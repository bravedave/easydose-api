<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/
class page extends dvc\pages\bootstrap {
  function __construct( $title = '' ) {
    self::$momentJS = TRUE;

    parent::__construct( $title);

    $this->latescripts[] = sprintf( '<script type="text/javascript" src="%s"></script>', \url::tostring( 'edjs'));
    $this->css[] = sprintf( '<link type="text/css" rel="stylesheet" media="all" href="%s" />', \url::tostring( 'css/easydose.css'));

  }

  public function primary10( $class = NULL) {
    if ( is_null( $class))
      $class =  'col-sm-8 col-md-10 pt-3 pb-4';

    return ( parent::primary( $class));	// chain

  }

  public function secondary2( $class = NULL) {
    if ( is_null( $class))
      $class =  'col-sm-4 col-md-2 pt-3 pb-4 bg-light d-print-none';

    return ( parent::secondary( $class));	// chain

  }

}
