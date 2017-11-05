<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	Reference:
		https://developer.paypal.com/docs/classic/express-checkout/ht_ec-recurringPaymentProfile-curl-etc/


	Endpoint URL: https://api-3t.sandbox.paypal.com/nvp
	HTTP method: POST
	POST data:
	USER=insert_merchant_user_name_here
	&PWD=insert_merchant_password_here
	&SIGNATURE=insert_merchant_signature_value_here
	&METHOD=SetExpressCheckout
	&VERSION=86
	&L_BILLINGTYPE0=RecurringPayments    #The type of billing agreement
	&L_BILLINGAGREEMENTDESCRIPTION0=FitnessMembership    #The description of the billing agreement
	&cancelUrl=http://www.yourdomain.com/cancel.html    #For use if the consumer decides not to proceed with payment
	&returnUrl=http://www.yourdomain.com/success.html   #For use if the consumer proceeds with payment

	Response
	--------
	TOKEN=EC%2d2B984685J43051234
	&ACK=Success
	*/
Namespace paypal;

class api {
	static function getToken( auth $auth, $params) {
		$api = new api( $auth);
		if ( $api->getAuthToken( $params))
			return ( $api);

		//~ \sys::dump( $params, NULL, FALSE );
		//~ \sys::dump( $api );

		return ( FALSE );

	}

	static function GetExpressCheckoutDetails( auth $auth, $token) {
		$api = new api( $auth);
		if ( $api->_GetExpressCheckoutDetails( $token))
			return ( $api);

		return ( FALSE );

	}

	public function GetRecurringPaymentsProfileDetails( auth $auth, $profileid) {
		$api = new api( $auth);
		if ( $api->_GetRecurringPaymentsProfileDetails( $profileid))
			return ( $api);

		return ( FALSE );

	}

	public function CancelRecurringPayment( auth $auth, $profileid) {
		$api = new api( $auth);
		if ( $api->_CancelRecurringPayment( $profileid))
			return ( $api);

		return ( FALSE );

	}

	//~ protected static $url = 'https://api-3t.sandbox.paypal.com/nvp';
	//~ protected static $url = 'https://api-3t.paypal.com/nvp';
	//~ protected static $_ExpressCheckoutUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
	//~ protected static $_ExpressCheckoutUrl = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
	protected $_response = '';
	protected $params = array();

	protected $URL, $ExpressCheckOutURL;

	private function __construct( auth $auth) {
		$this->URL = $auth->URL;
		$this->ExpressCheckOutURL = $auth->EXPRESSCHECKOUTURL;

		$this->params = array(
			"USER" => $auth->USER,
			"PWD" => $auth->PWD,
			"SIGNATURE" => $auth->SIGNATURE,
			"VERSION" => $auth->VERSION 	);

		//~ $params["PWD"] = 'notvalid';	// will cause failure

	}

	public function response() {
		return $this->_response;

	}

	public function token() {
		return $this->_response->TOKEN;

	}

	public function profileid() {
		return $this->_response->PROFILEID;

	}

	public function profilestatus() {
		return $this->_response->PROFILESTATUS;

	}

	public function nextbillingdate() {
		if ( $this->status() == 'Active' )
			return date( 'c', strtotime( $this->_response->NEXTBILLINGDATE));

		return '';
	}

	public function status() {
		return $this->_response->STATUS;

	}

	public function billingagreementacceptedstatus() {
		return $this->_response->BILLINGAGREEMENTACCEPTEDSTATUS;

	}

	public function payerid() {
		return $this->_response->PAYERID;

	}

	public function ExpressCheckoutURL() {
		return  $this->ExpressCheckOutURL . urlencode( $this->token());

	}

