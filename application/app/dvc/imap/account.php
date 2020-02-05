<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * This work is licensed under a Creative Commons Attribution 4.0 International Public License.
 *      http://creativecommons.org/licenses/by/4.0/
 *
*/

namespace dvc\imap;
use bCrypt;

abstract class account {
	static $SERVER = '';
	static $TYPE = '';
	static $NAME = '';
	static $EMAIL = '';
	static $USERNAME = '';
	static $PASSWORD = '';
	static $PROFILE = '';

	static $ENABLED = false;

    static function config() {
		return config::IMAP_DATA() . 'imap-account.json';

	}

    static function profile( string $profile) : string {
		if ( $_profile = preg_replace( '@[^0-9a-zA-Z]@', '', $profile)) {
			return sprintf( '%simap-profile-%s.json', config::IMAP_DATA(), $_profile);

		}

		return '';

	}

    static function profiles() : array {
		$ret = [];

		$iterator = new \Globiterator( sprintf( '%simap-profile-*.json', config::IMAP_DATA()));
		foreach ( $iterator as $config) {
			$j = json_decode( file_get_contents( $config));
			if ( isset( $j->profile)) {
				$ret[] = (object)[
					'name' => $config->getFilename(),
					'profile' => $j->profile,
					'path' => $config->getPathname(),

				];

			}

			// \sys::logger( sprintf('%s : %s', $config->getFilename(), __METHOD__));
			// \sys::logger( sprintf('%s : %s', $config->getPathname(), __METHOD__));

		}

		return $ret;

	}

	static function account_init() {
        if ( file_exists( $config = self::config())) {
			$j = json_decode( file_get_contents( $config));

			if ( isset( $j->server)) self::$SERVER = $j->server;
			if ( isset( $j->type)) self::$TYPE = $j->type;
			if ( isset( $j->name)) self::$NAME = $j->name;
			if ( isset( $j->email)) self::$EMAIL = $j->email;
			if ( isset( $j->username)) self::$USERNAME = $j->username;
			if ( isset( $j->password)) self::$PASSWORD = bCrypt::decrypt( $j->password);
			if ( isset( $j->profile)) self::$PROFILE = $j->profile;

			self::$ENABLED = ( (bool)self::$SERVER && (bool)self::$USERNAME && (bool)self::$PASSWORD);

		}

	}

}

account::account_init();

// \sys::dump( account::$SERVER);
