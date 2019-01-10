<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'easyLog' );
$dbc->defineField( 'comment', 'text');
$dbc->defineField( 'created', 'text');
$dbc->defineField( 'updated', 'text');
$dbc->defineField( 'updated_by', 'int');
$dbc->defineField( 'guid_id', 'int');
$dbc->defineField( 'site_id', 'int');
$dbc->defineField( 'user_id', 'int');

$dbc->check();
