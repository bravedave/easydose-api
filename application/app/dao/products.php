<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class products extends _dao {
	protected $_db_name = 'products';

	public function getDtoSet( $type = '') {
		if ( 'WKS' == $type) {
			if ( $res = $this->Result( 'SELECT * FROM products WHERE `name` LIKE "WKS%" ORDER BY `name` ASC')) {
				return ( $res->dtoSet());

			}

		}
		else {
			if ( $res = $this->Result( 'SELECT * FROM products WHERE `name` NOT LIKE "WKS%" ORDER BY `name` ASC')) {
				return ( $res->dtoSet());

			}

		}

		return ( $dtoSet);

	}


}
