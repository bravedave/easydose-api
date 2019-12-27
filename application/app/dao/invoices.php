<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

namespace dao;
use strings;
use sys;

class invoices extends _dao {
	protected $_db_name = 'invoices';
	protected $template = '\dao\dto\invoices';

	protected function _check_expiry( \dao\dto\invoices $dto) {
		if ( !$dto->expires) {
			$created = strtotime( isset($dto->effective) ? $dto->effective : $dto->created);
			$dto->expires = date( 'Y-m-d', strtotime( '+1 year', $created));

		}

		return ( $dto);

	}

	static function isProvisional( dto\invoices $dto) {
		if ( 'provisional' == $dto->state) {
			$cDate = new \DateTime($dto->created);
			$diff = $cDate->diff( new \DateTime());
			if ( $diff->days < \config::provisional_invoice_grace) {
				return true;

			}

		}

		return false;

	}

	static function ProvisionalExpiry( dto\invoices $dto) {
		$d = new \DateTime( $dto->created);
		$d->add( new \DateInterval( sprintf( 'P%dD', \config::provisional_invoice_grace)));

		return $d->format('Y-m-d');

	}

	public function getInvoice( \dao\dto\invoices $dto) {
		$dao = new invoices_detail;
		if ( $lines = $dao->getLines( $dto)) {
			$dto->lines = $lines;
			$tot = 0;
			foreach ( $lines as $line) {
				$tot += (float)$line->rate;

			}

			$tot -= (float)$dto->discount;

			$dto->total = $tot;
			$dto->tax = $tot / \config::tax_rate_devisor;

			$this->_check_expiry( $dto);

		}

		return ( $dto);

	}

