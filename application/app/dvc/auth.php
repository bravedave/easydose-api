<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dvc;

abstract class auth extends core\auth {
	static function button() {
		if ( auth::GoogleAuthEnabled()) {
			return parent::button();

		}
		else {
			if ( \currentUser::valid()) {
				return ( sprintf( '<a href="%s"><img alt="logout" src="%s" /><img alt="avatar" class="user-avatar" title="%s" src="%s" /><img alt="logout" src="%s" /></a>',
					strings::url( 'logout'),
					strings::url( 'images/logout-left9x50.png'),
					\currentUser::user()->name,
					\currentUser::avatar(),
					strings::url( 'images/logout-63x50.png')
					));

			}
			else {
				return ( sprintf( '<a class="btn" href="%s">logon</a>', strings::url()));

			}

		}

		return ( '');

	}

}
