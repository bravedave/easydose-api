<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

Namespace dao;

class users extends _dao {
	protected $_db_name = 'users';

	function getUserByUserName( $username ) {
		if ( (string)$username) {
			if ( $res = $this->Result( sprintf( "SELECT * FROM users WHERE username = '%s'", $this->escape( $username))))
				return $res->dto();

		}

		return ( FALSE);

	}

	function getUserByEmail( $email) {
		if ( \strings::IsEmailAddress( $email)) {
			if ( $res = $this->Result( sprintf( "SELECT * FROM users WHERE `email` = '%s'", $this->escape( $email))))
				return $res->dto();

		}

		return ( FALSE);

	}

	function validate( $u, $p ) {

		if ( $u && $p) {
			$dto = FALSE;
			if ( \strings::IsEmailAddress( $u))
				$dto = $this->getUserByEmail( $u);
			else
				$dto = $this->getUserByUserName( $u);

			if ( $dto) {
				if ( password_verify( $p, $dto->pass)) {
					\dvc\session::edit();
					\dvc\session::set('uid', $dto->id);
					\dvc\session::close();
					return ( TRUE);

				}

			}

		}

		return ( FALSE);

	}

}
