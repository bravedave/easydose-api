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
				'name' => (string)$this->getPost('name'),
				'email' => (string)$this->getPost('email'),
				'admin' => (int)$this->getPost('admin'),
				'business_name' => (string)$this->getPost('business_name'),
				'street' => (string)$this->getPost('street'),
				'town' => (string)$this->getPost('town'),
				'state' => (string)$this->getPost('state'),
				'postcode' => (string)$this->getPost('postcode'),
				'abn' => (string)$this->getPost('abn')];

			if ( $pass = $this->getPost('pass')) {
				$a['pass'] = password_hash( $pass, PASSWORD_DEFAULT);

			}

			if ( $id) {
				$dao->UpdateByID( $a, $id);
				Response::redirect( 'users/view/' . $id, 'updated user');

			}
			else {
				/** ensure username is unique */
				$a['created'] = db::dbTimeStamp();
				$a['username'] = strtolower( $this->getPost('username'));

				if ( $dto = $dao->getUserByUserName( $a['username'])) {
					Response::redirect( 'users/view/' . $dto->id, 'user already exists');

				}
				else {
					$id = $dao->Insert( $a);
					Response::redirect( 'users/view/' . $id, 'added user');

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

			$this->render([
				'title' => $this->title = 'Users',
				'primary' => 'report',
				'secondary' => 'index']);

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

	protected function _edit( $id = 0, $readonly = FALSE) {
		if ( currentUser::isAdmin()) {
			$this->data = (object)[
				'dto' => (object)[
					'id' => 0,
					'username' => '',
					'name' => '',
					'email' => '',
					'business_name' => '',
					'street' => '',
					'town' => '',
					'state' => '',
					'postcode' => '',
					'abn' => '',
					'admin' => 0]];

					// $dbc->defineField( 'pass', 'text');
					// $dbc->defineField( 'admin', 'int');
					// $dbc->defineField( 'reset_guid', 'text');
					// $dbc->defineField( 'reset_guid_date', 'text');
					// $dbc->defineField( 'created', 'text');
					// $dbc->defineField( 'updated', 'text');

			if ( $id = (int)$id) {
				$dao = new dao\users;
				if ( $dto = $dao->getByID( $id)) {
					$this->data = (object)['dto' => $dto];

				}
				else { throw new \Exceptions\InvalidAccount; }

			}

			$this->data->readonly = $readonly;
			if ( $readonly) {
				$dao = new dao\license;
				$this->data->license = $dao->getLicense( $id);
				$dao = new dao\invoices;
				$this->data->invoices = $dao->getForUser( $id);
				$dao = new dao\guid;
				$this->data->guid = $dao->getForUser( $id);

			}

			$this->render([
				'title' => $this->title = 'User',
				'primary' => 'edit',
				'secondary' => 'index']);

		}

	}

	public function view( $id = 0) {
		if ( currentUser::isAdmin()) {
			$this->_edit( $id, $readonly = TRUE);

		}

	}

	public function edit( $id = 0) {
		if ( currentUser::isAdmin()) {
			$this->_edit( $id, $readonly = FALSE);

		}

	}

	public function createinvoice( $id = 0) {
		if ( currentUser::isAdmin()) {

			if ( $id) {

				$dao = new dao\users;
				if ( $dto = $dao->getByID( $id)) {

					$daoProducts = new dao\products;
					$settings = new dao\settings;

					$this->data = (object)[
						'account' => $dto,
						'products' => $daoProducts->getDtoSet(),
						'productsWKS' => $daoProducts->getDtoSet( $type = "WKS"),
						'sys' => $settings->getFirst(),
						'personal' => '0'];

					$this->render([
						'title' => $this->title = 'Create Invoice',
						'primary' => 'account/invoice-create',
						'secondary' => 'index']);

				}
				else { throw new \Exceptions\InvalidAccount; }

			}
			else { throw new \Exceptions\InvalidAccount; }

		}

	}

}
