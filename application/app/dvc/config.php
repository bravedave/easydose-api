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

	/*
	 *	Caching using APCu, Interfaced through https://www.scrapbook.cash/
	 * 	see dao\_dao
	 *
	 *	NOTE: If you enable this you need to have installed
	 *		* APC => dnf install php-pecl-apcu
	 *		* matthiasmullie/scrapbook => composer require matthiasmullie/scrapbook
	 */
 	// static $DB_CACHE = 'APC';	// values = 'APC'
	// static $DB_CACHE_DEBUG = TRUE;

}

pages\bootstrap::$BootStrap_Version = '4';
