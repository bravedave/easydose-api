<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/
class api extends Controller {
	public $RequireValidation = false;

	protected function postHandler() {
		// $this->debug = TRUE;
		$debug = $this->debug;

		$action = $this->getPost('action');
		if ( $debug) \sys::logger( "api $action");

		if ( $action == 'guid') {
			/*
			*  curl -X POST -H "Accept: application/json" -d action=guid "https://my.easydose.net.au/api/"
			*/
			\Json::ack( $action)
				->add('data', strings::getGUID());

		}
		elseif ( $action == 'checkin') {
			$this->checkin( $action);

		}
		elseif ( $action == 'get-account') {
			$this->getAccount( $action);

		}
		elseif ( $action == 'set-account') {
			$this->setAccount( $action);

		}
		elseif ( $action == 'get-license') {
			$this->getLicense( $action);

		}

	}

	protected function getLicense( $action) {
		/*
		 *  curl -X POST -H "Accept: application/json" -d action=get-license -d guid="{D226CA40-CA53-94C2-2DC1-F86851D79F20}" "http://localhost/api/"
		 *  curl -X POST -H "Accept: application/json" -d action=get-license -d guid="{D226CA40-CA53-94C2-2DC1-F86851D79F20}" "https://my.easydose.net.au/api/"
		 */
		// if ( $guid = '{D226CA40-CA53-94C2-2DC1-F86851D79F20}') {
		if ( $guid = $this->getPost('guid')) {

			$guidDAO = new dao\guid;
			if ( $license = $guidDAO->getLicense( $guid)) { // will add guid if it doesn't exist
			\Json::ack( $action)
				->add( 'type', $license->type)
				->add( 'description', $license->description)
				->add( 'License', $license->product)
				->add( 'state', $license->state)
				->add( 'workstations', $license->workstations)
				->add( 'expires', $license->expires)
				->add( 'authoritive', $license->authoritive ? 'yes' : 'no')
				;

			} else { json::nak( $action); }

		} else { json::nak( $action); }

	}

