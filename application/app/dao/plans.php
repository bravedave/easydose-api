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

class plans extends _dao {
  protected $_db_name = 'plans';

  public function getByPayPalID($id) {
    if ($res = $this->Result(sprintf('SELECT * FROM plans WHERE `paypal_id` = "%s"', $this->escape($id))))
      return ($res->dto());

    return (FALSE);
  }

  public function deleteByPayPalID($id) {
    $this->Q(sprintf('DELETE FROM plans WHERE `paypal_id` = "%s"', $this->escape($id)));
    return (TRUE);
  }

  public function getActivePlans($type = '') {
    if ('WKS' == $type) {
      if ($res = $this->Result('SELECT * FROM plans WHERE `name` like "WKS%" AND `state` = "ACTIVE" ORDER BY `name` ASC')) {
        return ($res->dtoSet());
      }
    } else {
      if ($res = $this->Result('SELECT * FROM plans WHERE `name` NOT like "WKS%" AND `state` = "ACTIVE" ORDER BY `name` ASC')) {
        return ($res->dtoSet());
      }
    }

    return (FALSE);
  }
}
