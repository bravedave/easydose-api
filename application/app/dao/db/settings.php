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
$dbc->defineField( 'paypal_ClientID', 'text');
$dbc->defineField( 'paypal_ClientSecret', 'text');
//~ $dbc->defineField( 'paypal_sandbox', 'int');
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
