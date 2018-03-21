<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class sites extends _dao {
	protected $_db_name = 'sites';

	public function getAll( $fields = '*', $order = '' ) {
		if ( is_null( $this->_db_name))
			throw new Exceptions\DBNameIsNull;

		$this->db->log = $this->log;
		return ( $this->Result( sprintf( 'SELECT %s FROM %s WHERE deployment <> "Build" %s', $fields, $this->db_name(), $order )));

	}

	public function getAllIncludeUserID() {
		return ( $this->Result(
			'SELECT sites.*, guid.user_id guid_user_id
			FROM sites LEFT JOIN guid on guid.guid = sites.guid
			WHERE sites.deployment <> "Build" ORDER BY updated DESC'));

	}

	public function getForGUID( $guid) {
		return ( $res = $this->Result( sprintf( 'SELECT * FROM %s WHERE guid = "%s"', $this->db_name(), $guid )));

	}

}
