<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

class guid extends _dao {
  protected $_db_name = 'guid';
  protected $template = '\dao\dto\guid';

  protected function addGUID($guid) {
    if (strlen(trim($guid)) == 38) {
      // \sys::logger( strlen( trim( $guid)));

      $a = [
        'guid' => $guid,
        'created' => \db::dbTimeStamp(),
        'updated' => \db::dbTimeStamp()
      ];

      $id = $this->Insert($a);
      \sys::logger(sprintf('dao\guid->addGUID :: adding %s (%s)', $guid, $id));

      return ($id);
    }

    return (FALSE);
  }

  public function getByGUID($guid) {
    if ($res = $this->Result(sprintf('SELECT * FROM %s WHERE guid = "%s"', $this->db_name(), $guid))) {
      if ($dto = $res->dto($this->template))
        return $dto;
    }

    if ($id = $this->addGUID($guid))
      return ($this->getByID($id));

    return (FALSE);
  }

  public function getForUser($userID = 0) {
    if (is_null($this->_db_name)) {
      throw new Exceptions\DBNameIsNull;
    }

    if (!(int)$userID) $userID = \currentUser::id();

    $this->db->log = $this->log;
    $sql = sprintf('SELECT * FROM guid WHERE user_id = %s', $userID);

    if ($res = $this->Result($sql)) {
      return ($res->dtoSet(null, $this->template));
    }

    return (false);
  }

  public function getAll($fields = 'guid.*, u.name, s.site, s.expires', $order = '') {
    if (is_null($this->_db_name))
      throw new Exceptions\DBNameIsNull;

    $this->db->log = $this->log;
    $this->Q('DROP TABLE IF EXISTS _tmpsites');
    $this->Q('CREATE TEMPORARY TABLE _tmpsites(`id` INTEGER PRIMARY KEY AUTOINCREMENT, `guid` TEXT, `site` TEXT, `updated` TEXT, `expires` TEXT)');

    $this->Q('INSERT INTO _tmpsites(guid, site, updated) SELECT guid, site, updated FROM sites GROUP BY guid ORDER BY updated DESC');
    // $this->Q('CREATE TEMPORARY TABLE _tmpsites AS SELECT guid, site, updated FROM sites GROUP BY guid ORDER BY updated DESC');
    // $this->Q('ALTER TABLE _tmpsites ADD COLUMN `id` INTEGER PRIMARY KEY AUTOINCREMENT');
    // $this->Q('ALTER TABLE _tmpsites ADD COLUMN `expires` TEXT');
    // \sys::logSQL( $sql);

    if ($res = $this->Result('SELECT id, guid FROM _tmpsites')) {
      $res->dtoSet(function ($dto) {
        if ($license = $this->getLicense($dto->guid)) {
          $this->db->Update('_tmpsites', ['expires' => $license->expires], sprintf('WHERE `id` = %s', $dto->id));
        }
        // \sys::dump( $license);

      });
    }

    $sql = sprintf('SELECT %s FROM guid LEFT JOIN users u on user_id = u.id LEFT JOIN _tmpsites s on s.guid = guid.guid %s', $fields, $order);
    return ($this->Result($sql));
  }

  public function getLatestSite($guid) {
    $sql = sprintf(
      'SELECT
        *
      FROM `sites`
      WHERE `guid` = "%s"
      ORDER BY `updated` DESC
      LIMIT 1',
      $this->db_name(),
      $guid
    );

    if ($res = $this->Result($sql)) {
      return $res->dto();
    }

    return null;
  }

  public function getGratisLicenseOf(\dao\dto\guid $dto) {

    $license = new \dao\dto\license;

    if (($_time = strtotime($dto->grace_expires)) > 0) {
      if (in_array($dto->grace_product, \config::products)) {
        $license->type = 'GRATIS';
        $license->product = $dto->grace_product;
        $license->description = sprintf('FREE : %s', $dto->grace_product);
        $license->workstations = max((int)$dto->grace_workstations, 1);
        $license->state = 'active';
        $license->expires = date('Y-m-d', $_time);

        return ($license);
      }
    }

    $dOrigin = strtotime($dto->created);
    $dFreeExpires = date('Y-m-d', strtotime('+3 months', $dOrigin));
    // \sys::logger( sprintf( '%s : %s', date( 'Y-m-d', $dOrigin), $dFreeExpires));

    if (date('Y-m-d') <= $dFreeExpires) {
      $license->type = 'GRATIS';
      $license->product = 'easydoseFREE';
      $license->description = 'FREE : easydoseFREE';
      $license->state = 'active';
      $license->workstations = 1;
      $license->expires = $dFreeExpires;

      return ($license);
    }
  }

  public function getGratisLicense($guid) {
    if ($dto = $this->getByGUID($guid)) { // will add guid if it doesn't exist
      return ($this->getGratisLicenseOf($dto));
    }

    return (FALSE);
  }

  public function getLicenseOf(\dao\dto\guid $dto) {
    $debug = false;
    // $debug = true;

    if ((int)$dto->user_id) {
      $licenseDAO = new license;  // dao\license;
      if ($license = $licenseDAO->getLicense($dto->user_id)) {
        if ($debug) \sys::logger(sprintf('dao\guid->getLicenseOf :: %s license', $license->state));
        if ('active' == strtolower($license->state)) {
          if ($debug) \sys::logger(sprintf('dao\guid->getLicenseOf :: return active license : %s', $license->expires));
          $license->authoritive = true;
          return ($license);
        }

        /*
				* The default license is either - in order of priority

					1. The commercially purchased license
					2. The specified GRATIS (Free) license
					3. If the GUID is less than 3 months old an easydoseFREE license

				*/
        if ($license = $this->getGratisLicenseOf($dto)) {
          $license->authoritive = true;
        }

        return ($this->getGratisLicenseOf($dto));
      }
    }

    return (FALSE);
  }

  public function getLicense($guid) {
    $debug = false;
    // $debug = true;

    if ($debug) \sys::logger(sprintf('dao\guid->getLicense(%s) :: getting license', $guid));

    if ($dto = $this->getByGUID($guid)) { // will add guid if it doesn't exist
      return ($this->getLicenseOf($dto));
    }

    return (false);
  }

  public function getUserOf(\dao\dto\guid $dto) {
    $debug = FALSE;
    // $debug = TRUE;

    if ((int)$dto->user_id) {
      $userDAO = new users;  // dao\users;
      if ($user = $userDAO->getByID($dto->user_id)) {
        if ($debug) \sys::logger(sprintf('dao\guid->getUserOf :: %s', $user->name));
        return ($user);
      }
    }

    return (FALSE);
  }

  public function getUser($guid) {
    $debug = false;
    // $debug = true;

    if ($debug) \sys::logger(sprintf('dao\guid->getUser(%s) :: getting user', $guid));

    if ($dto = $this->getByGUID($guid)) { // will add guid if it doesn't exist
      return ($this->getUserOf($dto));
    }

    return (false);
  }
}
