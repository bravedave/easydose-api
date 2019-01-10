<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'products' );
$dbc->defineField( 'name', 'text');
$dbc->defineField( 'description', 'text');
$dbc->defineField( 'rate', 'float');
$dbc->defineField( 'term', 'text');
$dbc->defineField( 'created', 'text');
$dbc->defineField( 'updated', 'text');
$dbc->check();
