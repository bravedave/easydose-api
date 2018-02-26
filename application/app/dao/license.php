<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		data access worker for agreements table

	*/

Namespace dao;

class license extends _dao {
  public function getLicense( $user = 0) {
    $dao = new agreements;
    if ( $ret = $dao->getActiveAgreementForUser( $user)) {
      if ( $ret->license) {
        return ( $ret);

      }

    }

    $dao = new products;
    if ( $ret = $dao->getActiveProductForUser( $user)) {
      // \sys::dump( $ret);
      if ( $ret->license) {
        return ( $ret);

      }

    }

    return ( new dto\license);

  }

}
