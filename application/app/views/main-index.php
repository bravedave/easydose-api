<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/	?>

<ul class="menu">
	<li><h4 class="m-0 mt-2">contents..</h4></li>
	<?php
	if ( currentUser::isAdmin()) {
		$tpl = '<li><a href="%s">%s</a></li>';

		printf( $tpl, url::tostring('sites'), 'sites');
		printf( $tpl, url::tostring('guid'), 'pharmacy databases');
		printf( $tpl, url::tostring('users'), 'accounts');
		printf( '<li class="ml-3"><a href="%s">%s</a></li>', url::tostring('users/due'), 'expiring');

		print '<li><h4 class="m-0 mt-2">mail</h4></li>';
	if ( dvc\mail\config::$ENABLED && dvc\imap\account::$ENABLED) {
		printf( '<li><a href="%s">inbox</li>', strings::url('mail/webmail'));

	}	// if ( dvc\mail\config::$ENABLED)
	printf( '<li><a href="%s">account</li>', strings::url('imap/account'));

		print '<li><h4 class="m-0 mt-2">maintenance</h4></li>';

		printf( $tpl, url::tostring('settings'), 'settings');
		printf( $tpl, url::tostring('products'), 'products');
		if ( sys::useSubscriptions()) {
			printf( $tpl, url::tostring('plans'), 'plans');

		}

		printf( $tpl, url::tostring('payments'), 'payments');
		printf( $tpl, url::tostring('invoices'), 'invoices');

		if ( currentUser::isProgrammer()) {
			printf( $tpl, url::tostring('home/uploads'), 'uploads');
			printf( $tpl, url::tostring('home/dbinfo'), 'dbinfo');
			if ( \config::show_db_reset) {
				printf( $tpl, url::tostring('home/dbreset'), 'db reset');

			}

		}

		if ( config::$DB_TYPE == 'sqlite' ) {
			printf( $tpl, url::tostring('home/dbdownload'), 'db download');

		}

		if ( currentUser::isProgrammer()) {
			printf( $tpl, url::tostring('home/phpinfo'), 'info');
			printf( $tpl, url::tostring('home/mailtest'), 'mailtest');
			print '<li><hr /></li>';
			printf( $tpl, url::tostring('docs'), 'docs');

		}

	} ?>

</ul>
