<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

  PRAGMA foreign_keys=off;

  BEGIN TRANSACTION;

  ALTER TABLE guid RENAME TO _guid_old;

  <create here>

  INSERT INTO guid ( id, guid, user_id, agreements_id, created, updated )
    SELECT id, guid, user_id, agreements_id, created, updated
    FROM _guid_old;

  COMMIT;

  PRAGMA foreign_keys=on;
	*/
Namespace dvc\sqlite;

$dbc = new dbCheck( $this->db, 'guid' );
$dbc->defineField( 'guid', 'text');
$dbc->defineField( 'user_id', 'int');
$dbc->defineField( 'agreements_id', 'int');
$dbc->defineField( 'created', 'text');
$dbc->defineField( 'updated', 'text');
$dbc->defineField( 'use_license', 'int');
$dbc->defineField( 'grace_product', 'text');
$dbc->defineField( 'grace_workstations', 'int');
$dbc->defineField( 'grace_expires', 'text');
$dbc->defineField( 'development', 'int');

$dbc->check();

//
// sqlite doesn't support altering fields
// this was used to change grace_product to a text field
//
// $this->db->Q('PRAGMA foreign_keys=off');
//
// $this->db->Q('BEGIN TRANSACTION');
//
// $this->db->Q('ALTER TABLE guid RENAME TO _guid_old');
//
// $dbc->check();
//
// $this->db->Q('INSERT INTO guid ( id, guid, user_id, agreements_id, created, updated )
//   SELECT id, guid, user_id, agreements_id, created, updated
//   FROM _guid_old');
//
// $this->db->Q('COMMIT');
//
// $this->db->Q('PRAGMA foreign_keys=on');
