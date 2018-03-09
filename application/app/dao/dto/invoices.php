<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
Namespace dao\dto;

class invoices extends _dto {
  var
    $lines = [],
    $total = 0,
    $tax = 0,
    $created = '',
    $expires = '';

}
