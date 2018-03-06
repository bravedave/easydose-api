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

	static function useSubscriptions() {
		return ( self::_settings()->useSubscriptions());

	}

}
