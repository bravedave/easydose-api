<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

use dvc\jwt\jwt;

class api extends Controller {
	public $RequireValidation = false;

	protected function postHandler() {
		$this->debug = true;
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
		elseif ( 'checkin' == $action) {
			$this->checkin( $action);

		}
		elseif ( 'get-account' == $action) {
			$this->getAccount( $action);

		}
		elseif ( 'get-license' == $action) {
			$this->getLicense( $action);

		}
		elseif ( 'set-account' == $action) {
			$this->setAccount( $action);

		}
    elseif ( 'token' == $action || isset( $_SERVER['HTTP_AUTHORIZATION'])) {
			if ( 'token' == $action) {
				//  curl -X POST -H "Accept: application/json" -d action="token" -d guid="{a guid}" "http://localhost/api/"
				if ($guid = $this->getPost( 'guid')) {
					$guidDAO = new dao\guid;
					if ( $guidDTO = $guidDAO->getByGUID( $guid)) {
						if ( (int)$guidDTO->user_id > 0) {
							$usersDAO = new dao\users;
							if ( $usersDTO = $usersDAO->getByID( $guidDTO->user_id)) {

								if ( strings::isEmail( $usersDTO->email)) {
									$jwt = jwt::token([
										'audience_claim' => $this->getPost('grant_type') || 'client_credentials',
										'data' => [
											'id' => $usersDTO->id,
											'name' => $usersDTO->name,
											'email' => $usersDTO->email

										]

									]);

									$expires = jwt::expires( $jwt);
									Json::ack( $action)
										->add( 'jwt', $jwt);

								}

							} else { Json::nak( $action); }

						} else { Json::nak( $action); }
						// } else { Json::nak( sprintf( '%s : no associated user', $action)); }

					} else { Json::nak( $action); }
					// } else { Json::nak( sprintf( '%s : guid not found', $action)); }

				} else { Json::nak( $action); }
				// } else { Json::nak( sprintf( '%s : missing guid', $action)); }

			}
			elseif ( isset( $_SERVER['HTTP_AUTHORIZATION'])) {
				\sys::logger( sprintf('<%s> %s', 'HTTP_AUTHORIZATION', __METHOD__));

				/*
				* in javascript it would be

				( _ => {
					_.post({
						url : _.url('api'),
						data : { action : 'token', guid : '<a guid>' },

					}).then( d => {
						if ( 'ack' == d.response) {
							_.post({
								url : _.url('api'),
								data : { action : '-get-direct-logon-' },
								headers : { 'authorization' : 'Bearer ' + d.jwt }

							}).then( d => {
								if ( 'ack' == d.response) {
									window.location.href = d.url;

								}
								else {
									console.log( d);

								}

							});

						} else { console.log( d); }

					});
				}) (_brayworth_);

				 */

				$authheader = $_SERVER['HTTP_AUTHORIZATION'];
				$token = trim( preg_replace('@^Bearer@i', '', $authheader));

				if ( $token) {
					if ( $decoded = jwt::decode( $token)) {
						// Access is granted. Add code of the operation here

						if ( '-get-direct-logon-' == $action) {
							if ( (int)$decoded->data->id) {
								$dao = new dao\users;
								if ( $dto = $dao->getByID( $decoded->data->id)) {
									$auth_token = bin2hex( random_bytes( 6));
									$dao->UpdateByID([
										'auth_token' => $auth_token,
										'auth_token_expires' => date( 'Y-m-d H:i:s', time() + 60)

									], $dto->id);

									Json::ack( $action)
										->add( 'url', strings::url('?jwt=' . \urlencode( $auth_token), $protocol = true));

								} else { Json::nak( $action);   }

							} else { Json::nak( $action); }

						} else { Json::nak( $action); }

					} else { Json::nak( $action); }

				} else { Json::nak( $action); }

			} else { Json::nak( $action); }

		}
		elseif ( 'upload-file' == $action) {
			$this->upload( $action);

		}
		elseif ( false) {
		//~ elseif ( $action == 'send-invoice') {
			/*
				curl -X POST -H "Accept: application/json" -d action=send-invoice -d id=153 http://localhost/api/
			*/
			if ( $id = (int)$this->getPost( 'id')) {
				$dao = new dao\invoices;
				if ( $dto = $dao->getByID( $id)) {
					$dao->send( $dto);
					\Json::ack( $action);

				} else { \Json::nak( $action); }

			} else { \Json::nak( $action); }

		}

	}

