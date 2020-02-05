<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

abstract class currentUser extends dvc\currentUser {
	static function email() {
		return ( self::user()->email);

	}

	static function id() {
		return ( self::user()->id);

	}

	static function isAdmin() {
		if ( \sys::lockdown()) {
			return ( (int)self::user()->admin == 1);

		}
		else {
			return (parent::isAdmin());

		}
		// \sys::dump( self::user());

	}

	static public function isProgrammer() {
		// return ( FALSE);
		if ( isset( self::user()->programmer)) {
			return ( self::user()->programmer == 1 );

		}

		return ( self::isAdmin());

		// return ( FALSE);

	}

	static function name() {
		return ( self::user()->name);

	}

	static public function option( $key, $value = null) {
		if ( self::user()->valid())
			return ( self::user()->option( $key, $value));

		return ( false);

	}

	static function username() {
		return ( self::user()->username);

	}

}
