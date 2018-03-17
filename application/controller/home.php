<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	security: ordinary user

	*/
class home extends Controller {
	protected $firstRun = FALSE;

	protected function _authorize() {
		/*
		 * curl -X POST -H "Accept: application/json" -d action="-system-logon-" -d u="john" -d p="" "http://localhost/"
		 */
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
		elseif ( $action == '-send-password-') {
			/*
			 * send a link to reset the password
			 */
		 	\sys::logger('-send-password-link-');
		 	if ( $u = $this->getPost( 'u')) {
				$dao = new \dao\users;
				if ( $dto = $dao->getUserByEmail( $u)) {
					/*
					 * this will only work for email addresses
					 */
				 	if ( $dao->sendResetLink( $dto)) {
						\Json::ack( 'sent reset link')
							->add('message', 'sent link, check your email and your junk mail');
						// \sys::logger('-sent-password-link-');

					}	else { \Json::nak( $action); }

				}	else { \Json::nak( $action); }

			}	else { \Json::nak( $action); }
			exit;

		}
		else {
			throw new dvc\Exceptions\InvalidPostAction;

		}

	}

	protected function authorize() {
		if ( $this->isPost())
			$this->_authorize();
		else
			parent::authorize();

	}

	protected function postHandler() {
		$action = $this->getPost('action');

	}

	function __construct( $rootPath) {
		$this->firstRun = sys::firstRun();

		if ( $this->firstRun)
			$this->RequireValidation = FALSE;
		else
			$this->RequireValidation = \sys::lockdown();

		parent::__construct( $rootPath);

	}

	protected function _index() {

		if ( currentUser::isAdmin()) {
			$this->render([
				'title' => $this->title = sys::name(),
				'primary' => 'readme',
				'secondary' => 'main-index']);

		}
		else {
			$this->render([
				'title' => $this->title = sys::name(),
				'content' => 'readme']);

		}

	}

	public function index( $data = '' ) {
		if ( $this->isPost()) {
			$this->postHandler();

		}
		elseif ( $this->firstRun) {
			$this->dbinfo();

		}
		else {
			$this->_index();

		}

	}

	public function dbinfo() {
		if ( $this->firstRun || currentUser::isAdmin()) {
			$this->render([
				'title' => 'dbinfo',
				'primary' => 'dbinfo',
				'secondary' => 'main-index']);

		}
		else {
			$this->_index();

		}

	}

	public function phpinfo() {
		if ( $this->firstRun || currentUser::isAdmin()) {
			phpinfo();

		}
		else {
			$this->_index();

		}

	}

	public function mailtest() {
		if ( $this->firstRun || currentUser::isAdmin()) {
			sys::mailtest();

		}
		else {
			$this->_index();

		}

	}

	public function edjs() {
				//~ 'debug' => TRUE,
			jslib::viewjs([
				'libName' => 'easydosejs',
				'leadKey' => '_ed_.js',
				'jsFiles' => sprintf( '%s/app/js/_ed_*.js', $this->rootPath ),
				'libFile' => config::tempdir()  . 'easydose.js'

			]);

	}

}
