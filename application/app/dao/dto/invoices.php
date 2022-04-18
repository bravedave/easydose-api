<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao\dto;

use dvc\dao\dto\_dto;

class invoices extends _dto {
  var
    $lines = [],
    $total = 0,
    $tax = 0,
    $created = '',
    $expires = '';

}
