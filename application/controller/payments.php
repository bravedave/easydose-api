<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

  security: admin only

	*/
class payments extends Controller {
  protected function posthandler() {
    $action = $this->getPost('action');

    // if ( 'save' == $action ) {}

  }

  protected function _index() {
    if ( currentUser::isAdmin()) {
      $dao = new dao\payments;
      $this->data = (object)[
          'res' => $dao->getAll()
      ];

      $this->render([
        'title' => $this->title = 'payments',
        'primary' => 'payments',
        'secondary' => 'main-index']);

     }

	}

  public function view( $id) {
    if ( currentUser::isAdmin()) {
      $dao = new dao\payments;
      $this->data = (object)[
          'dto' => $dao->getByID( $id)
      ];

      $this->render([
        'title' => $this->title = 'payments',
        'primary' => 'view',
        'secondary' => 'main-index']);

     }

	}

  public function delete( $id) {
    if ( currentUser::isAdmin()) {
      $dao = new dao\payments;
      if ( $dto = $dao->getByID( $id)) {
          if ( $dto->state != 'approved') {
            $dao->delete( $dto->id);
            Response::redirect( url::tostring('payments'), 'deleted payment');

          }
          else { throw new \Exception( 'cannot delete approved payment'); }

      }
      else { Response::redirect( url::tostring('payments'), 'payment not found'); }

    }
    else { throw new \Exceptions\AccessViolation; }

	}

  public function index() {
    if ( currentUser::isAdmin()) {
      $this->_index();

    }

  }

}
