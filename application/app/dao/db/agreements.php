<?php
/**
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

  description:
    definition file for agreements table

	*/
Namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'agreements' );
$dbc->defineField( 'token', 'text');
$dbc->defineField( 'result', 'text');
$dbc->defineField( 'agreement_id', 'text');
$dbc->defineField( 'plan_id', 'text');
$dbc->defineField( 'name', 'text');
$dbc->defineField( 'description', 'text');
$dbc->defineField( 'payment_method', 'text');
$dbc->defineField( 'start_date', 'text');
$dbc->defineField( 'next_billing_date', 'text');
$dbc->defineField( 'frequency', 'text');
$dbc->defineField( 'cycles_completed', 'text');
$dbc->defineField( 'value', 'text');
$dbc->defineField( 'refreshed', 'text');
$dbc->defineField( 'state', 'text');
$dbc->defineField( 'user_id', 'int');
//~ $dbc->defineField( 'frequency', 'text');
//~ $dbc->defineField( 'rate', 'float');
//~ $dbc->defineField( 'paypal_sandbox', 'int');
$dbc->check();
