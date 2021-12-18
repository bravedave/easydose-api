#!/bin/bash

WD=`pwd`
PORT=$[RANDOM%1000+1024]
PORT=8057
php=php
if [[ -x /usr/bin/php8 ]]; then php=php8; fi

cd www
echo "this application is available at http://localhost:$PORT"
$php -S localhost:$PORT _dvc.php
cd $WD
