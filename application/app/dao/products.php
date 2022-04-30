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

class products extends _dao {
  protected $_db_name = 'products';

  public function getByName($name) {
    if ((string)$name) {
      if ($res = $this->Result(sprintf('SELECT * FROM products WHERE `name` = "%s"', $name))) {
        if ($dto = $res->dto()) {
          return ($dto);
        }
      }
    }

    return (FALSE);
  }

  public function getDtoSet($type = '') {
    if ('WKS' == $type) {
      if ($res = $this->Result('SELECT * FROM products WHERE `name` LIKE "WKS%" ORDER BY `name` ASC')) {
        return ($res->dtoSet());
      }
    } else {
      if ($res = $this->Result('SELECT * FROM products WHERE `name` NOT LIKE "WKS%" ORDER BY `name` ASC')) {
        return ($res->dtoSet());
      }
    }

    return [];
  }

  public function getActiveProductForUser($userID = 0) {
    $debug = false;
    // $debug = true;

    $ret = new dto\license;

    if (!(int)$userID) {
      $userID = \currentUser::id();
    }

    $_sql = 'SELECT
				pay.id,
				pay.payment_id,
				pay.state,
				pay.name,
				pay.description,
				pay.created,
				pay.updated,
				pay.user_id,
				products.term,
				products.name `product`,
				products.description `productDescription`
			FROM payments pay
				LEFT JOIN products on pay.product_id = products.id';

    $_where = [
      'pay.state = "approved"',
      sprintf('pay.created >= "%s"', date('Y-m-d', strtotime('-1 year'))),
      sprintf('pay.user_id = %d', $userID)
    ];

    $_w = $_where;
    $_w[] = 'pay.`name` NOT LIKE "WKS%"';
    $sql = sprintf('%s WHERE %s', $_sql, implode(' AND ', $_w));

    if ($debug) \sys::logSQL($sql);

    if ($res = $this->Result($sql)) {
      if ($ret->license = $res->dto()) {
        $ret->type = 'LICENSE';
        $ret->product = $ret->license->product;
        $ret->description = $ret->license->description;
        $ret->workstations = 1;
        $ret->expires = date('Y-m-d', strtotime('+1 year', strtotime($ret->license->created)));
        $ret->license->expires = $ret->expires;  // not happy with this ...
        $ret->state = 'active';
      }
    }

    $_w = $_where;
    $_w[] = 'pay.`name` LIKE "WKS%"';
    $sql = sprintf('%s WHERE %s', $_sql, implode(' AND ', $_w));
    // print $sql;
    if ($res = $this->Result($sql)) {
      if ($ret->workstation = $res->dto()) {
        if ('WKSSTATION1' == $ret->workstation->name) {
          $ret->workstations = 2;
        } elseif ('WKSSTATION2' == $ret->workstation->name) {
          $ret->workstations = 3;
        }
      }
    }

    if ($debug) \sys::dump($ret);
    return ($ret);
  }
}
