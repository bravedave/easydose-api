<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	security: admin only

	*/
class easyLog extends Controller {
	protected function postHandler() {
		$action = $this->getPost('action');

		if ( 'get-log' == $action) {
			$where = [];
			if ( $guid = (int)$this->getPost('guid')) {
				$where[] = sprintf( 'guid_id = %d', $guid);

			}

			if ( $site = (int)$this->getPost('site')) {
				$where[] = sprintf( 'site_id = %d', $site);

			}

			if ($user = (int)$this->getPost('user')) {
				$where[] = sprintf( 'user_id = %d', $user);

			}

			$sql = 'SELECT easyLog.*, u.name user_name FROM easyLog LEFT JOIN users u ON u.id = easyLog.updated_by';
			if ( count( $where)) {
				$sql .= sprintf( ' WHERE %s', implode( ' AND ', $where));

			}
			$sql .= ' ORDER BY created DESC';

			// \sys::logSQL( $sql);

			if ( $res = $this->dbResult( $sql)) {
				\Json::ack( $action)
					->add('data', $res->dtoSet());

			}
			else { \Json::nak( $action); }

		}
		elseif ( 'post comment' == $action) {
			$a = [
				'comment' => $this->getPost('comment'),
				'created' => \db::dbTimeStamp(),
				'updated' => \db::dbTimeStamp(),
				'updated_by' => currentUser::id(),
				'guid_id' => (int)$this->getPost('guid'),
				'site_id' => (int)$this->getPost('site'),
				'user_id' => (int)$this->getPost('user')

			];

			if ( $a['comment']) {
				$dao = new dao\easyLog;
				$dao->Insert( $a);

				\Json::ack( $action);

			}
			else { \Json::nak( $action); }

		}
		else { \Json::nak( $action); }

	}

	protected function _index() {
		$ths->render([
			'title' => 'EasyLog',
			'primary' => 'blank',
			'secondary' => 'blank'

		]);

	}

	public function index() {
		if ( $this->isPost()) {
      $this->postHandler();

    }
    else {
      $this->_index();

    }

	}

}
