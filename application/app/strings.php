<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/
	*/

abstract class strings extends dvc\strings {
  static function getGUID(){
    mt_srand( (double)microtime() * 10000);  //optional for php 4.2.0 and up.
    $charid = strtoupper(md5(uniqid(rand(), true)));
    $hyphen = chr(45);  // "-"
    $uuid = chr(123)    // "{"
      .substr($charid, 0, 8).$hyphen
      .substr($charid, 8, 4).$hyphen
      .substr($charid,12, 4).$hyphen
      .substr($charid,16, 4).$hyphen
      .substr($charid,20,12)
      .chr(125);        // "}"

    return $uuid;

  }

  static function ShortLicense( $license) {
    if ( 'easydoseOPEN' == $license)
      return 'OPEN';

    if ( 'easydose10' == $license)
      return 'E-10';

    if ( 'easydose2' == $license)
      return 'E-5';

    if ( 'easydoseFREE' == $license)
      return 'FREE';

    return ( $license);

  }

  static function StringToOS( $osIn) {
    if ( preg_match( '@^Microsoft Windows XP@', $osIn )) {
      return 'WinXP';

    }
    elseif ( preg_match( '@^Microsoft Windows \[Version 6.1.7601\]@', $osIn )) {
      return 'Win7/2008 SP1';

    }
    elseif ( preg_match( '@^Microsoft Windows \[Version 6.2.9200\]@', $osIn )) {
      return 'Win8/2012';

    }
    elseif ( preg_match( '@^Microsoft Windows \[Version 6.3.9200\]@', $osIn )) {
      return 'Win8.1/2012 R2';

    }
    elseif ( preg_match( '@^Microsoft Windows \[Version 6.3.9600\]@', $osIn )) {
      return 'Win8.1 U1/2012';

    }
    elseif ( preg_match( '@^Microsoft Windows \[Version 10@', $osIn )) {
      return 'Win10';

    }
    else {
      return $osIn;

    }

  }

}
