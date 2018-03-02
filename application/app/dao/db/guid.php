<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
Namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'guid' );
$dbc->defineField( 'guid', 'text');
$dbc->defineField( 'user_id', 'int');
$dbc->defineField( 'agreements_id', 'int');
$dbc->defineField( 'created', 'text');
$dbc->defineField( 'updated', 'text');
$dbc->defineField( 'grace_product', 'int');
$dbc->defineField( 'grace_workstations', 'int');
$dbc->defineField( 'grace_expires', 'text');

$dbc->check();
