<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/


namespace dvc\mail;

abstract class config {
  static $ENABLED = '';
  static $MODE = '';

  static function config() {
    return \config::dataPath() . DIRECTORY_SEPARATOR . 'mail-config.json';
  }

  static function init() {
    if (!class_exists('dvc\ews\config')) {
      self::$MODE = 'imap';
    }

    if (file_exists($config = self::config())) {
      $j = json_decode(file_get_contents($config));

      if (isset($j->mode)) self::$MODE = $j->mode;
    }

    self::$ENABLED = ((bool)self::$MODE);
  }
}

config::init();
