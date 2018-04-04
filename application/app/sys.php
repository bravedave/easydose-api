<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
abstract class sys extends dvc\sys {
	protected static $_settings = FALSE;

	protected static function _settings() {
		if ( !self::$_settings) {
			self::$_settings = new dao\settings;

		}

		return ( self::$_settings);

	}

	static function name() {
		return ( self::_settings()->getName());

	}

	static function lockdown() {
		return ( self::_settings()->lockdown());

	}

	static function firstRun() {
		return ( self::_settings()->firstRun());

	}

	static function paypalAuth() {
		return ( self::_settings()->paypalAuth());

	}

	static function paypalLive() {
		return ( self::_settings()->paypalLive());

	}

	static function useSubscriptions() {
		return ( self::_settings()->useSubscriptions());

	}

	static function mailtest() {

		Response::text_headers();

		$mail = sys::mailer();

		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 1;


		$mail->addAddress(currentUser::email(), currentUser::name());

		$mail->Subject = sprintf( '%s Mail test', \config::$WEBNAME);
		$mail->isHTML(false);                                  // Set email format to HTML
		$mail->Body    = 'Mail test message';

		//send the message, check for errors
		if (!$mail->send()) {
			echo '<pre>Mailer Error: ' . $mail->ErrorInfo;

		}
		else {
			echo 'Message sent!';

		}

	}

	static function format_invoice_number( $s) {
		return ( sprintf( '%s%s', \config::invoice_prefix, str_pad( $s, 4, '0', STR_PAD_LEFT)));

	}

}
