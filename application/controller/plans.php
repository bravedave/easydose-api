<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		Controller for accessing the user account

	security:
	 	Ordinary Authenticated user - non admin

	*/
class plans extends Controller {

  protected function _index() {
    $dao = new dao\plans;
    $this->data = (object)[
      'plans' => $dao->getAll(),
    ];

    $this->render([
      'title' => $this->title = 'Plans',
      'primary' => 'plans',
      'secondary' => 'main-index']);

  }

  public function index() {
    if ( currentUser::isAdmin()) {
      $this->_index();

    }

  }

}
