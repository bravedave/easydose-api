<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
 * security: admin only
*/

use dvc\session;

class users extends Controller {
  protected function before() {
    parent::before();
    $this->RequireValidation = \sys::lockdown();
  }

  protected function postHandler() {
    $action = $this->getPost('action');
    //~ sys::dump( $this->getPost());

    if ($action == 'delete') {
      if ($id = (int)$this->getPost('id')) {
        $dao = new dao\users;
        $dao->delete($id);

        \Json::ack($action);
      } else {
        \Json::nak($action);
      }
    } elseif ($action == 'get-invoices-for-user') {
      /*
			_brayworth_.post({
				url : _ed_.url('users'),
				data : {
					action : 'get-invoices-for-user',
					id : 44

				}

			}).then( function( d) { console.log( d); });
			*/

      if ($id = (int)$this->getPost('id')) {
        $dao = new dao\invoices;
        if ($dtoSet = $dao->getForUser($id)) {
          $a = [];
          foreach ($dtoSet as $dto) {
            $a[] = (object)[
              'id' => $dto->id,
              'state' => (string)$dto->state,
              'created' => date('Y-m-d', strtotime($dto->created)),

            ];
          }

          \Json::ack($action)->add('data', $a);
        } else {
          \Json::nak($action);
        }
      } else {
        \Json::nak($action);
      }
    } elseif ($action == 'save/update') {
      $dao = new dao\users;
      $id = (int)$this->getPost('id');

      $a = [
        'updated' => db::dbTimeStamp(),
        'name' => (string)$this->getPost('name'),
        'email' => (string)$this->getPost('email'),
        'admin' => (int)$this->getPost('admin'),
        'suppress_invoice' => (int)$this->getPost('suppress_invoice'),
        'business_name' => (string)$this->getPost('business_name'),
        'street' => (string)$this->getPost('street'),
        'town' => (string)$this->getPost('town'),
        'state' => (string)$this->getPost('state'),
        'postcode' => (string)$this->getPost('postcode'),
        'abn' => (string)$this->getPost('abn')
      ];

      if ($pass = $this->getPost('pass')) {
        $a['pass'] = password_hash($pass, PASSWORD_DEFAULT);
      }

      if ($id) {
        $dao->UpdateByID($a, $id);
        Response::redirect('users/view/' . $id, 'updated user');
      } else {
        /** ensure username is unique */
        $a['created'] = db::dbTimeStamp();
        $a['username'] = strtolower($this->getPost('username'));

        if ($dto = $dao->getUserByUserName($a['username'])) {
          Response::redirect('users/view/' . $dto->id, 'user already exists');
        } else {
          $id = $dao->Insert($a);
          Response::redirect('users/view/' . $id, 'added user');
        }
      }
    } elseif ($action == 'switch') {

      if ($guid = $this->getPost('guid')) {
        $dao = new dao\guid;
        if ($dto = $dao->getByGUID($guid)) {
          if ($u = $dao->getUserOf($dto)) {
            session::edit();
            session::set('uid', $u->id);
            session::close();

            \Json::ack($action);
          } else {
            \Json::nak($action);
          }
        } else {
          \Json::nak(sprintf('%s : guid not found', $action));
        }
      } else {
        \Json::nak(sprintf('%s : missing guid', $action));
      }
    }
  }

  protected function _edit($id = 0, $readonly = false) {
    if (currentUser::isAdmin()) {
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
          'admin' => 0
        ],
        'license' => false
      ];

      // $dbc->defineField( 'pass', 'text');
      // $dbc->defineField( 'admin', 'int');
      // $dbc->defineField( 'reset_guid', 'text');
      // $dbc->defineField( 'reset_guid_date', 'text');
      // $dbc->defineField( 'created', 'text');
      // $dbc->defineField( 'updated', 'text');

      if ($id = (int)$id) {
        $dao = new dao\users;
        if ($dto = $dao->getByID($id)) {
          $this->data = (object)['dto' => $dto];

          $dao = new dao\sites;
          $dao->discover($dto);
        } else {
          throw new \Exceptions\InvalidAccount;
        }
      }

      $this->data->readonly = $readonly;
      $latestSite = false;
      if ($readonly) {
        //~ sys::dump( $this->data->license);

        $dao = new dao\invoices;
        $this->data->invoices = $dao->getForUser($id);
        if (!($this->data->license = $dao->getActiveLicenseForUser($id))) {
          $dao = new dao\license;
          $this->data->license = $dao->getLicense($id);
        }
        //~ sys::dump( $this->data->license);

        $dao = new dao\guid;
        $this->data->guid = $dao->getForUser($id);

        $dao = new dao\sites;
        $this->data->sites = [];
        foreach ($this->data->guid as $guid) {
          if ($res = $dao->getForGUID($guid->guid)) {
            while ($site = $res->dto()) {
              $this->data->sites[] = $site;
              if ($latestSite) {
                if (strtotime($site->updated) > strtotime($latestSite->updated)) {
                  $latestSite = $site;
                }
              } else {
                $latestSite = $site;
              }

              // \sys::logger( $site->updated);

            }
          }
        }
      }

      $this->data->latestSite = $latestSite;

      $this->render([
        'title' => $this->title = 'User',
        'primary' => 'edit',
        'secondary' => 'index'

      ]);
    }
  }

  protected function _index() {
    if (currentUser::isAdmin()) {
      $dao = new dao\users;
      $this->data = $dao->getAll();
      //~ sys::dump( $this->data);

      $this->render([
        'title' => $this->title = 'Users',
        'primary' => 'report',
        'secondary' => 'index'
      ]);
    }
  }

  public function createinvoice($id = 0) {
    if (currentUser::isAdmin()) {
      if ($id = (int)$id) {
        $dao = new dao\users;
        if ($dto = $dao->getByID($id)) {

          $daoProducts = new dao\products;
          $settings = new dao\settings;

          $this->data = (object)[
            'account' => $dto,
            'products' => $daoProducts->getDtoSet(),
            'productsWKS' => $daoProducts->getDtoSet($type = "WKS"),
            'sys' => $settings->getFirst(),
            'personal' => '0'
          ];

          $this->render([
            'title' => $this->title = 'Create Invoice',
            'primary' => 'account/invoice-create',
            'secondary' => 'index'
          ]);
        } else {
          throw new \Exceptions\InvalidAccount;
        }
      } else {
        throw new \Exceptions\InvalidAccount;
      }
    }
  }

  public function createinvoiceAUTO($id = 0) {
    if (currentUser::isAdmin()) {
      if ($id = (int)$id) {
        $dao = new dao\users;
        if ($invID = $dao->autoCreateInvoiceFromLast($id)) {
          Response::redirect(url::tostring('account/invoice/' . $invID), 'created invoice');
        }
      } else {
        throw new \Exceptions\InvalidAccount;
      }
    }
  }

  public function due() {
    $dao = new dao\users;
    $this->data = $dao->getUserLicenses();

    //~ sys::dump( $this->data);

    $this->render([
      'title' => $this->title = 'Users - Due',
      'primary' => 'due',
      'secondary' => 'index'

    ]);
  }

  public function edit($id = 0) {
    if (currentUser::isAdmin()) {
      $this->_edit($id, $readonly = false);
    }
  }

  public function index() {
    $this->isPost() ?
      $this->postHandler() :
      $this->_index();
  }

  public function view($id = 0) {
    if (currentUser::isAdmin()) {
      $this->_edit($id, $readonly = true);
    }
  }
}
