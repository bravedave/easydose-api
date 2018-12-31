<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

namespace dao;

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

	public function getInvoice( \dao\dto\invoices $dto) {
		$dao = new invoices_detail;
		if ( $lines = $dao->getLines( $dto)) {
			$dto->lines = $lines;
			$tot = 0;
			foreach ( $lines as $line) {
				$tot += (float)$line->rate;

			}

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

		// \sys::logSQL( $_sql);

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
			// if ( $debug) \sys::dump( $res);
			return ( $res->dtoSet( null, $this->template));

		}

		return ( false);

	}

	public function getActiveLicenseForUser( $userID = 0) {
		$debug = false;
		// $debug = true;

		$license = false;

		if ( $dtoSet = $this->getForUser( $userID)) {
			/* look up and return the first active license
			*/
			$lastExpire = false;

			$dao = new guid;
			if ( $dtoSetGUID = $dao->getForUser( $userID)) {
				if ( count( $dtoSetGUID)) {
					if ( $dtoGratis = $dao->getGratisLicenseOf( $dtoSetGUID[0])) {
						$lastExpire = $dtoGratis->expires;

					}

				}

			}

			foreach ( $dtoSet as $dto) {

				$dto->effective = ( $lastExpire ? $lastExpire : $dto->created);

				$this->_check_expiry( $dto);

				$lastExpire = $dto->expires;

				if ( $dto->state == 'approved' && $dto->expires >= date( 'Y-m-d')) {
					if ( $ret = $this->getInvoice( $dto)) {
						if ( $license) {
							// there is an active license this is an extension
							$license->expires = date( 'Y-m-d', strtotime( '+1 year', strtotime( $license->expires)));
							if ( $debug) \sys::logger( sprintf( 'dao\invoices->getActiveLicenseForUser : %s: %s - extending', $dto->id, $license->expires));

						}
						else {

							if ( $debug) \sys::logger( sprintf( 'dao\invoices->getActiveLicenseForUser : %s : %s', $dto->id, $dto->expires));

							$license = new dto\license;
							$license->type = 'LICENSE';
							$license->expires = $dto->expires;
							foreach ( $ret->lines as $line) {
								if ( in_array( $line->name, \config::products)) {
									$license->product = $line->name;
									$license->description = $line->description;
									$license->workstations += 1;
									$license->state = 'active';

								}
								elseif ( 'WKSSTATION1' == $line->name ) {
									$license->workstations += 1;
								}
								elseif ( 'WKSSTATION2' == $line->name ) {
									$license->workstations += 2;
								}
								elseif ( 'WKSSTATION3' == $line->name ) {
									$license->workstations += 3;
								}
								elseif ( 'WKSSTATION4' == $line->name ) {
									$license->workstations += 4;
								}
								elseif ( 'WKSSTATION5' == $line->name ) {
									$license->workstations += 5;
								}

								if ( 'active' == $license->state) {
									$license->license = $ret;

								}

							}

							if ($dto->workstation_override) {
								$license->workstations = $dto->workstation_override;

							}

							// \sys::dump( $ret);

						}

					}

				}

			}

		}

		return ( $license);

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
			// if ( $debug) \sys::dump( $res);
			return ( $res->dto( $this->template));

		}

		return ( false);

	}

}
