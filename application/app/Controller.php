<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
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
