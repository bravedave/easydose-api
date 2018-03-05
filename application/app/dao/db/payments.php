<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

  description:
    definition file for agreements table

	*/
Namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'payments' );
$dbc->defineField( 'payment_id', 'text');
$dbc->defineField( 'state', 'text');
$dbc->defineField( 'cart', 'text');
$dbc->defineField( 'product_id', 'int');
$dbc->defineField( 'invoices_id', 'int');
$dbc->defineField( 'name', 'text');
$dbc->defineField( 'description', 'text');
$dbc->defineField( 'tax', 'text');
$dbc->defineField( 'value', 'text');
$dbc->defineField( 'user_id', 'int');
$dbc->defineField( 'created', 'text');
$dbc->defineField( 'updated', 'text');
$dbc->check();
