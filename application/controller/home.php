<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
class home extends Controller {
	protected $firstRun = FALSE;

	protected function _authorize() {
		$action = $this->getPost( 'action');
		if ( $action == '-system-logon-') {
			if ( $u = $this->getPost( 'u')) {
				if ( $p = $this->getPost( 'p')) {
					$dao = new \dao\users;
					if ( $dto = $dao->validate( $u, $p))
						\Json::ack( $action);
					else
						\Json::nak( $action);
					die;

				}

			}

		}
		throw new dvc\Exceptions\InvalidPostAction;

	}

	protected function authorize() {
		if ( $this->isPost())
			$this->_authorize();
		else
			parent::authorize();

	}

	protected function postHandler() {}

	function __construct( $rootPath) {
		$this->firstRun = sys::firstRun();

		if ( $this->firstRun)
			$this->RequireValidation = FALSE;
		else
			$this->RequireValidation = \sys::lockdown();

		parent::__construct( $rootPath);

	}

	public function index( $data = '' ) {
		if ( $this->isPost()) {
			$this->postHandler();

		}
		elseif ( $this->firstRun) {
			$this->dbinfo();

		}
		else {
			$p = new page( $this->title = sys::name());
			$p
				->header()
				->title()
				->primary();

				$this->load( 'readme');

			$p->secondary();

				$this->load('main-index');

		}

	}

	public function dbinfo() {
		$p = new dvc\pages\bootstrap('dbinfo');
			$p
			->header()
			->title()
			->primary();

			$dbinfo = new dao\dbinfo;
			$dbinfo->dump();

		$p->secondary();
			$this->load('main-index');

	}

}
