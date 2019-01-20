<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	DO NOT change this file
	Copy it to <application>/app/dao and modify it there
	*/
namespace dao;

class dbinfo extends _dbinfo {
	/*
	 * it is probably sufficient to copy this file into the <application>/app/dao folder
	 *
	 * from there store you structure files in <application>/dao/db folder
	 */
	protected function check() {
		parent::check();

		\sys::logger( 'checking ' . dirname( __FILE__ ) . '/db/*.php' );

		if ( glob( dirname( __FILE__ ) . '/db/*.php')) {
			foreach ( glob( dirname( __FILE__ ) . '/db/*.php') as $f ) {
				\sys::logger( 'checking => ' . $f );
				include_once $f;

			}

		}

	}

	public function reset() {
		$sql = [
			'DROP TABLE IF EXISTS _guid_old',
			'DROP TABLE IF EXISTS agreements',
			'DROP TABLE IF EXISTS invoices',
			'DROP TABLE IF EXISTS invoices_detail',
			'DROP TABLE IF EXISTS payments',
			'DROP TABLE IF EXISTS plans',
			'UPDATE guid SET user_id = 0',

			'ALTER TABLE users RENAME TO _users_old'

		];

		foreach ($sql as $_sql) {
			$this->db->Q( $_sql);

		}

		$dao = new \dao\users;
		$dao->check();

		$fields = 'username, name, email, business_name,
			street, town, state, postcode, abn, pass, admin,
			created, updated';

		$sql = [
			sprintf('INSERT INTO users(%s)
			 SELECT %s FROM _users_old
				 where username in ("davidb", "steve", "noel", "phil")',
				 $fields, $fields),

			'DROP TABLE _users_old'

		];

		foreach ($sql as $_sql) {
			// \sys::logger( $_sql);
			$this->db->Q( $_sql);

		}

		$this->check();

	}

}
