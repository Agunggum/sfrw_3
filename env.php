<?php
define('ENVIRONMENT', 'local');
define('DEBUG', 'true');

// Menentukan BASEURL secara dinamis untuk memudahkan deploy di berbagai environment termasuk Laravel Herd
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
$scriptPath = str_replace(['/public/index.php', '/index.php'], '', $_SERVER['SCRIPT_NAME']);
$dynamicBaseUrl = $protocol . $domainName . $scriptPath . '/';

define('BASEURL', $dynamicBaseUrl);
define('BASESKIN', 'default'.'/');
define('CONNECTION', 'true'); // true or false lihat juga pada config.txt pada folder library

define('WEBTITLE', 'Sunda Framework - SFRW');
define('WEBTITLETOP', 'SFRW');
define('WEBNAME', 'Sunda Framework - SFRW');
define('MAILTITLE', 'Sunda Framework - SFRW');
define('VERSION', '1.0');
define('TVERSION', '');
define('COPYR', (date('Y')=='2025') ? ''.date('Y'):'2025 - '.date('Y'));

/*****************************************************************/
/* Date timezone
*/
date_default_timezone_set("Asia/Jakarta");
define('DATEWMIN', date("Y-m-d H:i:s"));
/*
/* Expired time
/* waktu sekarang GMT+7
/* waktu timeout (detik)
*/
define('WAKTUINI', time());
define('KADALUARSA', 3600); //7200 detik = 2 jam
/*
/* Pagination
*/
define('PAGINATION', '15');

/*
/* Email connector
*/
define('MAILACTIVATE', 'false');
define('MAILHOST', 'smtp.yourdomain.com');
define('MAILSENT', 'no-reply@yourdomain.id');
define('MAILUSER', 'yourmail@yourdomain.com');
define('MAILPASS', '');
define('MAILPORT', 587);

/* 
/* Autoload and File Extension
*/

define('AUTOLOAD', 'app');
define('EXT', '.php');
define('EXTENSIONLIBRARY', include('library/Library'.EXT));
define('EXTENSIONAUTOLOAD', include('library/Autoload'.EXT));
define('FILECONFIG', 'library/config.txt');