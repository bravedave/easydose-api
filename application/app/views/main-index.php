<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/ ?>

<ul class="menu">
  <li><h4>contents</h4></li>
<?php
  if ( currentUser::isAdmin()) {
    $tpl = '<li><a href="%s">%s</a></li>';

    printf( $tpl, url::tostring('sites'), 'sites');
    printf( $tpl, url::tostring('guid'), 'pharmacy databases');
    printf( $tpl, url::tostring('users'), 'accounts');

    print '<li>&nbsp;</li>';
    print '<li><h4>maintenance</h4></li>';

    printf( $tpl, url::tostring('settings'), 'settings');
    printf( $tpl, url::tostring('products'), 'products');
    if ( sys::useSubscriptions()) {
      printf( $tpl, url::tostring('plans'), 'plans');

    }

    printf( $tpl, url::tostring('payments'), 'payments');
    printf( $tpl, url::tostring('invoices'), 'invoices');

    if ( currentUser::isProgrammer()) {
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
