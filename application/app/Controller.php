<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

class Controller extends dvc\Controller {
	const easydose_version = 0.01;

	protected function before() {
		if ( config::easydose_version() < self::easydose_version) {
			config::easydose_version( self::easydose_version);
			$dao = new dao\dbinfo;
			$dao->dump( $verbose = false);

			// sys::logger( 'bro!');

		}

		parent::before();

	}

}
