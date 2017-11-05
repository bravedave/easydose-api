<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
Namespace paypal;

class auth {
	public $URL;
	public $EXPRESSCHECKOUTURL;
	public $USER;
	public $PWD;
	public $SIGNATURE;
	public $VERSION = '86';

	protected static $sandbox_url = 'https://api-3t.sandbox.paypal.com/nvp';
	protected static $url = 'https://api-3t.paypal.com/nvp';
	protected static $sandbox_ExpressCheckoutUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
	protected static $ExpressCheckoutUrl = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';


	function __construct( $sandbox = FALSE ) {
		if ( $sandbox ) {
			$this->URL = self::$sandbox_url;
			$this->EXPRESSCHECKOUTURL = self::$sandbox_ExpressCheckoutUrl;

		}
		else {
			$this->URL = self::$url;
			$this->EXPRESSCHECKOUTURL = self::$ExpressCheckoutUrl;

		}

	}

}