	protected function getLicense( $action) {
		/*
		 *  curl -X POST -H "Accept: application/json" -d action=get-license -d guid="{a guid}" "http://localhost/api/"
		 *  curl -X POST -H "Accept: application/json" -d action=get-license -d guid="{a guid}" "https://my.easydose.net.au/api/"
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

			} else { Json::nak( $action); }

		} else { Json::nak( $action); }

	}

	protected function checkin( $action) {
		$debug = false;
		$debug = true;

		$site = $this->getPost('site');
		if ( $site != '' ) {
			/*
			*  curl -X POST -H "Accept: application/json" -d action=checkin -d site="Davido the Demo" -d state=WA -d address="1 Wisteria Lane" -d suburb="Hogwash"  -d tel=0893494011 -d workstation=WISPER -d deployment=Web -d version="RC2.1.10.0.9" -d productid=EasydoseLegacy -d activated=yes -d expires="2018-01-14" -d patients=24 -d patientsActive=15 -d guid="{a guid}"  "http://localhost/api/"
			*/

			$a = [
				'site' => $site,
				'state' => $this->getPost('state'),
				'town' => $this->getPost( 'suburb'),
				'street' => $this->getPost( 'address'),
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
        'updated' => \db::dbTimeStamp()

      ];

			if ( $a['deployment'] == 'Build' ) {

        sys::logger('api/getAccount - development checkin');
        $email = '';
        $guidDAO = new dao\guid;
        if ( $usersDTO = $guidDAO->getUser( $a['guid'])) {
          $email = $usersDTO->email;

        }

				\Json::ack( sprintf( '%s - developer', $action))
					->add( 'License', config::developer_license)
					->add( 'workstations', config::developer_workstations)
					->add('NextPaymentDue', date( 'Y-m-d', strtotime('+1 month')))
					->add('Subscription_Status', 'active')
					->add('authoritive', 'yes')
          ->add('email', $email)
					;

				return;

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
          $email = '';

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
									if ( $invID = $uDAO->autoCreateInvoiceFromLast( $uDto->id)) {
										\sys::logger( sprintf( 'auto created invoice ..(%s)', $interval->days));
										if ( (int)dao\settings::get('invoice_autosend')) {
											if ( $iDTO = $iDAO->getByID( $invID)) {
												$iDAO->send( $iDTO);

											}

										}

									} else { \sys::logger( sprintf( 'could not create an invoice ..(%s)', $interval->days)); }

								}
								//~ else {
									//~ \sys::logger( sprintf( 'will NOT create a invoice, there is an unpaid one ..(%s)', $interval->days));

								//~ }

							}

						}
						//~ else {
							//~ \sys::logger( sprintf( 'will NOT create a invoice, not due : %s %s ..(%s)', $licenseDTO->expires, $NextPaymentDue, $interval->days));

						//~ }

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

          if ( $usersDTO = $guidDAO->getUser( $a['guid'])) {
						$email = $usersDTO->email;

          }

					$j
						->add('License', $license)
						->add('Workstations', $workstations)
						->add('NextPaymentDue', $NextPaymentDue)
						->add('Subscription_Status', $status)
            ->add('authoritive', $authoritive ? 'yes' : 'no')
            ->add('email', $email)
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
		*  curl -X POST -H "Accept: application/json" -d action="get-account" -d guid="{a guid}" "http://localhost/api/"
		*  curl -X POST -H "Accept: application/json" -d action="get-account" -d guid="{a guid}" -d deployment=Build "http://localhost/api/
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
							->add( 'License', config::developer_license)
							->add( 'description', 'Development License')
							->add( 'state', 'active')
							->add( 'workstations', config::developer_workstations)
							->add( 'expires', date('Y-m-d', strtotime('+1 month')))
							;

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
		*  curl -X POST -H "Accept: application/json" -d action="set-account" -d guid="{a guid}" -d email="david@brayworth.com.au" "http://localhost/api/"
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

	protected function upload( $action) {
		// $debug = true;
		$debug = false;
		$debugOnError = true;
		// $debugOnError = false;

		if ( $debug) \sys::logger( __METHOD__);
		// set_time_limit(600);

		if ( $guid = $this->getPost( 'guid')) {
			$guidDAO = new dao\guid;
			if ( $guidDTO = $guidDAO->getByGUID( $guid)) {
				if ( (int)$guidDTO->user_id > 0) {
					$usersDAO = new dao\users;
					if ( $usersDTO = $usersDAO->getByID( $guidDTO->user_id)) {
						$targetFileBase = preg_replace( '/[^0-9a-zA-Z]/', '_', $usersDTO->username);
						/*--- ---[upload backup]--- ---*/
						$accept = [
							'application/zip'

						];

						if ( isset( $_FILES['file'])) {
							$file = $_FILES['file'];
							if ( $file['error'] == UPLOAD_ERR_INI_SIZE ) {
								if ( $debug || $debugOnError) \sys::logger( sprintf('<%s is too large (ini)> %s : %s', $file['name'], $action, __METHOD__));
								\Json::nak( sprintf( '%s :: %s', $action, $file['name']));

							}
							elseif ( $file['error'] == UPLOAD_ERR_FORM_SIZE ) {
								if ( $debug || $debugOnError) \sys::logger( sprintf('<%s is too large (form)> %s : %s', $file['name'], $action, __METHOD__));
								\Json::nak( sprintf( '%s :: %s is too large (form)', $action, $file['name']));

							}
							elseif ( UPLOAD_ERR_PARTIAL == $file['error']) {
								if ( $debug || $debugOnError) \sys::logger( sprintf('<The file you are trying upload was only partially uploaded> : %s', __METHOD__));
								\Json::nak( 'The file you are trying upload was only partially uploaded');

							}
							elseif ( UPLOAD_ERR_NO_FILE == $file['error']) {
								if ( $debug || $debugOnError) \sys::logger( sprintf('<no file was uploaded> : %s', __METHOD__));
								\Json::nak( 'no file was uploaded');

							}
							elseif ( is_uploaded_file( $file['tmp_name'] )) {
								$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
								$strType = finfo_file($finfo, $file['tmp_name']);
								if ( $debug) sys::logger( sprintf( '%s :: %s (%s)', $action, $file['name'], $strType));

								$ok = TRUE;
								if ( in_array( $strType, $accept)) {
									$source = $file['tmp_name'];
									$_target = sprintf( '%s_%s.%s', $targetFileBase, date('Y-m-d_his'), preg_replace( '@^[a-z]*/@', '', $strType));
									$target = implode( DIRECTORY_SEPARATOR, [
										config::easydose_upload_dir(),
										$_target

									]);

									if ( file_exists( $target )) {
										unlink( $target );

									}

									if ( move_uploaded_file( $source, $target)) {
										chmod( $target, 0666 );
										\Json::ack( sprintf( '%s :: %s', $action, $_target));
										if ( $debug) sys::logger("ack");

									}
									else {
										if ( $debug) sys::logger("Possible file upload attack!  Here's some debugging info:\n" . var_export($_FILES, TRUE));

									}

								}
								elseif ( $strType == "" ) {
									// \Json::nak( sprintf( '%s :: %s invalid file type : %s', $action, $file['name'], print_r( $file['type'], TRUE)));
									// \Json::nak( sprintf( '%s :: invalid file type : %s', $action, print_r( $file, TRUE)));
									\Json::nak( sprintf( '%s :: invalid file type : %s', $action, print_r( $_FILES, TRUE)));
									if ( $debug) \sys::logger( sprintf('<%s> %s', 'invalid file type', __METHOD__));

								}
								else {
									\Json::nak( sprintf( 'upload: %s file type not permitted - %s', $file['name'], $strType));
									if ( $debug) \sys::logger( sprintf('<%s> %s', 'type not permitted', __METHOD__));

								}

							}
							else {
								\Json::nak( sprintf( '%s :: not :: is_uploaded_file( %s)', $action, print_r( $file, true)));
								if ( $debug || $debugOnError) \sys::logger( sprintf('<%s> %s', 'not upload file', __METHOD__));

							}

						}
						else {
							\Json::nak( sprintf( '%s :: no $_FILE[]', $action));
							if ( $debug || $debugOnError) \sys::logger( sprintf('<%s> %s', 'no file', __METHOD__));

						}
						/*--- ---[upload backup]--- ---*/

					}
					else {
						Json::nak( $action);
						if ( $debug || $debugOnError) \sys::logger( sprintf('<%s> %s', 'user not found', __METHOD__));

					}

				}
				else {
					Json::nak( $action);
					if ( $debug || $debugOnError) \sys::logger( sprintf('<%s> guid does not have user id %s', $guid, __METHOD__));

				}

			}
			else {
				Json::nak( $action);
				if ( $debug || $debugOnError) \sys::logger( sprintf('<%s> guid not found %s', $guid, __METHOD__));

			}

		}
		else {
			Json::nak( $action);
			if ( $debug || $debugOnError) \sys::logger( sprintf('<%s> %s', 'no guid', __METHOD__));

		}

		if ( $debug || $debugOnError) \sys::logger( sprintf('<%s> %s', 'end', __METHOD__));

	}

	public function _index() {
		print 'EasyDose API handler';

	}

}
