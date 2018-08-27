<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		Controller for accessing the sites

	security:
	 	admin

	*/
class sites extends Controller {

  protected function postHandler() {
    $action = $this->getPost('action');

    if ( 'delete' == $action) {
      if ( $id = (int)$this->getPost('id')) {
        $dao = new dao\sites;
        $dao->delete( $id);
        \Json::ack( $action);

      }
      else {
        \Json::nak( $action);

      }

    }
    else {
      \Json::nak( $action);

    }

  }

  public function createaccount( $id = 0) {
    /**
    * we are going to create an account from this id
    */
    if ( currentUser::isAdmin()) {
      if ( (int)$id) {
        $dao = new dao\sites;
        if ( $site = $dao->getByID( $id)) {
          /*
          * it must have an email and
          * the email must be unique
          * in the system
          */
          if ( strings::IsEmailAddress( $site->email)) {
            $usersDAO = new dao\users;
            if ( $userDTO = $usersDAO->getUserByEmail( $site->email)) {
              Response::redirect( url::tostring('users/view/' . $userDTO->id), 'user exists');

            }
            else {
              /*
              * It will have a guid - that was
              * how it got created
              * fetch it so we can update it
              */
              if ( $site->guid) {
                $guidDAO = new dao\guid;
                if ( $guidDTO = $guidDAO->getByGUID( $site->guid)) {
                  $a = explode( '@', $site->email);
                  $username = (string)$a[0];

                  /*
                  * create the user and update
                  * the guid table with the
                  * created users id
                  */
                  $id = $usersDAO->Insert([
                    'username' => $username,
                    'name' => $site->site,
                    'email' => $site->email,
                    'business_name' => $site->site,
                    'state' => $site->state,
                    'abn' => $site->abn,
                    'created' => \db::dbTimeStamp(),
                    'updated' => \db::dbTimeStamp()
                  ]);

                  $guidDAO->UpdateByID([
                    'user_id' => $id,
                    'updated' => \db::dbTimeStamp()
                  ], $guidDTO->id);

                  Response::redirect( url::tostring('users/view/' . $id), 'created user');

                }
                else { throw new \Exceptions\InvalidGUID; }

              }
              else { throw new \Exceptions\InvalidGUID; }

            }

				}
				else { throw new \Exceptions\InvalidEmailAddress; }

			}
			else { Response::redirect( url::toString( 'sites', 'site not found')); }

		}
		else { Response::redirect( url::toString( 'sites', 'invalid site id')); }

	}
	else { throw new \Exceptions\AccessViolation; }

	}

	public function view( $id = 0) {
		if ( currentUser::isAdmin()) {
			if ( (int)$id) {
				$dao = new dao\sites;
				if ( $site = $dao->getByID( $id)) {
					$this->data = (object)[
						'site' => $site,
						'guid' => FALSE,
						'account' => FALSE
					];

					if ( $this->data->site->guid) {
						$dao = new dao\guid;
						if ($this->data->guid = $dao->getByGUID( $this->data->site->guid)) {
							$this->data->account = $dao->getUserOf( $this->data->guid);

						}

					}

					$this->render([
						'title' => $this->title = 'Site',
						'primary' => 'view',
						'secondary' => 'main-index']);

				}
				else { Response::redirect( url::toString( 'sites', 'site not found')); }

			}
			else { $this->index(); }

		}

	}

  protected function _index() {
    if ( currentUser::isAdmin()) {
      $dao = new dao\sites;
      $this->data = (object)[
        'sites' => $dao->getAllIncludeUserID()

      ];

      // \sys::dump( $this->data);
      // while ( $dto = $this->data->sites->dto()) {
      //   if ( $dto->id == 77) {
      //     \sys::dump( $dto);
      //
      //   }
      //
      // }

      $p = $this->page(['title' => ( $this->title = 'Sites')]);
        $p->meta[] = sprintf( '<meta http-equiv="refresh" content="300; url=%s" />', url::tostring('sites'));
  			$p
  				->header()
  				->title();

        $p->primary10(); $this->load('list');

        $p->secondary2(); $this->load('main-index');

    }

  }

  public function index() {
    if ( currentUser::isAdmin()) {
      $this->isPost() ?
        $this->postHandler() :
        $this->_index();

    }
    else {
      Response::redirect( url::tostring());

    }

  }

  public function remove( $id = 0, $guid = 0) {
    if ( currentUser::isAdmin()) {
      if ( (int) $id) {
        $dao = new dao\sites;
        $dao->delete( $id);

      }

      if ( (int)$guid) {
        Response::Redirect( url::tostring( 'guid/view/' . $guid), 'removed site');

      }
      else {
        Response::Redirect( url::tostring(), 'removed site');

      }

    }
    else {
      Response::Redirect();

    }

  }

}
