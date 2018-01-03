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
    // $debug = TRUE;
    $debug = FALSE;

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
      $site = $this->getPost('site');
      if ( $site != '' ) {
        if ( $debug) \sys::logger( "api $action : $site");

        /*
         *  curl -X POST -H "Accept: application/json" -d action=checkin -d site="Davido the Demo" -d state=WA -d tel=0893494011 -d workstation=WISPER -d deployment=Build -d version="RC2.1.10.0.9" -d productid=EasydoseLegacy -d activated=yes -d expires="2018-01-14" -d patients=24 -d patientsActive=15 "http://localhost/api/"
         *  curl -X POST -H "Accept: application/json" -d action=checkin -d site="Davido the Demo" -d state=WA -d tel=0893494011 -d workstation=WISPER -d deployment=Build -d version="RC2.1.10.0.9" -d productid=EasydoseLegacy -d activated=yes -d expires="2018-01-14" -d patients=24 -d patientsActive=15 "https://my.easydose.net.au/api/"
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
          $dao = new dao\sites;
          $res = $this->dbResult( sprintf( "SELECT * FROM SITES WHERE site = '%s' AND workstation = '%s'",
            $this->db->escape( $a['site']),
            $this->db->escape( $a['workstation'])));

          if ( $res) {
            if ( $dto = $res->dto()) {
              $dao->UpdateByID( $a, $dto->id );
              \sys::logger( sprintf( 'site: updated => %s, %s', $a['site'], $a['workstation'] ));
              \Json::ack($action);

            }
            else {
              $dao->Insert( $a);
              \sys::logger( sprintf( 'site: inserted => %s, %s', $a['site'], $a['workstation'] ));
              \Json::ack($action);

            }

          }
          else {
            \Json::nak($action);

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
          \Json::ack($action);

        }

      } // if ( $site != '' )
      else {
        \Json::nak($action);

      }

    }

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
