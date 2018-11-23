<?php
/*
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

namespace dvc;

abstract class config extends _config {
	static $DB_TYPE = 'sqlite';
	static $DATE_FORMAT = 'd/m/Y';

	static $EMAIL_ERRORS_TO_SUPPORT = true;	// when a trappable error occurs, email it to support email

	static $PAGE_TEMPLATE = '\page';
	//~ static $paypalSandbox = TRUE;
	static $paypalSandbox = false;


	static $SUPPORT_NAME = 'My.EasyDose Webmaster';
	static $SUPPORT_EMAIL = 'help@easydose.net.au';

	static $TIMEZONE = 'Australia/Perth';

	static $UTC_OFFSET = '+08:00';

	static $WEBNAME = 'My.EasyDose';

	const allow_password_recovery = true;

	const country_code = 'AU';

	const invoice_prefix = 'me.';

	const products = [ 'easydose5', 'easydose10', 'easydoseOPEN'];

	const show_db_reset = false;

	const tax_rate_devisor = 11;

	const use_inline_logon = true;

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
