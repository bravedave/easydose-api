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
					// sys::logger( sprintf('%s : %s.%s : %s', $action, $u, $p, __METHOD__));

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
		if ( $this->isPost()) {
			$this->_authorize();
		}
		else {
			$guid = $this->getParam( 'guid');
			$action = $this->getParam( 'action');
			if ( $guid && $action == 'logon') {
				$dao = new dao\guid;
				if ( $dto = $dao->getByGUID( $guid)) {
					if ( $u = $dao->getUserOf( $dto)) {
						\dvc\session::edit();
						\dvc\session::set('uid', $u->id);
						\dvc\session::close();

						Response::redirect();

					}
					else {
						parent::authorize();

					}

				}
				else {
					parent::authorize();

				}

			}
			else {
				parent::authorize();

			}

		}

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
			Response::redirect('account');
			// $this->render([
			// 	'title' => $this->title = sys::name(),
			// 	'content' => 'readme']);

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
		if ( $this->firstRun || currentUser::isProgrammer()) {
			$this->render([
				'title' => 'dbinfo',
				'primary' => 'db-info',
				'secondary' => 'main-index']);

		}
		else {
			$this->_index();

		}

	}

	public function dbreset() {
		if ( currentUser::isProgrammer()) {
			$this->render([
				'title' => 'dbReset',
				'primary' => 'db-reset',
				'secondary' => 'main-index']);

		}
		else {
			$this->_index();

		}

	}

	public function dbdownload() {
		if ( config::$DB_TYPE == 'sqlite' ) {
			if ( currentUser::isProgrammer()) {
				$zipfile = $this->db->zip();
				if ( file_exists( $zipfile)) {
					sys::serve( $zipfile);

				}

			} else { $this->_index(); }

		} else { $this->_index(); }

	}

	public function dbMigrateSQLite2MySQL() {
		if ( currentUser::isProgrammer()) {

			/*
			* This is written to access SQLite3
			*
			* I'm not so sure of the wisdom of this - I have
			* had one 'Database is Busy' error
			*
			* roughly this could be used to migrate to MySQL
			* the escape sequencing would need fixing
			*
			*/
			print 'disabled';
			return;

			// $sq = new SQLite3( 'sqlite3.db' );
			Response::text_headers();

			// $mysqli = new mysqli( "localhost", "my_user", "my_password", "world");
			$sqlitedb = $this->db;

			$tables = $sqlitedb->Q( 'SELECT name FROM sqlite_master WHERE type="table"' );
			while ( $table = $tables->fetchArray() ) {
				$table = current( $table );

				$result = $sqlitedb->Q( sprintf( 'SELECT * FROM %s', $table ) );

				if ( strpos( $table, 'sqlite' ) !== false ) {
					continue;

				}

				printf( "-- %s\n", $table );

				while ( $row = $result->fetchArray( SQLITE3_ASSOC)) {
					$values = array_map( function( $value ) {

						// return sprintf( "'%s'", mysqli_real_escape_string( $value));
						return sprintf( "'%s'", addslashes( $value));

					}, array_values( $row));

					printf( "INSERT INTO `%s` VALUES( %s );\n", $table, implode( ', ', $values ) );

				}

			}

		}
		else {
			$this->_index();

		}

	}

	public function test() {
		if ( currentUser::isProgrammer()) {
			$this->render([
				'title' => 'test',
				'primary' => 'test',
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

	public function primo() {
		if ( !$this->authorised)
			return;

		$debug = FALSE;
		// $debug = TRUE;

		Response::javascript_headers();

		ob_start();

		$jsFiles = sprintf( '%s/app/js/primo/*.js', $this->rootPath );
		$gi = new GlobIterator( $jsFiles, FilesystemIterator::KEY_AS_FILENAME);

		//~ $n = 0;
		foreach ($gi as $key => $item) {
			//~ sys::logger( sprintf( "[%s] %s", $key, $item->getRealPath()));
			include_once $item->getRealPath();
			print PHP_EOL;

		}

		$out = ob_get_contents();
		ob_end_clean();

		if ( $debug || $this->Request->ClientIsLocal()) {
			if ( $debug) \sys::logger( sprintf( ' not minifying primo :: %s', $this->timer->elapsed()));
			print $out;

		}
		else {
			if ( $debug) \sys::logger( sprintf( 'primo :: %s', $this->timer->elapsed()));

			$minifier = new MatthiasMullie\Minify\JS;
			$minifier->add( $out);
			$minified =  $minifier->minify();

			if ( $debug) \sys::logger( sprintf( 'primo :: minified :: %s', $this->timer->elapsed()));

			print $minified;

			if ( $debug) \sys::logger( sprintf( 'primo :: %s', $this->timer->elapsed()));

		}

	}

}
