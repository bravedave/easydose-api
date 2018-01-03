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
       *  curl -X POST --header "Accept: application/json" --data '{"action":"guid"}' "https://my.easydose.net.au/api/"
       */
      \Json::ack( $action)
        ->add('data', strings::getGUID());

    }
    elseif ( $action = 'checkin') {
      $site = $this->getPost('site');
      if ( $site != '' ) {

        /*
        	/easydose/license/
        		?site=Royal%20Street%20Chemmart
        		&state=WA
        		&tel=0893494011
        		&workstation=WISPER
        		&deployment=Build
        		&version=RC2.1.10.0.9
        		&productid=EasydoseLegacy
        		&activated=yes
        		&expires=2015-01-14
        		&patients=24
        		&patientsActive=15
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
          "updated" => \db:dbTimeStamp()];

        if ( $a['deployment' != "" ) {
          $dao = new dao\sites;
          $res = $this->Result( sprintf( "SELECT * FROM SITES WHERE site = '%s' AND workstation = '%s'",
            $this->escape( $a['site']),
            $this->escape( $a['workstation')));

          if ( $res) {
            if ( $dto = $res->dto()) {
              $dao->UpdateByID( $a, $dto->id );
              \sys::logger( sprintf( 'site: updated => %s, %s', $a['site', $a['workstation'] ));

            }
            else {
              $dao->Insert( $a);
              \sys::logger( sprintf( 'site: inserted => %s, %s', $a['site', $a['workstation'] ));
            }

          }

	      }	else {
          \sys::logger( sprintf( 'site: %s, %s, %s, %s @ (%s), %s (activated:%s - %s)',
            $a['site'],
            $a['workstation'],
            $a['version'],
            $a['deployment'],
            $a['ip'],
            $a['productid'],
            $a['activated'],
            $a['expires']));

        }

      } // if ( $site != '' )

    }

	}

  public function index() {
    if ( $this->isPost())
      $this->postHandler();

  }

}