<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	you will want to make user 1 a programmer (probably)
		update users set programmer = 1 where id = 1;

	*/

namespace dao;

class users extends _dao {
	protected $_db_name = 'users';
	protected $template = '\dao\dto\users';

	const due = 1;
	const expired = 2;

	const created = 3;
	const sent = 4;

	public function check() {
		$dbc = new \dvc\sqlite\dbCheck( $this->db, 'users' );
		$dbc->defineField( 'username', 'text');
		$dbc->defineField( 'name', 'text');
		$dbc->defineField( 'email', 'text');
		$dbc->defineField( 'business_name', 'text');
		$dbc->defineField( 'street', 'text');
		$dbc->defineField( 'town', 'text');
		$dbc->defineField( 'state', 'text');
		$dbc->defineField( 'postcode', 'text');
		$dbc->defineField( 'abn', 'text');
		$dbc->defineField( 'pass', 'text');
		$dbc->defineField( 'admin', 'int');
		$dbc->defineField( 'programmer', 'int');
		$dbc->defineField( 'reset_guid', 'text');
		$dbc->defineField( 'reset_guid_date', 'text');
		$dbc->defineField( 'created', 'text');
		$dbc->defineField( 'updated', 'text');
		$dbc->check();

	}

	public function autoCreateInvoiceFromLast( $id) {
		if ( $user = $this->getByID( $id)) {
			$dao = new invoices;
			if ( $license = $dao->getActiveLicenseForUser( $user->id)) {
				$aInvoices = [
					'user_id' => $user->id,
					'created' => \db::dbTimeStamp(),
					'updated' => \db::dbTimeStamp()
				];

				$aInvoicesDetail = [];
				$products = new products;
				$license->wksCharged = 0;
				$license->wksLine = -1;
				foreach ( $license->license->lines as $line) {
					if ( $product = $products->getByID( $line->product_id)) {
						$aInvoicesDetail[] = [
							'user_id' => $user->id,
							'invoices_id' => 0,
							'product_id' => $line->product_id,
							'rate' => $product->rate,
							'created' => \db::dbTimeStamp(),
							'updated' => \db::dbTimeStamp()

						];

						if ( in_array( $product->name, [
							'WKSSTATION1',
							'WKSSTATION2',
							'WKSSTATION3',
							'WKSSTATION4',
							'WKSSTATION5'])) {
							$license->wksLine = count( $aInvoicesDetail) -1;

						}


						//~ printf( '%s<br />', $product->name);
						if ( in_array( $product->name, [
							'WKSSTATION1',
							'easydose5',
							'easydose10',
							'easydoseOPEN'])) {
							$license->wksCharged += 1;

						}
						elseif ( 'WKSSTATION2' == $product->name) {
							$license->wksCharged += 2;

						}
						elseif ( 'WKSSTATION3' == $product->name) {
							$license->wksCharged += 3;

						}
						elseif ( 'WKSSTATION4' == $product->name) {
							$license->wksCharged += 4;

						}
						elseif ( 'WKSSTATION5' == $product->name) {
							$license->wksCharged += 5;

						}


					} else { throw new \Exceptions\InvalidProduct; }

				}

				if ( count($aInvoicesDetail)) {

					// use during testing ...
					//~ $aInvoicesDetail[] = [
						//~ 'user_id' => 12,
						//~ 'invoices_id' => 0,
						//~ 'product_id' => 2,
						//~ 'rate' => 11,
						//~ 'created' => '2019-05-01 07:26:28',
						//~ 'updated' => '2019-05-01 07:26:28',

					//~ ];
					//~ $license->wksLine = count( $aInvoicesDetail) -1;
					// use during testing ...

					if ( $license->wksCharged < $license->workstations) {
						$msg = [
							'Upgrading Wks Charge',
							'---',
							sprintf( 'User: %s', $user->name),
							sprintf( 'Wks: %s/%s', $license->wksCharged, $license->workstations),

						];

						if ( 2 == $license->workstations) { $product = $products->getByName( 'WKSSTATION1'); }
						elseif ( 3 == $license->workstations) { $product = $products->getByName( 'WKSSTATION2'); }
						elseif ( 4 == $license->workstations) { $product = $products->getByName( 'WKSSTATION3'); }
						elseif ( 5 == $license->workstations) { $product = $products->getByName( 'WKSSTATION4'); }
						elseif ( 6 == $license->workstations) { $product = $products->getByName( 'WKSSTATION5'); }

						if ( $product) {
							if ( $license->wksLine < 0) {
								$aInvoicesDetail[] = [
									'user_id' => $user->id,
									'invoices_id' => 0,
									'product_id' => $product->id,
									'rate' => $product->rate,
									'created' => \db::dbTimeStamp(),
									'updated' => \db::dbTimeStamp()

								];

								$msg[] = sprintf( 'upgrade license : %s', $product->name);

							}
							else {
								$aInvoicesDetail[$license->wksLine]['product_id'] = $product->id;
								$aInvoicesDetail[$license->wksLine]['rate'] = $product->rate;
								$msg[] = sprintf( 'upgraded line to : %s', $product->name);

							}

							\sys::notifySupport('WKS Fix', implode( PHP_EOL, $msg));

						}
						else {
							$msg[] = 'failed : could not find a product for the workstation license';
							\sys::notifySupport('WKS Fix', implode( PHP_EOL, $msg));
							return;

						}


					}

					//~ \sys::dump( $aInvoices, 'Invoice', false);
					//~ \sys::dump( $aInvoicesDetail, 'Invoice Detail', false);
					//~ \sys::dump( $license);

					$dao = new invoices;
					$invID = $dao->Insert( $aInvoices);

					$dao = new invoices_detail;
					foreach ($aInvoicesDetail as $line) {
						$line['invoices_id'] = $invID;
						$dao->Insert( $line);

					}

					return ( $invID);

				} else { \sys::logger( sprintf( 'could not create autocreate invoice : FailedToCreateInvoice : %s', $user->name)); }

			} else { \sys::logger( sprintf( 'could not create autocreate invoice : NoActiveLicense : %s', $user->name)); }

		} else { \sys::logger( 'could not create autocreate invoice : InvalidAccount'); }

	}

