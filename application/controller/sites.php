<?php
/**
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		Controller for accessing the sites

	security:
	 	admin

	*/
class sites extends Controller {

  public function _index() {
    $dao = new dao\sites;
    $this->data = (object)[
      'sites' => $dao->getAll()

    ];

    $p = new page( $this->title = 'Sites');
			$p
				->header()
				->title();

			$p->primary();
				$this->load('list');

			$p->secondary();
				//~ $this->load('index');
				$this->load('main-index');

  }

  public function index() {
    $this->_index();

  }

}
