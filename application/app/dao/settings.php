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

class settings extends _dao {
  protected $_db_name = 'settings';
  static $dto = false;

  public function firstRun() {
    /**
     * test if the settings table exists
     * if it does - it is not first run
     */

    if ($res = $this->Result("SELECT name FROM sqlite_master WHERE type='table' AND name='settings';"))
      return (!$res->dto());

    return (true);
  }

  static private function _getFirst() {
    $dao = new self;
    return ($dao->getFirst());
  }

  public function getFirst() {
    if ($res = $this->Result("SELECT * FROM settings"))
      return ($res->dto());

    return (false);
  }

  public function getName() {
    if (self::$dto || self::$dto = $this->getFirst())
      return (self::$dto->name);

    return (\config::$WEBNAME);
  }

  static public function get($field) {
    if (self::$dto || self::$dto = self::_getFirst())
      return (self::$dto->{$field});

    return (null);
  }

  public function useSubscriptions() {
    if (self::$dto || self::$dto = $this->getFirst()) {
      if (isset(self::$dto->use_subscription))
        return ((bool)self::$dto->use_subscription);
    }

    return (false);
  }

  public function lockdown($set = null) {
    $lockdown = FALSE;
    if (self::$dto || self::$dto = $this->getFirst()) {
      $lockdown = (int)self::$dto->lockdown;

      if (!is_null($set))
        $this->Q(sprintf("UPDATE `settings` SET `lockdown` = %d", (int)$set));
    }

    //~ \sys::logger( sprintf( 'lockdown = %s', ( $lockdown ? 'TRUE' : 'FALSE' )));

    return ($lockdown);
  }

  public function paypalLive() {
    if (self::$dto || self::$dto = $this->getFirst()) {
      if (self::$dto->paypal_live) {
        return ('live');
      }
    }
    return ('sandbox');
  }

  public function paypalAuth() {
    if (self::$dto || self::$dto = $this->getFirst()) {
      $auth = new \PayPal\Auth\OAuthTokenCredential(
        self::$dto->paypal_ClientID,
        self::$dto->paypal_ClientSecret
      );

      return ($auth);
    }

    //~ \sys::logger( sprintf( 'lockdown = %s', ( $lockdown ? 'TRUE' : 'FALSE' )));

    return (FALSE);
  }
}
