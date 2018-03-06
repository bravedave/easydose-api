<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	security: admin only

	*/
class users extends Controller {
	protected function postHandler() {
		$action = $this->getPost('action');
		//~ sys::dump( $this->getPost());

		if ( $action == 'save/update') {
			$dao = new dao\users;
			$id = (int)$this->getPost('id');

			$a = [
				'updated' => db::dbTimeStamp(),
				'name' => $this->getPost('name'),
				'email' => $this->getPost('email'),
				'admin' => (int)$this->getPost('admin')];

			if ( $pass = $this->getPost('pass')) {
				$a['pass'] = password_hash( $pass, PASSWORD_DEFAULT);

			}

			if ( $id) {
				$dao->UpdateByID( $a, $id);
				Response::redirect( 'users', 'updated user');

			}
			else {
				/** ensure username is unique */
				$a['created'] = db::dbTimeStamp();
				$a['username'] = strtolower( $this->getPost('username'));

				if ( $dto = $dao->getUserByUserName( $a['username'])) {
					Response::redirect( 'users', 'user already exists');

				}
				else {
					$dao->Insert( $a);
					Response::redirect( 'users', 'added user');

				}

			}

		}
		elseif ( $action == 'delete') {

			if ( $id = (int)$this->getPost('id')) {
				$dao = new dao\users;
				$dao->delete( $id);
				\Json::ack( 'deleted user');

			}
			else {
				\Json::nak( 'invalid id');

			}

		}

	}

	function __construct( $rootPath) {
		$this->RequireValidation = \sys::lockdown();
		parent::__construct( $rootPath);

	}

	protected function _index() {
		if ( currentUser::isAdmin()) {
			$dao = new dao\users;
			$this->data = $dao->getAll();
			//~ sys::dump( $this->data);

			$p = new page( $this->title = 'Users');
				$p
					->header()
					->title();

				$p->primary();
					$this->load('report');

				$p->secondary();
					$this->load('index');

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

	protected function _edit( $id = 0, $view = 'view') {
		if ( currentUser::isAdmin()) {
			$this->data = (object)[
				'dto' => (object)[
				'id' => 0,
				'username' => '',
				'name' => '',
				'email' => '',
				'admin' => 0]];

			if ( $id) {
				$dao = new dao\users;
				if ( $dto = $dao->getByID( $id)) {
					$this->data = (object)['dto' => $dto];

				}
				else { throw new \Exceptions\InvalidAccount; }

			}

			$p = new page( $this->title = 'User');
			$p
				->header()
				->title();

			$p->primary();$this->load( $view);

			$p->secondary();$this->load('index');

		}

	}

	public function view( $id = 0) {
		if ( currentUser::isAdmin()) {
			$this->_edit( $id, 'view');

		}

	}

	public function edit( $id = 0) {
		if ( currentUser::isAdmin()) {
			$this->_edit( $id, 'edit');

		}

	}

	public function createinvoice( $id = 0) {
		if ( currentUser::isAdmin()) {
			$this->data = (object)[
				'dto' => FALSE];

			if ( $id) {
				$dao = new dao\users;
				if ( $dto = $dao->getByID( $id)) {
					$this->data = (object)['dto' => $dto];

				}
				else { throw new \Exceptions\InvalidAccount; }

				$p = new page( $this->title = 'Create Invoice');
				$p
					->header()
					->title();

				$p->primary();$this->load( 'blank');

				$p->secondary();$this->load('index');

			}
			else { throw new \Exceptions\InvalidAccount; }

		}

	}

}
