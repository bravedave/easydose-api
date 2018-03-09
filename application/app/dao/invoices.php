<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class invoices extends _dao {
	protected $_db_name = 'invoices';
	protected $template = '\dao\dto\invoices';

	public function getInvoice( \dao\dto\invoices $dto) {
		$dao = new invoices_detail;
		if ( $lines = $dao->getLines( $dto)) {
			$dto->lines = $lines;
			$tot = 0;
			foreach ( $lines as $line) {
				$tot += (float)$line->rate;

			}

			$dto->total = $tot;
			$dto->tax = $tot / \config::tax_rate_devisor;

			if ( !$dto->expires) {
				$created = strtotime( $dto->created);
				$dto->expires = date( 'Y-m-d', strtotime( '+1 year', $created));

			}

		}

		return ( $dto);

	}

	public function getAll( $fields = 'i.*, u.name user_name', $order = '') {

		$_sql = sprintf( 'SELECT %s
			FROM invoices i
				LEFT JOIN
			 		users u ON u.id = i.user_id %s', $fields, $order);

		return ( $this->Result( $_sql));

	}

	public function getForUser( $userID = 0) {
		if ( !(int)$userID) {
			$userID = \currentUser::id();

		}

		$sql = sprintf( 'SELECT * FROM invoices WHERE user_id = %s', $userID);

		if ( $res = $this->Result( $sql)) {
			return ( $res->dtoSet());

		}

		return ( FALSE);

	}

}
