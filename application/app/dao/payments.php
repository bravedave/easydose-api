<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class payments extends _dao {
	protected $_db_name = 'payments';

	public function getAll( $fields = 'p.*, u.name user_name', $order = '') {

		$_sql = sprintf( 'SELECT %s
			FROM payments p
				LEFT JOIN
			 		users u ON u.id = p.user_id %s', $fields, $order);

		return ( $this->Result( $_sql));

	}

	public function getByID( $id) {
		$fields = 'p.*, u.name user_name';
		$_sql = sprintf( 'SELECT %s
			FROM payments p
				LEFT JOIN
					users u ON u.id = p.user_id
			WHERE p.id = %d', $fields, (int)$id);

			if ( $res = $this->Result( $_sql)) {
				return ( $res->dto());

			};

			return ( FALSE);

	}

	public function getByPaymentID( $PayID) {
		if ( $res = $this->Result( sprintf( 'SELECT * FROM payments WHERE `payment_id` = "%s"', $this->escape( $PayID))))
			return ( $res->dto());

		return ( FALSE);

	}

}
