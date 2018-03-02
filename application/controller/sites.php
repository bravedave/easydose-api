<?php
/*
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

  function view( $id = 0) {
    if ( currentUser::isAdmin()) {
      if ( (int)$id) {
        $dao = new dao\sites;
        if ( $site = $dao->getByID( $id)) {
          $this->data = (object)[ 'site' => $site ];
          $p = new page( $this->title = 'Site');
      			$p
      				->header()
      				->title();

      			$p->primary();
      				$this->load('view');

      			$p->secondary();
      				//~ $this->load('index');
      				$this->load('main-index');


        }
        else {
          Respose::redirect( url::toString( 'sites', 'not found'));

        }

      }
      else {
        $this->index();

      }

    }

  }

  protected function _index() {
    if ( currentUser::isAdmin()) {
      $dao = new dao\sites;
      $this->data = (object)[
        'sites' => $dao->getAll()

      ];

      $p = new page( $this->title = 'Sites');
        $p->meta[] = sprintf( '<meta http-equiv="refresh" content="60; url=%s" />', url::tostring('sites'));
  			$p
  				->header()
  				->title();

  			$p->primary();
  				$this->load('list');

  			$p->secondary();
  				//~ $this->load('index');
  				$this->load('main-index');

    }

  }

  public function index() {
    if ( currentUser::isAdmin()) {
      $this->_index();

    }
    else {
      Response::redirect( url::tostring());

    }

  }

  public function remove( $id = 0, $guid = 0) {
    if ( currentUser::isAdmin()) {
      if ( (int) $id) {
        $dao = new dao\sites;
        $dao->delete( $id);

      }

      if ( (int)$guid) {
        Response::Redirect( url::tostring( 'guid/view/' . $guid), 'removed site');

      }
      else {
        Response::Redirect( url::tostring(), 'removed site');

      }

    }
    else {
      Response::Redirect();

    }

  }

}
