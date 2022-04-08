#!/bin/bash

WD=`pwd`
PORT=8080

cd www
echo "this application is available at http://localhost:$PORT"
php -S localhost:$PORT _mvp.php
cd $WD
