<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	description:
		Controller for accessing the user account

	security:
	 	Ordinary Authenticated user - non admin

	*/
class products extends Controller {
	protected function posthandler() {
    $action = $this->getPost('action');

    if ( 'save' == $action ) {
      $id = (int)$this->getPost('id');

      $a = [
        'updated' => \db::dbTimeStamp(),
        'name' => $this->getPost('name'),
        'description' => $this->getPost('description'),
        'rate' => $this->getPost('rate'),
        'term' => $this->getPost('term')

      ];

      $dao = new dao\products;
      if ( $id) {
        $dao->UpdateByID( $a, $id);
        Response::redirect( url::tostring('products'), 'updated');

      }
      else {
        $a['created'] = $a['updated'];
        $id = $dao->Insert( $a);
        Response::redirect( url::tostring('products'), 'added');

      }

    }

	}

	protected function _index() {
    $dao = new dao\products;
    $this->data = (object)[
        'res' => $dao->getAll()
    ];

		$p = new page( $this->title = "products");
      $p
        ->header()
        ->title();
			$p->primary();
				$this->load( 'products' );

			$p->secondary();
        $this->load('main-index');

	}

	public function edit( $id = 0) {
    if ( currentUser::isAdmin()) {
      $this->data = (object)[
        'dto' => (object)[
          'id' => '',
          'name' => '',
          'description' => '',
          'rate' => '',
          'term' => ''

        ]

      ];

      if ( $id) {
        $dao = new dao\products;
        $this->data->dto = $dao->getByID( $id);

      }

      $p = new page( $this->title = ( (int)$id ? "edit product" : "new product"));
      $p
				->header()
				->title();
      $p->primary();
        $this->load('edit');

      $p->secondary();
        $this->load( 'blank' );


    }

  }

	public function index() {
		if ( $this->isPost()) {
      $this->postHandler();

    }
		elseif ( currentUser::isAdmin()) {
			$this->_index();

    }

	}


}
