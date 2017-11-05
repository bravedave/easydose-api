<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
NameSpace dvc;

abstract class auth extends _auth {
	static function button() {
		if ( auth::GoogleAuthEnabled()) {
			return parent::button();

		}
		else {
			if ( currentUser::valid()) {
				return ( sprintf( '<a href="%s"><img alt="logout" src="%s" /><img alt="avatar" class="user-avatar" title="%s" src="%s" /><img alt="logout" src="%s" /></a>',
					url::tostring( 'logout'),
					url::tostring( 'images/logout-left9x54.png'),
					currentUser::user()->name,
					currentUser::avatar(),
					url::tostring( 'images/logout-63x54.png')
					));

			}
			else {
				return ( sprintf( '<a class="btn" href="%s">logon</a>', url::tostring()));

			}

		}

		return ( '');

	}

}
