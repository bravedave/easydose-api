<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

class application extends dvc\application {
	const use_full_url = false;

	static function startDir() {
		return dirname( __DIR__);

	}

}