	protected function checkin( $action) {
		$debug = false;
		$debug = true;

		$site = $this->getPost('site');
		if ( $site != '' ) {
			/*
			*  curl -X POST -H "Accept: application/json" -d action=checkin -d site="Davido the Demo" -d state=WA -d tel=0893494011 -d workstation=WISPER -d deployment=Build -d version="RC2.1.10.0.9" -d productid=EasydoseLegacy -d activated=yes -d expires="2018-01-14" -d patients=24 -d patientsActive=15 -d guid="{D226CA40-CA53-94C2-2DC1-F86851D79F20}" "http://localhost/api/"
			*  curl -X POST -H "Accept: application/json" -d action=checkin -d site="Davido the Demo" -d state=WA -d tel=0893494011 -d workstation=WISPER -d deployment=Build -d version="RC2.1.10.0.9" -d productid=EasydoseLegacy -d activated=yes -d expires="2018-01-14" -d patients=24 -d patientsActive=15 -d guid="{9D85652E-E7D8-BAED-7C89-72720005B87D}" "http://localhost/api/"
			*  curl -X POST -H "Accept: application/json" -d action=checkin -d site="Davido the Demo" -d state=WA -d tel=0893494011 -d workstation=WISPER -d deployment=Build -d version="RC2.1.10.0.9" -d productid=EasydoseLegacy -d activated=yes -d expires="2018-01-14" -d patients=24 -d patientsActive=15 -d guid="{9D85652E-E7D8-BAED-7C89-72720005B87D}"  "https://my.easydose.net.au/api/"
			*/

			$a = [
				'site' => $site,
				'state' => $this->getPost('state'),
				'tel' => $this->getPost('tel'),
				'ip' => $this->Request->getRemoteIP(),
				'workstation' => $this->getPost('workstation'),
				'productid' => $this->getPost('productid'),
				'productid_report' => $this->getPost('productid'),
				'patients' => $this->getPost('patients'),
				'patientsActive' => $this->getPost('patientsActive'),
				'os' => $this->getPost('OS'),
				'deployment' => $this->getPost('deployment'),
				'version' => $this->getPost('version'),
				'activated' => ( $this->getPost('activated') == 'yes' ? 1 : 0 ),
				'expires' => $this->getPost('expires'),
				'expires_report' => $this->getPost('expires'),
				'guid' => $this->getPost('guid'),
				'abn' => $this->getPost('abn'),
				'email' => $this->getPost('email'),
				'updated' => \db::dbTimeStamp()];

			if ( $a['deployment'] == 'Build' ) {
				sys::logger('api/getAccount - development checkin');
				\Json::ack( sprintf( '%s - developer', $action));

			}

			if ( $a['deployment'] != '' ) {
				$res = $this->dbResult( sprintf( "SELECT * FROM SITES WHERE site = '%s' AND workstation = '%s'",
					$this->db->escape( $a['site']),
					$this->db->escape( $a['workstation']))

				);

				if ( $res) {

					$guidDAO = new dao\guid;
					$sitesDAO = new dao\sites;
					$license = 'none';
					$status = 'inactive';
					$workstations = 0;
					$NextPaymentDue = date('Y-m-d', 0);
					$authoritive = false;


					if ( $licenseDTO = $guidDAO->getLicense( $a['guid'])) { // will add guid if it doesn't exist
						$a['productid'] = $licenseDTO->product;
						$a['expires'] = date( 'Y-m-d', strtotime($licenseDTO->expires));
						$authoritive = $licenseDTO->authoritive;

						// \sys::dump( $licenseDTO, NULL, FALSE);

						// ->add( 'type', $licenseDTO->type)
						// ->add( 'description', $licenseDTO->description)
						// ->add( 'state', $licenseDTO->state)
						// ->add( 'workstations', $licenseDTO->workstations)
						$license = $licenseDTO->product;
						$status = $licenseDTO->state;
						$workstations = $licenseDTO->workstations;
						$NextPaymentDue = date( 'Y-m-d', strtotime($licenseDTO->expires));

						$lex = new \DateTime($licenseDTO->expires);
						$tod = new \DateTime;
						$interval = $tod->diff($lex);

						if ( $interval->days > 0 && $interval->days < dao\settings::get('invoice_creation_days')) {

							if ( $uDto = $guidDAO->getUser( $a['guid'])) {

								$iDAO = new dao\invoices;
								if ( !( $dtoUP = $iDAO->getUnpaidForUser( $uDto->id))) {
									$uDAO = new dao\users;
									if ( $uDAO->autoCreateInvoiceFromLast( $uDto->id)) {
										\sys::logger( sprintf( 'auto created invoice ..(%s)', $interval->days));

									} else { \sys::logger( sprintf( 'could not create an invoice ..(%s)', $interval->days)); }

								}
								//~ else {
									//~ \sys::logger( sprintf( 'will NOT create a invoice, there is an unpaid one ..(%s)', $interval->days));

								//~ }

							}

						}

					}

					if ( $sitesDTO = $res->dto()) {
						$sitesDAO->UpdateByID( $a, $sitesDTO->id );
						if ( $debug) {
							// foreach ($a as $key => $value) {
							//   \sys::logger( sprintf( 'site: updated %s => %s', $key, $value ));
							//
							// }

							\sys::logger( sprintf( 'site: updated => %s, %s (%s)', $a['site'], $a['workstation'], $a['version'] ));

						}

						$j = \Json::ack( $action);

					}
					else {
						$sitesDAO->Insert( $a);
						if ( $debug) \sys::logger( sprintf( 'site: inserted => %s, %s', $a['site'], $a['workstation'] ));
						$j = \Json::ack( $action);

					}

					$j
						->add('License', $license)
						->add('Workstations', $workstations)
						->add('NextPaymentDue', $NextPaymentDue)
						->add('Subscription_Status', $status)
						->add('authoritive', $authoritive ? 'yes' : 'no')
						;

				} else { \Json::nak( $action); }

			}
			else {
				\sys::logger( sprintf( 'site: %s, %s, %s, %s @ (%s), %s (activated:%s - %s)',
					$a['site'],
					$a['workstation'],
					$a['version'],
					$a['deployment'],
					$a['ip'],
					$a['productid'],
					$a['activated'],
					$a['expires']));
				\Json::ack($action);

			}

		} else { \Json::nak($action); }

	}

