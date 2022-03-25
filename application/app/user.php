<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

use dvc\session;

class user extends dvc\user {
  protected $_options = null;
  public $id = 0;
  public $admin = false;
  public $programmer = false;

  protected $dto = false;

  protected function _optionsConfig() {
    return \config::dataPath() . '/options.json';
  }

  protected function _optionsLoad() {
    $this->_options = (object)[];   // default

    $path = $this->_optionsConfig();
    if (file_exists($path)) {
      $this->_options = json_decode(file_get_contents($path));
    }
  }

  protected function _optionsSave() {
    // sys::logger( sprintf('options save : %s', __METHOD__));
    $path = $this->_optionsConfig();
    if (file_exists($path)) {
      unlink($path);
    }
    file_put_contents($path, json_encode($this->_options, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  }

  public function __construct() {
    if (($id = (int)session::get('uid')) > 0) {
      $dao = new \dao\users;
      if ($this->dto = $dao->getByID($id)) {
        // this sets up what you expose about self (only to yourself)
        $this->id = $this->dto->id;
        $this->name = $this->dto->name;
        $this->username = $this->dto->username;
        $this->email = $this->dto->email;
        $this->admin = $this->dto->admin;
        $this->programmer = (isset($this->dto->programmer) ? $this->dto->programmer : $this->admin);
      }
    }
  }

  function option($key, $value = null) {
    if (!$this->_options) $this->_optionsLoad();

    $ret = isset($this->_options->{$key}) ? $this->_options->{$key} : '';

    if ((string)$value) {
      $this->_options->{$key} = $value;
      $this->_optionsSave();
    }

    return $ret;
  }

  public function valid() {
    /**
     * if this function returns true you are logged in
     */

    return ($this->id > 0);
  }
}
