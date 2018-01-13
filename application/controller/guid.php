<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/

class guid extends Controller {

  protected function _index() {
    $guidDAO = new dao\guid;
    $this->data = (object)[
      'res' => $guidDAO->getAll()  ];

    $p = new page( $this->title = 'guid');
		$p
			->header()
			->title()
			->primary();

		$this->load( 'list');

		$p->secondary();
			$this->load('main-index');

  }

  function index() {
    $this->_index();

  }

}