	public function getAll( $fields = 'i.*, u.name user_name, s.site', $order = '') {

		$this->Q('DROP TABLE IF EXISTS _tmpsites');
		$this->Q('DROP TABLE IF EXISTS _tmpsitess');
		$this->Q('CREATE TEMPORARY TABLE _tmpsites AS SELECT guid, site, updated FROM sites GROUP BY guid ORDER BY updated DESC');
		$this->Q('CREATE TEMPORARY TABLE _tmpsitess AS SELECT s.*, g.user_id FROM _tmpsites s LEFT JOIN guid g on g.guid = s.guid');
		// $_sql = sprintf( 'SELECT %s
		// 	FROM invoices i
		// 		LEFT JOIN
		// 	 		users u ON u.id = i.user_id %s', $fields, $order);
		$_sql = sprintf( 'SELECT %s
			FROM invoices i
			LEFT JOIN
					users u ON u.id = i.user_id
				LEFT JOIN
					_tmpsitess s ON s.user_id = i.user_id %s
			ORDER BY i.id DESC', $fields, $order);

		// sys::logSQL( $_sql);

		return ( $this->Result( $_sql));

	}

	public function getForUser( $userID = 0) {
		$debug = false;
		// $debug = true;

		if ( !(int)$userID) {
			$userID = \currentUser::id();

		}

		$sql = sprintf(
			"SELECT
				*
			FROM
				invoices
			WHERE
				ifnull(state,'') <> 'canceled' AND user_id = %s
			ORDER BY
				CASE ifnull( expires, '')
					WHEN '' THEN '9999-99-99'
					ELSE expires
				END ASC, created ASC", $userID);

		if ( $res = $this->Result( $sql)) {
			// if ( $debug) sys::dump( $res);
			return ( $res->dtoSet( null, $this->template));

		}

		return ( false);

	}

	public function getActiveLicenseForUser( $userID = 0) {
		$debug = false;
		// $debug = true;

		if ( $debug) sys::logger( sprintf( '---------------[%s]-------------', __METHOD__));

		$license = false;

		if ( $dtoSet = $this->getForUser( $userID)) {
			/* look up and return the first active license */
			$lastExpire = false;

			$dao = new guid;
			if ( $dtoSetGUID = $dao->getForUser( $userID)) {
				if ( count( $dtoSetGUID)) {
					if ( $dtoGratis = $dao->getGratisLicenseOf( $dtoSetGUID[0])) {
						$lastExpire = $dtoGratis->expires;

					}

				}

			}

			//~ sys::dump( $dtoSet);
			$finalWorkstations = 0;
			$finalWorkstationExtensions = 0;
			foreach ( $dtoSet as $dto) {
				if ( $dto->license_exclusion) continue;

				$dto->effective = $dto->created;
				if ( !( $dto->state == 'approved' || self::isProvisional( $dto) && $dto->authoritative)) {
					/**
					 * if it is not an invoice with is approved and authoritive
					 * the effective date may already be established and we can use that,
					 * this invoice could extend it.
					 * */
					if ( $lastExpire) $dto->effective = $lastExpire;

				}

				//~ if ( $dto->id == 138) sys::dump( $dto);
				$_expires = $dto->expires;	// before contamination
				$this->_check_expiry( $dto);
				$lastExpire = $dto->expires;

				// if ( ( $dto->state == 'approved' || self::isProvisional( $dto)) && $dto->expires >= date( 'Y-m-d')) {
				if ( ( $dto->state == 'approved' || self::isProvisional( $dto)) && strtotime( $dto->expires) > 0) {
					if ( $ret = $this->getInvoice( $dto)) {
						if ( $license && !$dto->authoritative) {
							foreach ( $ret->lines as $line) {
								if ( in_array( $line->name, \config::products)) {
									// there is an active license this is an extension
									if ( strtotime( $_expires) > 0) {
										$license->expires = date( 'Y-m-d', strtotime( $_expires));	// before contamination
										if ( $debug) sys::logger( sprintf( '%s: %s - absolute :: %s', $dto->id, $license->expires, __METHOD__));


									}
									else {
										// this has adjusted the expiry date of this invoice
										$dto->expires = $license->expires = date( 'Y-m-d', strtotime( '+1 year', strtotime( $license->expires)));
										if ( $debug) sys::logger( sprintf( '%s: %s - extending :: %s', $dto->id, $license->expires, __METHOD__));


									}

									/**
									 * not sure about this calculation ..
									 * what if there is a valid workstation extension expiring from before this license ...
									 *
									 * so we introduce $finalWorkstationExtensions - which are all the valid extensions
									 * so the license is
									 * 	- what is active, usually 1, but may have an override
									 *  - plus any non expired workstations
									 * 		- so if you purchase an extension in month 6 of your subscription
									 * 		- then for the first period of the following subscription you will have
									 * 		- an extra workstation ... if you buy workstations as part of that invoice
									 *
									 * */
									// if ($finalWorkstations < 1) $finalWorkstations = 1;
									$finalWorkstations = 1;
									if ( (int)$dto->workstation_override) {
										if ( strings::DateDiff( $license->expires) < 0) {
											$finalWorkstations = $dto->workstation_override;
											if ( $debug) \sys::logger( sprintf('finalWorkstations (extended) : %s : %s', $finalWorkstations, __METHOD__));

											$finalWorkstationExtensions = 0;

										}

									}

								}
								elseif ( 'WKSSTATION1' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) {
										$finalWorkstationExtensions += 1;
										if ( $debug) \sys::logger( sprintf('%s: WKSSTATION1 : %s (exp:%s) : %s', $dto->id, $finalWorkstationExtensions, $dto->expires, __METHOD__));

									}
									else {
										if ( $debug) \sys::logger( sprintf('%s: WKSSTATION1 expired : %s : %s', $dto->id, $dto->expires,  __METHOD__));

									}


								}
								elseif ( 'WKSSTATION2' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) $finalWorkstationExtensions += 2;
									if ( $debug) \sys::logger( sprintf('WKSSTATION2 : %s : %s', $finalWorkstationExtensions, __METHOD__));

								}
								elseif ( 'WKSSTATION3' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) $finalWorkstationExtensions += 3;
									if ( $debug) \sys::logger( sprintf('WKSSTATION3 : %s : %s', $finalWorkstationExtensions, __METHOD__));

								}
								elseif ( 'WKSSTATION4' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) $finalWorkstationExtensions += 4;
									if ( $debug) \sys::logger( sprintf('WKSSTATION4 : %s : %s', $finalWorkstationExtensions, __METHOD__));

								}
								elseif ( 'WKSSTATION5' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) $finalWorkstationExtensions += 5;
									if ( $debug) \sys::logger( sprintf('WKSSTATION5 : %s : %s', $finalWorkstationExtensions, __METHOD__));

								}

							}

						}
						else {

							if ( $debug) sys::logger( sprintf( '%s : %s : %s :: %s', $dto->id, $dto->state, $dto->expires, __METHOD__));

							$license = new dto\license;
							$license->type = 'LICENSE';
							$license->expires = $dto->expires;
							foreach ( $ret->lines as $line) {
								if ( in_array( $line->name, \config::products)) {
									$license->product = $line->name;
									$license->description = $line->description;
									if ( strings::DateDiff( $dto->expires) < 0) {
										$finalWorkstations = 1;

									}
									$license->state = 'active';
									// $license->state = $dto->expires > date('Y-m-d') ? 'active' : 'inactive';

								}
								elseif ( 'WKSSTATION1' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) $finalWorkstationExtensions += 1;

								}
								elseif ( 'WKSSTATION2' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) $finalWorkstationExtensions += 2;

								}
								elseif ( 'WKSSTATION3' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) $finalWorkstationExtensions += 3;

								}
								elseif ( 'WKSSTATION4' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) $finalWorkstationExtensions += 4;

								}
								elseif ( 'WKSSTATION5' == $line->name ) {
									if ( strings::DateDiff( $dto->expires) < 0) $finalWorkstationExtensions += 5;

								}

