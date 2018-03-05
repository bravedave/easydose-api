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

		}

		return ( $dto);

	}

}
