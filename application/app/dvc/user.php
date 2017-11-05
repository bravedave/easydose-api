<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
NameSpace dvc;

class user extends _user {
	var $id = 0;
	protected $dto = FALSE;

	public function __construct() {
		if ( ( $id = (int)session::get('uid')) > 0 ) {
			$dao = new \dao\users;
			if ( $this->dto = $dao->getByID( $id)) {
				// this sets up what you expose about self (only to yourself)
				$this->id = $this->dto->id;
				$this->name = $this->dto->name;
				$this->username = $this->dto->username;
				$this->email = $this->dto->email;

			}

		}

	}

	public function valid() {
		/**
		 * if this function returns true you are logged in
		 */

		return ( $this->id > 0);

	}

}
