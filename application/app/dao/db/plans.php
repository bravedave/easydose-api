<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
Namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'plans' );
$dbc->defineField( 'paypal_id', 'text');
$dbc->defineField( 'name', 'text');
$dbc->defineField( 'description', 'text');
$dbc->defineField( 'state', 'text');
$dbc->defineField( 'frequency', 'text');
$dbc->defineField( 'rate', 'float');
//~ $dbc->defineField( 'paypal_sandbox', 'int');
$dbc->check();
