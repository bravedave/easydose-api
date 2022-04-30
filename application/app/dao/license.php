<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

use dvc\dao\_dao;

class license extends _dao {
  public function getLicense($user = 0) {
    $debug = false;
    // $debug = true;

    if ($debug) \sys::logger(sprintf('dao\license->getLicense(%s) :: getting license', $user));

    $dao = new agreements;
    if ($ret = $dao->getActiveAgreementForUser($user)) {
      if ($ret->license) {
        if ($debug) \sys::logger(sprintf('dao\license->getLicense(%s) :: found agreement', $user));
        return ($ret);
      }
    }

    $dao = new invoices;
    if ($ret = $dao->getActiveLicenseForUser($user)) {
      if ($ret->license) {
        // \sys::dump( $ret);
        if ($debug) \sys::logger(sprintf('dao\license->getLicense(%s) :: getting Invoiced license - found', $user));
        return ($ret);
      }
    }


    $dao = new guid;
    if ($dtoSet = $dao->getForUser($user)) {
      if (count($dtoSet)) {
        if ($debug) \sys::logger(sprintf('%s : got Gratis license :: %s', $user, __METHOD__));
        if ($ret = $dao->getGratisLicenseOf($dtoSet[0])) {
          //~ \sys::dump( $ret);
          return ($ret);
        }
      }
    }

    return (new dto\license);
  }
}
