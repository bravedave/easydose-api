<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
Namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'settings' );
$dbc->defineField( 'name', 'text');
$dbc->defineField( 'lockdown', 'int');
$dbc->defineField( 'paypal_live', 'int');
$dbc->defineField( 'paypal_ClientID', 'text');
$dbc->defineField( 'paypal_ClientSecret', 'text');
$dbc->defineField( 'street', 'text');
$dbc->defineField( 'town', 'text');
$dbc->defineField( 'state', 'text');
$dbc->defineField( 'postcode', 'text');
$dbc->defineField( 'use_subscription', 'int');
$dbc->defineField( 'bank_name', 'text');
$dbc->defineField( 'bank_bsb', 'text');
$dbc->defineField( 'bank_account', 'text');
$dbc->defineField( 'invoice_email', 'text');
$dbc->defineField( 'abn', 'text');
$dbc->check();

if ( $res = $this->db->Result( 'SELECT count(*) count FROM settings' )) {
	if ( $dto = $res->dto()) {
		if ( $dto->count < 1 ) {
			$a = [ 'name' => \config::$WEBNAME ];
			$this->db->Insert( 'settings', $a );

			\sys::logger( 'wrote system defaults');

		}

	}

}
