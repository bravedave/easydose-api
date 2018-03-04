<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/

class guid extends Controller {

  public function postHandler() {
    $action = $this->getPost('action');

    if ( 'apply license override' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $a = [
          'grace_product' => $this->getPost('grace_product'),
          'grace_workstations' => $this->getPost('grace_workstations'),
          'grace_expires' => $this->getPost('grace_expires')
        ];
        $dao = new dao\guid;
        $dao->UpdateByID( $a, $id);
        Response::redirect( url::toString('guid/' . $id), 'invalid id');

      }
      else {
        Response::redirect( url::toString('guid'), 'invalid id');

      }

    }
    else {
      Response::redirect( url::toString('guid'));

    }

  }

  public function view( $id) {
    if ( !currentUser::isAdmin())
      response::Redirect();

    if ( (int)$id < 1)
      response::Redirect( url::tostring( 'guid'));

    $guidDAO = new dao\guid;
    if ( !$dto = $guidDAO->getByID( $id))
      response::Redirect( url::tostring( 'guid'), 'not found');

    $this->data = (object)[
      'dto' => $dto,
      'account' => FALSE,
      'sites' => FALSE,
      'license' => $guidDAO->getLicenseOf( $dto)];

    if ( $dto->user_id) {
      $usersDAO = new dao\users;
      $this->data->account = $usersDAO->getByID( $dto->user_id);

    }

    $sitesDAO = new dao\sites;
    $this->data->sites = $sitesDAO->getForGUID( $dto->guid);

    $p = new page( $this->title = 'guid');
		$p
			->header()
			->title()
			->primary();

		$this->load( 'view');

		$p->secondary();
			$this->load('main-index');

  }

  protected function _index() {
    if ( !currentUser::isAdmin())
      response::Redirect();

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
    if ( $this->isPost()) {
      $this->postHandler();

    }
    else {
      $this->_index();

    }

  }

  public function remove( $id = 0) {
    if ( currentUser::isAdmin()) {
      if ( (int) $id) {
        $dao = new dao\guid;
        $dao->delete( $id);

      }

      Response::Redirect( url::tostring( 'guid/'), 'removed guid');

    }
    else {
      Response::Redirect();

    }

  }

}
