<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

class application extends dvc\application {
	private $_settings = false;

	const use_full_url = false;

	static function run() {
		// dvc\core\application::$debug = true;
		$app = new self( dirname( __FILE__ ) . '/../' );

	}

}
