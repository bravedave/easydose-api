<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/

namespace exceptions;

class ResetDisabled extends Exception {
	protected $_text = 'Reset is Disabled - you have to activate it if you need it';

}
