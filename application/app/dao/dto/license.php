<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
Namespace dao\dto;

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