	public function getAll( $fields = '*', $order = '' ) {
		if ( is_null( $this->_db_name)) {
			throw new Exceptions\DBNameIsNull;

		}

		$this->db->log = $this->log;
		// return ( $this->Result( sprintf( 'SELECT %s FROM %s %s', $fields, $this->db_name(), $order )));

		$this->Q('DROP TABLE IF EXISTS _tmpsites');
		// $this->Q('DROP TABLE IF EXISTS _tmpsitess');
		$this->Q('CREATE TEMPORARY TABLE _tmpsites AS SELECT s.guid guid, s.site site, g.user_id user_id, s.updated updated FROM sites s LEFT JOIN guid g on g.guid = s.guid GROUP BY s.guid ORDER BY s.updated DESC');
		// \sys::dump( $this->Result( 'SELECT * FROM _tmpsites LIMIT 2'));

		// $this->Q('CREATE TEMPORARY TABLE _tmpsitess AS SELECT s.*, g.user_id FROM _tmpsites s LEFT JOIN guid g on g.guid = s.guid');

		// \sys::logger('-------------------------------------------------');
		$sql = sprintf( 'SELECT u.*, s.site FROM users u LEFT JOIN _tmpsites s ON u.id = s.user_id %s', $order );
		// $sql = 'SELECT u.*, s.`site` FROM users u LEFT JOIN _tmpsites s ON u.id = s.user_id';
		// \sys::logSQL( $sql);
		return ( $this->Result( $sql));
		// \sys::logger('-------------------------------------------------');

	}

	public function getByResetKey( $key) {
		if ( substr( $key, 0, 1) == '{' && substr( $key, -1) == '}') {
			if ( $res = $this->Result( sprintf( "SELECT * FROM users WHERE `reset_guid` = '%s'", $this->escape( $key)))) {
				if ( $dto = $res->dto()) {
					// \sys::logger( time() - strtotime($dto->reset_guid_date));
					if ( time() - strtotime($dto->reset_guid_date) < 3600) {
						// it's good for 1 hour
						return ( $dto);

					}
					// else {
					// 	$this->UpdateByID( ['reset_guid_date' => \db::dbTimeStamp()], $dto->id);
					//
					// }

				}

			}

		}

		return ( FALSE);

	}

	public function getUserByEmail( $email) {
		if ( \strings::IsEmailAddress( $email)) {
			if ( $res = $this->Result( sprintf( "SELECT * FROM users WHERE `email` = '%s'", $this->escape( $email))))
			return $res->dto();

		}

		return ( FALSE);

	}

	public function getUserByUserName( $username ) {
		if ( (string)$username) {
			if ( $res = $this->Result( sprintf( "SELECT * FROM users WHERE username = '%s'", $this->escape( $username))))
				return $res->dto();

		}

		return ( FALSE);

	}

