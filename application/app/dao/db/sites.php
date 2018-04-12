<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
Namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'sites' );
$dbc->defineField( 'site', 'text');
$dbc->defineField( 'state', 'text');
$dbc->defineField( 'tel', 'text');
$dbc->defineField( 'ip', 'text');
$dbc->defineField( 'workstation', 'text');
$dbc->defineField( 'productid', 'text');
$dbc->defineField( 'productid_report', 'text');
$dbc->defineField( 'patients', 'text');
$dbc->defineField( 'patientsActive', 'text');
$dbc->defineField( 'os', 'text');
$dbc->defineField( 'deployment', 'text');
$dbc->defineField( 'version', 'text');
$dbc->defineField( 'activated', 'int');
$dbc->defineField( 'expires', 'text');
$dbc->defineField( 'expires_report', 'text');
$dbc->defineField( 'guid', 'text');
$dbc->defineField( 'abn', 'text');
$dbc->defineField( 'email', 'text');
$dbc->defineField( 'updated', 'text');

$dbc->check();
