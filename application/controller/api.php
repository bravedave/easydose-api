<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
class api extends Controller {
  public $RequireValidation = FALSE;

  protected function postHandler() {
		$action = $this->getPost('action');
    if ( $action = 'guid') {
      /*
       *  curl -X POST --header "Accept: application/json" --data '{"action":"guid"}' "https://my.easydose.com.au/api/"
       */
      \Json::ack( $action)
        ->add('data', strings::getGUID());

    }

	}

  public function index() {
    if ( $this->isPost())
      $this->postHandler();

  }

}
