<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
Namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'invoices' );
$dbc->defineField( 'state', 'text');
$dbc->defineField( 'cart', 'text');
$dbc->defineField( 'user_id', 'text');
$dbc->defineField( 'created', 'text');
$dbc->defineField( 'updated', 'text');
$dbc->check();