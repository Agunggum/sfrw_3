<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//koneksi ke database via methode/function yang ada di config.php
if(CONNECTION == 'true'){ db::connectMySQL(BASEPATH); }
// Define page route
define('ROUTE', (isset($_GET['params'])) ? $_GET['params']:"");
/*****************************************************************/
/* Session control
/* Get Users variable
*/
define('BASESESSION', (isset($_SESSION['username'])) ? $_SESSION['username']:"");
define('ACCESSME', (isset($_SESSION['accessme'])) ? $_SESSION['accessme']:"");