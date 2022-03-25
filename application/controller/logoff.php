<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

use dvc\session;

class logoff extends Controller {
	public $RequireValidation = false;
	public $CheckOffline = false;

	function index() {
		session::destroy();
		Response::redirect();

	}

}
