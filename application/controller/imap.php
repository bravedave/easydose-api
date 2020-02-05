<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * This work is licensed under a Creative Commons Attribution 4.0 International Public License.
 * 		http://creativecommons.org/licenses/by/4.0/
 *
 * */

use dvc\imap\account;
use dvc\mail\credentials;

class imap extends dvc\imap\controller {
	protected function before() {
		parent::before();

        /**
         * in the development environment this
         * establishes a local account
         *
         * use this area to establish an account
         *
         */

	}

	protected function postHandler() {
		$action = $this->getPost('action');

		if ( 'delete-profile' == $action) {
			if ( $profile = $this->getPost('profile')) {
				if ( $profile_config = account::profile( $profile)) {
					if ( file_exists( $profile_config)) {
						unlink( $profile_config);
						\Json::ack( $action);

					} else { \Json::nak( sprintf( 'profile %s not found : %s', $profile_config, $action)); }

				} else { \Json::nak( sprintf( 'invalid profile name : %s', $action)); }

			} else { \Json::nak( $action); }

		}
		elseif ( 'load-profile' == $action) {
			if ( $profile = $this->getPost('profile')) {
				if ( $profile_config = account::profile( $profile)) {
					if ( file_exists( $profile_config)) {
						$config = account::config();
						if ( file_exists( $config)) unlink( $config);

						if ( copy( $profile_config, $config)) {
							// \sys::logger( sprintf('<load profile : %s> %s', $profile, __METHOD__));
							\Json::ack( $action);

						} else { \Json::nak( sprintf( 'failed to copy profile to default : %s', $action)); }

					} else { \Json::nak( sprintf( 'profile %s not found : %s', $profile_config, $action)); }

				} else { \Json::nak( sprintf( 'invalid profile name : %s', $action)); }

			} else { \Json::nak( $action); }

		}
		elseif ( 'save-account' == $action) {
			// \sys::dump( $this->getPost());
			$a = (object)[
				'server' => $this->getPost('server'),
				'name' => $this->getPost('name'),
				'email' => $this->getPost('email'),
				'username' => $this->getPost('username'),
				'password' => $this->getPost('password'),
				'type' => $this->getPost('type'),
				'profile' => $this->getPost('profile'),

			];

			// sys::dump( $a);

			if ( !trim( $a->password, '- ')) {
				$a->password = account::$PASSWORD;

			}

			if ( $a->password) $a->password = bCrypt::crypt( $a->password);

			$config = account::config();

			if ( file_exists( $config)) unlink( $config);
			file_put_contents( $config, json_encode( $a, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

			if ( '' != $a->profile && 'default' != $a->profile) {
				if ( $profile_config = account::profile( $a->profile)) {
					if ( file_exists( $profile_config)) unlink( $profile_config);
					file_put_contents( $profile_config, json_encode( $a, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

				}

			}

			// sys::dump( $a, $config);

			Response::redirect( strings::url( $this->route));

		}
        else { parent::postHandler(); }

	}

	public function account() {
		$this->data = (object)[
			'account' => (object)[
				'server' => account::$SERVER,
				'type' => account::$TYPE,
				'name' => account::$NAME,
				'email' => account::$EMAIL,
				'username' => account::$USERNAME,
				'password' => account::$PASSWORD,
				'profile' => account::$PROFILE,

			]

		];

		$this->render([
			'title' => $this->title = 'Account Settings',
			'primary' => 'account',
			'secondary' => ['index']

		]);

	}

}
