<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

$autoload = __DIR__ . '/../vendor/bravedave/dvc/autoloader.php';

if ( file_exists( $autoload))
	require_once $autoload;

else
	throw new Exception( 'Unable to locate autoloader');

spl_autoload_register(function ($class) {
	if ( $lib = realpath( __DIR__ . '/app/' . str_replace('\\', '/', $class) . '.php')) {
		include_once $lib;
		dvc\core\load::logger( sprintf( 'app: %s', $lib ));

		return ( true);

	}

	return ( false);

}, true, true);

