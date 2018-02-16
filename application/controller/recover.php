<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		Controller for resetting account passwords

	security:
	 	anonymous

	*/
class recover extends Controller {
  public $RequireValidation = FALSE;

	protected function postHandler() {
    $action = $this->getPost('action');
    if ( $action == 'reset password') {
      if ( $key = $this->getParam('guid')) {

        $pwd = $this->getPost('password');
        if ( strlen($pwd) < 8) {
          Response::Redirect( url::tostring('recover/?k=' . urlencode($key)), 'Password too short');

        }
        elseif (!preg_match("#[0-9]+#", $pwd)) {
          Response::Redirect( url::tostring('recover/?k=' . urlencode($key)), 'Password must include at least one number');

        }
        elseif (!preg_match("#[a-zA-Z]+#", $pwd)) {
          Response::Redirect( url::tostring('recover/?k=' . urlencode($key)), 'Password must include at least one letter');

        }
        else {
          $a = [
            'pass' => password_hash( $pwd, PASSWORD_DEFAULT)
          ];

          $dao = new dao\users;
          if ( $dto = $dao->getByResetKey( $key)) {
            $dao->UpdateByID( $a, $dto->id);
            Response::Redirect( url::tostring(), 'reset password');

          }
          else {
            Response::Redirect( url::tostring(), 'password NOT reset.nf');

          }

        }

      }
      else {
        Response::Redirect( url::tostring(), 'password NOT reset.g');

      }

    }

  }

	protected function _index() {
    if ( $key = $this->getParam('k')) {
      $dao = new dao\users;
      if ( $dto = $dao->getByResetKey( $key)) {
        $this->data = (object)[
          'guid' => $key,
          'dto' => $dto
        ];

        $p = new page( $this->title = "Reset Password");
        $p->header()
				    ->title()
            ->content();

        $this->load('password-reset');


      }
      else {
        Response::redirect( url::tostring(), 'invalid recovery key');

      }

    }
    else {
      Response::redirect();

    }

  }

	function index() {
    if ( $this->isPost()) {
      $this->postHandler();

    }
    else {
      $this->_index();

    }

	}

}