	protected static function DeCodeResponse( $responseString) {
		// decode the incoming string as JSON
		$b = [];
		if ( 0 !== strlen( $responseString)) {
			$a = explode( '&', $responseString );
			foreach ( $a as $s ) {
				$x = explode( '=', $s );
				$b[$x[0]] = urldecode( $x[1] );

			}

			if ( !isset( $b['ACK'] ))
				$b['ACK'] = 'Failure';

		}
		else {
			$b['ACK'] = 'Failure';

		}

		return (  (object)$b);

	}

	protected function getAuthToken( $params ) {
		$this->params["METHOD"] = 'SetExpressCheckout';
		foreach ( $params as $k => $v )
			$this->params[$k] = $v;

		// build a new HTTP POST request
		$request = new HttpPost( $this->URL);
		$request->setPostData( $this->params);
		$request->send();

		$this->_response = self::DeCodeResponse( $request->getResponse());
		return ( $this->_response->ACK == 'Success' );

	}

	protected function _GetExpressCheckoutDetails( $token ) {
		$this->params["METHOD"] = 'GetExpressCheckoutDetails';
		$this->params["TOKEN"] = urlencode( $token);

		// build a new HTTP POST request
		$request = new HttpPost( $this->URL);
		$request->setPostData( $this->params);
		$request->send();

		$this->_response = self::DeCodeResponse( $request->getResponse());
		return ( $this->_response->ACK == 'Success' );

	}

	public function CreateRecurringPaymentsProfile( $params ) {
		$this->params["METHOD"] = 'CreateRecurringPaymentsProfile';
		$this->params["TOKEN"] = urlencode( $this->token());
		$this->params["PAYERID"] = urlencode( $this->payerid());
		$this->params["PROFILESTARTDATE"] = $params['PROFILESTARTDATE'];		// Billing date start, in UTC/GMT format
		$this->params["DESC"] = $params['DESC'];								// Profile description - same as billing agreement description
		$this->params["BILLINGPERIOD"] = $params['BILLINGPERIOD'];				// Period of time between billings
		$this->params["BILLINGFREQUENCY"] = $params['BILLINGFREQUENCY'];		// Frequency of charges
		$this->params["AMT"] = $params['AMT'];									// The amount the buyer will pay in a payment period
		$this->params["CURRENCYCODE"] = $params['CURRENCYCODE'];				// The currency, e.g. US dollars
		$this->params["COUNTRYCODE"] = $params['COUNTRYCODE'];				// The country code, e.g. US
		$this->params["MAXFAILEDPAYMENTS"] = $params['MAXFAILEDPAYMENTS'];	// Maximum failed payments before suspension of the profile

		// build a new HTTP POST request
		$request = new HttpPost( $this->URL);
		$request->setPostData( $this->params);
		$request->send();

		$this->_response = self::DeCodeResponse( $request->getResponse());
		return ( $this->_response->ACK == 'Success' );
		/*
			Response
			--------
			PROFILEID=I%2d6D5UGCVX1234
			&PROFILESTATUS=ActiveProfile
			&ACK=Success
		*/

	}

	protected function _GetRecurringPaymentsProfileDetails( $profileid ) {
		$this->params["METHOD"] = 'GetRecurringPaymentsProfileDetails';
		$this->params["PROFILEID"] = $profileid;

		// build a new HTTP POST request
		$request = new HttpPost( $this->URL);
		$request->setPostData( $this->params);
		$request->send();

		$this->_response = self::DeCodeResponse( $request->getResponse());
		return ( $this->_response->ACK == 'Success' );

	}

	protected function _CancelRecurringPayment( $profileid ) {
		$this->params["METHOD"] = 'ManageRecurringPaymentsProfileStatus';
		$this->params["PROFILEID"] = $profileid;
		$this->params["ACTION"] = 'cancel';

		// build a new HTTP POST request
		$request = new HttpPost( $this->URL);
		$request->setPostData( $this->params);
		$request->send();

		$this->_response = self::DeCodeResponse( $request->getResponse());
		return ( $this->_response->ACK == 'Success' );

	}

}