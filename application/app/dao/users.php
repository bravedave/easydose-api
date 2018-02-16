<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class users extends _dao {
	protected $_db_name = 'users';

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
<a href="%s%s">Reset Password</a>', \url::$PROTOCOL, \url::tostring('recover/&k=' . $guid));

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
