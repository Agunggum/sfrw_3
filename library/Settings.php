<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//koneksi ke database via methode/function yang ada di config.php
if(DBNAME != ''){ db::connectMySQL(BASEPATH); }
// Define page route
define('ROUTE', (isset($_GET['params'])) ? $_GET['params']:"");
/*****************************************************************/
/* Session control
/* Get Users variable
*/
define('BASESESSION', (isset($_SESSION['user'])) ? $_SESSION['user']:"");
define('ACCESSME', (isset($_SESSION['accessme'])) ? $_SESSION['accessme']:"");
/*****************************************************************/
/* Date timezone
*/
date_default_timezone_set("Asia/Jakarta");
define('DATEWMIN', gmdate("Y-m-d H:i:s", time()+60*60*7));