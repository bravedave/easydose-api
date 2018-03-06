<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

  security: admin only

	*/

class invoices extends Controller {

  protected function postHandler() {
    $action = $this->getPost('action');

    if ( 'aa' == $action) {
    }
    else {
      Response::redirect( url::tostring());

    }

  }

  protected function _index() {
    if ( currentUser::isAdmin()) {
      $dao = new dao\invoices;
      $this->data = (object)[ 'invoices' => $dao->getAll() ];

      $p = new page( $this->title = 'invoices');
      $p
        ->header()
        ->title()
        ->primary();

        $this->load( 'list');

      $p->secondary();
        $this->load('main-index');

    }
    else { throw new \exceptions\AccessViolation; }

  }

  public function index() {
    if ( $this->isPost()) {
      $this->postHandler();

    }
    else {
      $this->_index();

    }

  }

}
