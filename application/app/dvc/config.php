<?php
/**
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		global programmable settings

	note:
	 	dvc defaults will be set in the parent class

	*/
	
NameSpace dvc;

abstract class config extends _config {
	static $WEBNAME = 'A PSR style PHP Framework';
	static $DB_TYPE = 'sqlite';
	static $DATE_FORMAT = 'd-M-Y';

	const use_inline_logon = TRUE;

	//~ static $paypalSandbox = TRUE;
	static $paypalSandbox = FALSE;

}

pages\bootstrap::$BootStrap_Version = '4';
