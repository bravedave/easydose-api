<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * This work is licensed under a Creative Commons Attribution 4.0 International Public License.
 *      http://creativecommons.org/licenses/by/4.0/
 *
*/

class user extends dvc\user {
    protected $_options = false;

    protected function _optionsConfig() {
        return \config::dataPath() . '/options.json';

    }

    protected function _optionsLoad() {
        $this->_options = (object)[];   // default

        $path = $this->_optionsConfig();
        if ( file_exists( $path)) {
            $this->_options = json_decode( file_get_contents( $path));

        }

    }

    protected function _optionsSave() {
        // sys::logger( sprintf('options save : %s', __METHOD__));
        $path = $this->_optionsConfig();
        if ( file_exists( $path)) {
            unlink( $path);

        }
        file_put_contents( $path, json_encode( $this->_options, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    }

    function option( $key, $value = null) {
        if ( !$this->_options) $this->_optionsLoad();

        $ret = isset( $this->_options->{$key}) ? $this->_options->{$key} : '';

        if ( (string)$value) {
            $this->_options->{$key} = $value;
            $this->_optionsSave();

        }

        return $ret;

	}

}
