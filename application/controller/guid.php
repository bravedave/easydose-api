<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

  security: admin only

	*/

class guid extends Controller {

  protected function postHandler() {
    $action = $this->getPost('action');

    if ( 'apply license override' == $action || 'remove license override' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\guid;
        if ('remove license override' == $action) {
          $a = [
            'grace_product' => '',
            'grace_workstations' => 0,
            'grace_expires' => ''
          ];

        }
        else {
          $a = [
            'grace_product' => $this->getPost('grace_product'),
            'grace_workstations' => $this->getPost('grace_workstations'),
            'grace_expires' => $this->getPost('grace_expires')
          ];

        }
        $dao->UpdateByID( $a, $id);
        Response::redirect( url::toString('guid/view/' . $id), $action);

      }
      else {
        Response::redirect( url::toString('guid'), 'invalid id');

      }

    }
    elseif ( 'use-version-2-license' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\guid;
        $dao->UpdateByID( ['use_license' => (int)$this->getPost('value')], $id);
        \Json::ack( $action);

      }
      else {
        \Json::nak( $action);

      }

    }
    else {
      Response::redirect( url::toString('guid'));

    }

  }

  public function view( $id) {
    if ( currentUser::isAdmin()) {

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

      $this->title = 'pharmacy database';
      if ( $dto->user_id) {
        $usersDAO = new dao\users;
        if ($this->data->account = $usersDAO->getByID( $dto->user_id)) {
          $this->title = sprintf('guid: %s', $this->data->account->name);

        }

      }

      $sitesDAO = new dao\sites;
      $this->data->sites = $sitesDAO->getForGUID( $dto->guid);

      $this->render([
        'title' => $this->title,
        'primary' => 'view',
        'secondary' => 'main-index']);

    }
    else {
      response::Redirect();

    }

  }

  protected function _index() {
    if ( currentUser::isAdmin()) {
      $guidDAO = new dao\guid;
      $this->data = (object)[ 'res' => $guidDAO->getAll() ];

      $this->render([
        'title' => $this->title = 'pharmacy databases',
        'primary' => 'list',
        'secondary' => 'main-index']);

    }
    else {
      response::Redirect();

    }

  }

  public function index() {
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

      Response::Redirect( url::tostring( 'guid/'), 'removed pharmacy database');

    }
    else {
      Response::Redirect();

    }

  }

}
