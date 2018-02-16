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

  protected function _index() {
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

  public function remove( $id = 0, $guid = 0) {
    if ( currentUser::isAdmin()) {
      if ( (int) $id) {
        $dao = new dao\sites;
        $dao->delete( $id);

      }

      if ( (int)$guid) {
        Response::Redirect( url::tostring( 'guid/' . $guid), 'deleted site');

      }
      else {
        Response::Redirect( url::tostring(), 'deleted site');

      }

    }
    else {
      Response::Redirect();

    }

  }

}
