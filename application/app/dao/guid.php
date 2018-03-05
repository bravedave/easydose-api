<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class guid extends _dao {
	protected $_db_name = 'guid';
	protected $template = '\dao\dto\guid';

	protected function addGUID( $guid) {
		if ( strlen( trim( $guid)) == 38) {
			// \sys::logger( strlen( trim( $guid)));

			$a = [
				'guid' => $guid,
				'created' => \db::dbTimeStamp(),
				'updated' => \db::dbTimeStamp()];

			$id = $this->Insert( $a);
			\sys::logger( sprintf( 'dao\guid->addGUID :: adding %s (%s)', $guid, $id));

			return ( $id);

		}

		return ( FALSE);

	}

	public function getByGUID( $guid) {
		if ( $res = $this->Result( sprintf( 'SELECT * FROM %s WHERE guid = "%s"', $this->db_name(), $guid ))) {
			if ( $dto = $res->dto( $this->template))
				return $dto;

		}

		if ( $id = $this->addGUID( $guid))
			return ( $this->getByID( $id));

		return ( FALSE);

	}

	public function getForUser( $userID = 0) {
		if ( is_null( $this->_db_name)) {
			throw new Exceptions\DBNameIsNull;

		}

		if ( !(int)$userID) {
			$userID = \currentUser::id();

		}

		$this->db->log = $this->log;
		$sql = sprintf( 'SELECT * FROM guid WHERE user_id = %s', $userID);

		if ( $res = $this->Result( $sql)) {
			return ( $res->dtoSet());

		}

		return ( FALSE);

	}

	public function getAll( $fields = 'guid.*, u.name', $order = '' ) {
		if ( is_null( $this->_db_name))
			throw new Exceptions\DBNameIsNull;

		$this->db->log = $this->log;
		$sql = sprintf( 'SELECT %s FROM guid LEFT JOIN users u on user_id = u.id %s', $fields, $order);

		return ( $this->Result( $sql));

	}

	public function getLicenseOf( \dao\dto\guid $dto) {
		$debug = FALSE;
		$debug = TRUE;

		if ( (int)$dto->user_id) {
			$licenseDAO = new license;	// dao\license;
			if ( $license = $licenseDAO->getLicense( $dto->user_id)) {
				if ( $debug) \sys::logger( sprintf( 'dao\guid->getLicenseOf :: %s license', $license->state));
				if ( 'active' == strtolower( $license->state)) {
					if ( $debug) \sys::logger( sprintf( 'dao\guid->getLicenseOf :: return active license : %s', $license->expires));
					return ( $license);

				}

				/*
				* The default license is either - in order of priority

					1. The commercially purchased license
					2. The specified GRATIS license
					3. If the GUID is less than 3 months old an easydoseFREE license

				*/

				if (( $_time = strtotime( $dto->grace_expires)) > 0 ) {
					if ( in_array( $dto->grace_product, \config::products))
					$license->type = 'GRATIS';
					$license->product = $dto->grace_product;
					$license->workstations = max( $dto->grace_workstations, 1);
					$license->state = 'active';
					$license->expires = date( 'Y-m-d', $_time);
					return ( $license);

				}

				$dOrigin = strtotime( $dto->created);
				$dFreeExpires = date( 'Y-m-d', strtotime( '+3 months', $dOrigin));
				// \sys::logger( sprintf( '%s : %s', date( 'Y-m-d', $dOrigin), $dFreeExpires));

				if ( date( 'Y-m-d') <= $dFreeExpires) {
					$license->type = 'GRATIS';
					$license->product = 'easydoseFREE';
					$license->state = 'active';
					$license->workstations = 1;
					$license->expires = $dFreeExpires;
					return ( $license);

				}

			}

		}

		return ( FALSE);

	}

	public function getLicense( $guid) {
		$debug = FALSE;
		// $debug = TRUE;

		if ( $debug) \sys::logger( sprintf( 'dao\guid->getLicense(%s) :: getting license', $guid));

		if ( $dto = $this->getByGUID( $guid)) { // will add guid if it doesn't exist
			return ( $this->getLicenseOf( $dto));

		}

		return ( FALSE);

	}

}