								if ( 'active' == $license->state) {
									$license->license = $ret;

								}

							}

							if ($dto->workstation_override) {
								if ( strings::DateDiff( $dto->expires) < 0) {
									$finalWorkstations = $dto->workstation_override;
									if ( $debug) \sys::logger( sprintf('finalWorkstations (authorative) : %s : %s', $finalWorkstations, __METHOD__));

									$finalWorkstationExtensions = 0;

								}
								else {
									if ( $debug) \sys::logger( sprintf('expired finalWorkstations overide (authorative) : %s', __METHOD__));

								}

							}

							// sys::dump( $ret);

						}

					}

				}
				else {
					if ( $debug) sys::logger( sprintf( '%s : %s(%s) :: %s',
						$dto->id,
						$dto->state,
						$dto->expires,
						'approved' == $dto->state ? 'yes' : 'no',
						__METHOD__));


				}

			}

			if ( $license) {
				$license->workstations = $finalWorkstations + $finalWorkstationExtensions;

			}

			if ( $debug) \sys::logger( sprintf( '<workstations : %s + %s = %s : %s',
				$finalWorkstations,
				$finalWorkstationExtensions,
				$license->workstations,
				__METHOD__));


		}

		// only return if license has not expired ?
		if ( $license && $license->expires >= date('Y-m-d')) {
			if ( $debug) {
				\sys::logger( sprintf( '--- ---[ found %s license : %s(%s) : %s ]--- ---',
					$license->state,
					$license->description,
					$license->workstations,
					__METHOD__));

			}

			return ( $license);

		}

		if ( $debug) sys::logger( sprintf( '---------------[%s]-------------', __METHOD__));
		return null;

	}

	public function getUnpaidForUser( $userID = 0) {
		$debug = false;
		// $debug = true;

		if ( !(int)$userID) {
			$userID = \currentUser::id();

		}

		$sql = sprintf(
			"SELECT
				*
			FROM
				invoices
			WHERE
				ifnull(state,'') <> 'canceled' AND ifnull(state,'') <> 'approved' AND user_id = %s
			ORDER BY
				CASE ifnull( expires, '')
					WHEN '' THEN '9999-99-99'
					ELSE expires
				END ASC, created ASC", $userID);

		if ( $res = $this->Result( $sql)) {
			// if ( $debug) sys::dump( $res);
			return ( $res->dto( $this->template));

		}

		return ( false);

	}

	public function send( dto\invoices $dto) {
		$users = new users;
		$settings = new settings;
		$daoLicense = new license;

		if ( $account = $users->getByID( $dto->user_id)) {
			$sys = $settings->getFirst();

			$inv = new \invoice(
				$sys,
				$account,
				$this->getInvoice( $dto),
				$daoLicense->getLicense( $dto->user_id)
				);

			$html = $inv->render();
			//~ print $html;

			$mail = sys::mailer();
			$mail->CharSet = 'UTF-8';
			$mail->Encoding = 'base64';

			if ( strings::IsEmailAddress( $sys->invoice_email)) {
				$mail->SetFrom( $sys->invoice_email, \config::$WEBNAME);

			}

			$mail->Subject  = \config::$WEBNAME . " Invoice";
			$mail->AddAddress( $account->email, $account->name );
			// $mail->AddAddress( 'david@brayworth.com.au', 'David Bray' );

			$mail->MsgHTML( $html);

			if ( $mail->send()) {
				//~ print '<h2>Sent</h2>';

				$a = [
					'state' => 'sent',
					'state_change' => 'manual',
					'state_changed' => \db::dbTimeStamp(),
					'state_changed_by' => \currentUser::id(),
					'updated' => \db::dbTimeStamp()

				];

				$this->UpdateByID( $a, $dto->id);

			}
			else {
				//~ print '<h2>Error - NOT Sent</h2>';
				sys::logger( 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);

			}

		}

	}

}
