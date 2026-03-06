<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//koneksi ke database via methode/function yang ada di config.php
if(CONNECTION == 'true'){ db::connectMySQL(BASEPATH); }
// Define page route - mendukung penulisan URL via params (Apache) atau REQUEST_URI (Nginx/Laravel Herd)
if (isset($_GET['params'])) {
    $route = $_GET['params'];
} else {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $scriptName = str_replace(['/public/index.php', '/index.php'], '', $_SERVER['SCRIPT_NAME']);
    $route = str_replace($scriptName, '', $uri);
    $route = str_replace('/public/', '', $route);
    $route = ltrim($route, '/');
}
define('ROUTE', $route);
/*****************************************************************/
/* Session control
/* Get Users variable
*/
define('BASESESSION', (isset($_SESSION['username'])) ? $_SESSION['username']:"");
define('ACCESSME', (isset($_SESSION['accessme'])) ? $_SESSION['accessme']:"");