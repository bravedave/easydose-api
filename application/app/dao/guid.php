<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class guid extends _dao {
	protected $_db_name = 'guid';

	public function addGUID( $guid) {
		if ( strlen( trim( $guid)) == 38) {
			// \sys::logger( strlen( trim( $guid)));

			$a = [
				'guid' => $guid,
				'created' => \db::dbTimeStamp(),
				'updated' => \db::dbTimeStamp()];

			$id = $this->Insert( $a);
			\sys::logger( sprintf( 'dao\guid->addGUID :: adding %s (%s)', $guid, $id));

			return ( $id);

		}

		return ( FALSE);

	}

	public function getByGUID( $guid) {
		if ( $res = $this->Result( sprintf( 'SELECT * FROM %s WHERE guid = "%s"', $this->db_name(), $guid ))) {
			if ( $dto = $res->dto())
				return $dto;

		}

		if ( $id = $this->addGUID( $guid))
			return ( $this->getByID( $id));

		return ( FALSE);

	}

	public function getAll( $fields = 'guid.*, u.name', $order = '' ) {
		if ( is_null( $this->_db_name))
			throw new Exceptions\DBNameIsNull;

		$this->db->log = $this->log;
		$sql = sprintf( 'SELECT %s FROM guid LEFT JOIN users u on user_id = u.id %s', $fields, $order);

		return ( $this->Result( $sql));

	}


}
