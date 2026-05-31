<?php
// Set working directory ke root project agar autoloading tetap berjalan dengan benar
chdir(__DIR__ . '/../');

require_once 'env.php';
/*
*---------------------------------------------------------------
* SESSION START
*---------------------------------------------------------------
*/
ob_start();
session_start();
// The PHP file extension
EXTENSIONLIBRARY;
EXTENSIONAUTOLOAD;
/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
*/
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
/* End of file index.php */
/* Location: ./index.php */
