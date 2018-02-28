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
    if ( $guid = $this->getPost('guid')) {
    // if ( $guid = '{D226CA40-CA53-94C2-2DC1-F86851D79F20}') {

      $guidDAO = new dao\guid;
      $licenseDAO = new dao\license;

      if ( $guidDTO = $guidDAO->getByGUID( $guid)) { // will add guid if it doesn't exist
        if ( (int)$guidDTO->user_id) {
          if ( $licenseDTO = $licenseDAO->getLicense( $guidDTO->user_id)) {
            // \sys::dump( $licenseDTO, NULL, FALSE);
            json::ack( $action)
              ->add( 'type', $licenseDTO->type)
              ->add( 'description', $licenseDTO->description)
              ->add( 'product', $licenseDTO->product)
              ->add( 'state', $licenseDTO->state)
              ->add( 'workstations', $licenseDTO->workstations)
              ->add( 'expires', $licenseDTO->expires);

          }
          else { json::nak( $action); }

        }
        else { json::nak( $action); }
        // \sys::dump( $guidDTO);

      }
      else { json::nak( $action); }

    }
    else { json::nak( $action); }

  }

  protected function checkin( $action) {
    $debug = FALSE;
    // $debug = TRUE;

    $site = $this->getPost('site');
    if ( $site != '' ) {
      /*
      *  curl -X POST -H "Accept: application/json" -d action=checkin -d site="Davido the Demo" -d state=WA -d tel=0893494011 -d workstation=WISPER -d deployment=Build -d version="RC2.1.10.0.9" -d productid=EasydoseLegacy -d activated=yes -d expires="2018-01-14" -d patients=24 -d patientsActive=15 -d guid="{D226CA40-CA53-94C2-2DC1-F86851D79F20}" "http://localhost/api/"
      *  curl -X POST -H "Accept: application/json" -d action=checkin -d site="Davido the Demo" -d state=WA -d tel=0893494011 -d workstation=WISPER -d deployment=Build -d version="RC2.1.10.0.9" -d productid=EasydoseLegacy -d activated=yes -d expires="2018-01-14" -d patients=24 -d patientsActive=15 -d guid="{9D85652E-E7D8-BAED-7C89-72720005B87D}" "http://localhost/api/"
      *  curl -X POST -H "Accept: application/json" -d action=checkin -d site="Davido the Demo" -d state=WA -d tel=0893494011 -d workstation=WISPER -d deployment=Build -d version="RC2.1.10.0.9" -d productid=EasydoseLegacy -d activated=yes -d expires="2018-01-14" -d patients=24 -d patientsActive=15 -d guid="{9D85652E-E7D8-BAED-7C89-72720005B87D}"  "https://my.easydose.net.au/api/"
      */

      $a = [
        "site" => $site,
        "state" => $this->getPost('state'),
        "tel" => $this->getPost('tel'),
        "ip" => $this->Request->getRemoteIP(),
        "workstation" => $this->getPost('workstation'),
        "productid" => $this->getPost('productid'),
        "patients" => $this->getPost('patients'),
        "patientsActive" => $this->getPost('patientsActive'),
        "os" => $this->getPost('OS'),
        "deployment" => $this->getPost('deployment'),
        "version" => $this->getPost('version'),
        "activated" => ( $this->getPost('activated') == "yes" ? 1 : 0 ),
        "expires" => $this->getPost('expires'),
        "guid" => $this->getPost('guid'),
        "updated" => \db::dbTimeStamp()];

      if ( $a['deployment'] != "" ) {
        $res = $this->dbResult( sprintf( "SELECT * FROM SITES WHERE site = '%s' AND workstation = '%s'",
          $this->db->escape( $a['site']),
          $this->db->escape( $a['workstation'])));

        if ( $res) {

          $agreementsDAO = new dao\agreements;
          $plansDAO = new dao\plans;
          $guidDAO = new dao\guid;
          $sitesDAO = new dao\sites;
          $licenseDAO = new dao\license;
          $licenseDTO = FALSE;
          $agreementsID = 0;
          $license = 'none';
          $status = 'inactive';
          $workstations = 0;
          $NextPaymentDue = date('Y-m-d', 0);

          if ( $guidDTO = $guidDAO->getByGUID( $a['guid'])) { // will add guid if it doesn't exist
            if ( (int)$guidDTO->user_id) {
              if ( $licenseDTO = $licenseDAO->getLicense( $guidDTO->user_id)) {
                // \sys::dump( $licenseDTO, NULL, FALSE);
                $a['productid'] = $licenseDTO->product;
                $a['expires'] = date( 'Y-m-d', strtotime($licenseDTO->expires));

                // ->add( 'type', $licenseDTO->type)
                // ->add( 'description', $licenseDTO->description)
                // ->add( 'state', $licenseDTO->state)
                // ->add( 'workstations', $licenseDTO->workstations)
                $license = $licenseDTO->product;
                $status = $licenseDTO->state;
                $workstations = $licenseDTO->workstations;
                $NextPaymentDue = date( 'Y-m-d', strtotime($licenseDTO->expires));
              }

            }

          }

          if ( $sitesDTO = $res->dto()) {
            $sitesDAO->UpdateByID( $a, $sitesDTO->id );
            if ( $debug) \sys::logger( sprintf( 'site: updated => %s, %s', $a['site'], $a['workstation'] ));
            $j = \Json::ack( $action);


          }
          else {
            $sitesDAO->Insert( $a);
            if ( $debug) \sys::logger( sprintf( 'site: inserted => %s, %s', $a['site'], $a['workstation'] ));
            $j = \Json::ack( $action);

          }

          // elseif ( $guidDTO) {
          //   if ( $agreementsID = $guidDTO->agreements_id) {
          //     if ( $agreementsDTO = $agreementsDAO->getByID( $agreementsID)) {
          //       if ( $agreementsDTO->plan_id) {
          //         $status = strtolower( $agreementsDTO->state);
          //         $NextPaymentDue = date( 'Y-m-d', strtotime( $agreementsDTO->next_billing_date));
          //         if ( $plansDTO = $plansDAO->getByPayPalID( $agreementsDTO->plan_id)) {
          //           $license = $plansDTO->name;
          //
          //         }
          //
          //       }
          //
          //     }
          //
          //   }
          //
          // }

          // ->add('agreementsID', $agreementsID)
          $j
            ->add('License', $license)
            ->add('Workstations', $workstations)
            ->add('NextPaymentDue', $NextPaymentDue)
            ->add('Subscription_Status', $status)
            ;

        }
        else {
          \Json::nak( $action);

        }

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

    } // if ( $site != '' )
    else {
      \Json::nak($action);

    }

  }

  protected function getAccount( $action) {
    /*
     *  return the email address associated with the account
     *
     *  curl -X POST -H "Accept: application/json" -d action="get-account" -d guid="{9D85652E-E7D8-BAED-7C89-72720005B87D}" "http://localhost/api/"
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

        \Json::ack( $action)
          ->add( 'guid', $guid)
          ->add( 'email', $email);

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
              $guidDAO->UpdateByID([
                'user_id' => $usersDTO->id,
                'updated' => \db::dbTimeStamp()
              ], $guidDTO->id);
              \Json::ack( sprintf( '%s :: found account', $action))
                ->add( 'email', $email);

            }
            else {
              $a = [
                'username' => $email,
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
    if ( $this->isPost()) {
      $this->postHandler();

    }
    else {
      print 'EasyDose API handler';

    }

  }

}
