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

Namespace dao;

class users extends _dao {
	protected $_db_name = 'users';

	function check() {
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


	function getUserByUserName( $username ) {
		if ( (string)$username) {
			if ( $res = $this->Result( sprintf( "SELECT * FROM users WHERE username = '%s'", $this->escape( $username))))
				return $res->dto();

		}

		return ( FALSE);

	}

	function getUserByEmail( $email) {
		if ( \strings::IsEmailAddress( $email)) {
			if ( $res = $this->Result( sprintf( "SELECT * FROM users WHERE `email` = '%s'", $this->escape( $email))))
				return $res->dto();

		}

		return ( FALSE);

	}

	function getByResetKey( $key) {
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

	function validate( $u, $p ) {

		if ( $u && $p) {
			$dto = FALSE;
			if ( \strings::IsEmailAddress( $u))
				$dto = $this->getUserByEmail( $u);
			else
				$dto = $this->getUserByUserName( $u);

			if ( $dto) {
				if ( password_verify( $p, $dto->pass)) {
					\dvc\session::edit();
					\dvc\session::set('uid', $dto->id);
					\dvc\session::close();
					return ( TRUE);

				}

			}

		}

		return ( FALSE);

	}

	function sendResetLink( $dto) {
		$guid = \strings::getGUID();
		$this->UpdateByID([
				'reset_guid' => $guid,
				'reset_guid_date' => \db::dbTimeStamp()
			], $dto->id);

		$mailMessage = sprintf( 'Reset your password?<br />
<br />
If you requested a password reset click the link below.<br />
If you didn\'t make this request, ignore this email.<br />
<br />
<a href="%s%s">Reset Password</a>', \url::$PROTOCOL, \url::tostring('recover/?k=' . $guid));

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

}
