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

class license extends _dto {
  var
    $type = 'SUBSCRIPTION',
    $license = FALSE,
    $workstation = FALSE,
    $description = '',
    $product = '',
    $state = '',
    $workstations = 0,
    $expires = '1970-01-01';

}