	protected function getAccount( $action) {
		/*
		*  return the account information including license
		*
		*  curl -X POST -H "Accept: application/json" -d action="get-account" -d guid="{9D85652E-E7D8-BAED-7C89-72720005B87D}" "http://localhost/api/"
		*  curl -X POST -H "Accept: application/json" -d action="get-account" -d guid="{9D85652E-E7D8-BAED-7C89-72720005B87D}" -d deployment=Build "http://localhost/api/
		*
		*/
		if ( $guid = $this->getPost( 'guid')) {
			$guidDAO = new dao\guid;
			if ( $guidDTO = $guidDAO->getByGUID( $guid)) {
				$email = '';
				if ( (int)$guidDTO->user_id > 0) {
					$usersDAO = new dao\users;
					if ( $usersDTO = $usersDAO->getByID( $guidDTO->user_id))
						$email = $usersDTO->email;

				}

				$j = \Json::ack( $action)
					->add( 'guid', $guid)
					->add( 'email', $email);

				if ( $guidDTO = $guidDAO->getByGUID( $guid)) {
					$deployment = $this->getPost( 'deployment');
					if ( $deployment == 'Build') {
						sys::logger('api/getAccount - development license');
						$j
							->add( 'type', 'LICENSE')
							->add( 'License', 'easydoseOPEN')
							->add( 'description', 'Development License')
							->add( 'state', 'active')
							->add( 'workstations', 9)
							->add( 'expires', date('Y-m-d', strtotime('+1 month')));

					}
					else {
						if ( $license = $guidDAO->getLicenseOf( $guidDTO)) { // will add guid if it doesn't exist
							$j
								->add( 'type', $license->type)
								->add( 'description', $license->description)
								->add( 'License', $license->product)
								->add( 'state', $license->state)
								->add( 'workstations', $license->workstations)
								->add( 'expires', $license->expires);

						}

					}

				}

			} else { \Json::nak( sprintf( '%s :: new', $action)); }

		} else { \Json::nak( $action); }

	}

	protected function setAccount( $action) {
		/*
		*  associated an email address associated with a guid
		*  this can only be done if the email is blank
		*
		*  curl -X POST -H "Accept: application/json" -d action="set-account" -d guid="{9D85652E-E7D8-BAED-7C89-72720005B87D}" -d email="david@brayworth.com.au" "http://localhost/api/"
		*/
		if ( $guid = $this->getPost( 'guid')) {

			$guidDAO = new dao\guid;
			if ( $guidDTO = $guidDAO->getByGUID( $guid)) {

				if ( (int)$guidDTO->user_id < 1) {

					$email = $this->getPost( 'email');

					if ( strings::IsEmailAddress( $email)) {

						$usersDAO = new dao\users;
						if ( $usersDTO = $usersDAO->getUserByEmail( $email)) {
							/*
							* Only allow one guid per account
							*/
							$guidDTOs = $guidDAO->getForUser( $usersDTO->id);
							if ( count( $guidDTOs)) {
								/*
								* to test this using a guid that is already in the database
								* try to assign it using a duplicate email
								*
								* sql to get info:
								*   select g.id, g.guid, u.name, u.email from guid g left join users u on g.user_id = u.id;
								*
								* curl -X POST -H "Accept: application/json" -d action="set-account" -d guid="{a guid id}" -d email="john@citizen.com" "http://localhost/api/"
								*/
								Json::nak( sprintf( '%s : email already used', $action));

							}
							else {
								$guidDAO->UpdateByID([
									'user_id' => $usersDTO->id,
									'updated' => \db::dbTimeStamp()
								], $guidDTO->id);
								\Json::ack( sprintf( '%s :: found account', $action))
									->add( 'email', $email);

							}

						}
						else {
							$a = explode( '@', $email);
							$name = (string)$a[0];
							$a = [
								'username' => $email,
								'name' => $name,
								'email' => $email,
								'created' => \db::dbTimeStamp(),
								'updated' => \db::dbTimeStamp()

							];
							$id = $usersDAO->Insert( $a);
							$guidDAO->UpdateByID( ['user_id' => $id], $guidDTO->id);

							\Json::ack( sprintf( '%s :: added account', $action))
								->add( 'email', $email);

						}

					}

				} else { \Json::nak( sprintf( '%s : account already assigned', $action)); }

			} else { \Json::nak( $action); }

		} else { \Json::nak( $action); }

	}

	public function index() {
		$this->isPost() ?
			$this->postHandler() :
			print 'EasyDose API handler';

	}

}