	public function getUserLicenses() {
		$debug = false;
		// $debug = true;

		$sql = "SELECT
				u.id,
				u.name,
				u.business_name,
				inv.id inv_id,
				inv.state,
				inv.created
			FROM users u
				LEFT JOIN (
					SELECT id, state, user_id, created
					FROM invoices
					WHERE 'canceled' != state
					GROUP BY user_id
					ORDER BY id DESC) inv
			WHERE
				inv.user_id = u.id";

		if ( $res = $this->Result( $sql)) {
			$this->Q( "CREATE TEMPORARY TABLE _lic (
				id INTEGER DEFAULT 0,
				name TEXT NOT NULL DEFAULT '',
				business_name TEXT NOT NULL DEFAULT '',
				license TEXT NOT NULL DEFAULT '',
				last_invoice TEXT NOT NULL DEFAULT '',
				last_invoice_state TEXT NOT NULL DEFAULT '',
				last_invoice_created TEXT NOT NULL DEFAULT '',
				workstations INTEGER DEFAULT 0,
				expires TEXT NOT NULL DEFAULT '',
				due INTEGER DEFAULT 0,
				unpaid_invoice INTEGER DEFAULT 0

			)");

			$data = $res->dtoSet( function( $dto) use( $debug) {
				$a = [
					'id' => $dto->id,
					'name' => $dto->name,
					'business_name' => $dto->business_name,
					'last_invoice' => $dto->inv_id,
					'last_invoice_state' => $dto->state,
					'last_invoice_created' => $dto->created,

				];

				$dao = new invoices;
				if ( $license = $dao->getActiveLicenseForUser( $dto->id)) {
					$a['license'] = $license->product;
					$a['workstations'] = $license->workstations;
					$a['expires'] = $license->expires;

					//~ if ( date( 'Y-m-d', strtotime( $license->expires)) < date( 'Y-m-d')) {

					$lex = new \DateTime( $license->expires);
					$tod = new \DateTime;
					$interval = $tod->diff( $lex);
					if ( $debug) \sys::logger( sprintf('#%d : %s(%s - %s) : %s', $dto->id,
						$license->expires,
						$interval->days,
						$interval->format('%R%a days'),
						__METHOD__));
					if ( (int)$interval->format('%R%a') < settings::get('invoice_creation_days')) {
						if ( $interval->format('%R%a') < 0) {
							$a['due'] = self::expired;

						}
						else {
							$a['due'] = self::due;
							//~ \sys::logger( $interval->days);

						}

						if ( $dtoUP = $dao->getUnpaidForUser( $dto->id)) {
							if ( $dtoUP->state == 'sent' ) {
								$a['unpaid_invoice'] = self::sent;

							}
							else {
								$a['unpaid_invoice'] = self::created;

							}

						}

					}

				}

				$this->db->Insert( '_lic', $a);

				return ( $dto);

			});

			$this->Q( 'DROP TABLE IF EXISTS _lic_debug');
			if ( $debug) {
				$this->Q( 'CREATE TABLE _lic_debug AS SELECT * FROM _lic');

			}

			return( $this->Result( sprintf( "SELECT * FROM _lic WHERE due <> %d AND license <> '' ORDER BY expires ASC", self::expired)));
			// return( $this->Result("SELECT * FROM _lic WHERE license <> '' ORDER BY expires ASC"));

		}

		return false;

	}

	public function sendResetLink( $dto) {
		$guid = \strings::getGUID();
		$this->UpdateByID([
			'reset_guid' => $guid,
			'reset_guid_date' => \db::dbTimeStamp()
		], $dto->id);

		$mailMessage = sprintf(
			'Reset your password?<br />
			<br />
			If you requested a password reset click the link below.<br />
			If you didn\'t make this request, ignore this email.<br />
			<br />
			<a href="%s%s">Reset Password</a>',
			\url::$PROTOCOL,
			\url::tostring('recover/?k=' . $guid)

		);

		$mail = \sys::mailer();

		// $mail->AddReplyTo( $user->email, $user->name);
		$mail->Subject  = \config::$WEBNAME . " Password Recovery";
		$mail->AddAddress( $dto->email, $dto->name );

		$mail->MsgHTML( $mailMessage);

		try {
			if ( $mail->send()) {
				return ( true);

			}
			else {
				\sys::logger( 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
				return ( FALSE);

			}


		}
		catch( \Exception $e) {
			\sys::logger( 'dao\users->sendResetLink :: Could not send error email');
			return ( false);

		}

	}

	public function validate( $u, $p ) {

		if ( $u && $p) {
			$dto = FALSE;
			if ( \strings::IsEmailAddress( $u)) {
				$dto = $this->getUserByEmail( $u);

			}
			else {
				$dto = $this->getUserByUserName( $u);

			}

			if ( $dto) {
				if ( password_verify( $p, $dto->pass)) {
					\dvc\session::edit();
					\dvc\session::set('uid', $dto->id);
					\dvc\session::close();

					return ( true);

				}

			}

		}

		return ( false);

	}

}
