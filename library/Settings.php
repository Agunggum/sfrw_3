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