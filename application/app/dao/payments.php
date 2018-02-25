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

	public function getByPaymentID( $PayID) {
		if ( $res = $this->Result( sprintf( 'SELECT * FROM payments WHERE `payment_id` = "%s"', $this->escape( $PayID))))
			return ( $res->dto());

		return ( FALSE);

	}

}
