<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

abstract class uploads {
    static function getFile( $f) : string {
        if ( $path= realpath( implode( DIRECTORY_SEPARATOR, [
            config::easydose_upload_dir(),
            $f

        ]))) {

            return $path;

        }

        return '';

    }

    static function Iterator() : FilesystemIterator {
        return new FilesystemIterator( config::easydose_upload_dir());

    }

}