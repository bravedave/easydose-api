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
    $debug = FALSE;
		// $debug = TRUE;

		if ( $debug) \sys::logger( sprintf( 'dao\license->getLicense(%s) :: getting license', $user));

    $dao = new agreements;
    if ( $ret = $dao->getActiveAgreementForUser( $user)) {
      if ( $ret->license) {
        if ( $debug) \sys::logger( sprintf( 'dao\license->getLicense(%s) :: found agreement', $user));
        return ( $ret);

      }

    }

    if ( $debug) \sys::logger( sprintf( 'dao\license->getLicense(%s) :: getting product license', $user));

    $dao = new products;
    if ( $ret = $dao->getActiveProductForUser( $user)) {
      // \sys::dump( $ret);
      if ( $ret->license) {
        return ( $ret);

      }

    }

    if ( $debug) \sys::logger( sprintf( 'dao\license->getLicense(%s) :: getting Invoiced license', $user));

    $dao = new invoices;
    if ( $ret = $dao->getActiveLicenseForUser( $user)) {
      // \sys::dump( $ret);
      if ( $ret->license) {
        return ( $ret);

      }

    }

    if ( $debug) \sys::logger( sprintf( 'dao\license->getLicense(%s) :: getting Gratis license', $user));

    $dao = new guid;
    if ( $dtoSet = $dao->getForUser( $user)) {
      if ( count( $dtoSet)) {
        if ( $ret = $dao->getGratisLicenseOf( $dtoSet[0])) {
          return ( $ret);

        }

      }

    }

    return ( new dto\license);

  }

}
