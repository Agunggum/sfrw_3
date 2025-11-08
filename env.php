<?php
define('ENVIRONMENT', 'local');
define('DEBUG', 'true');
define('BASEURL', 'http://localhost/sfrw_3/');
define('BASESKIN', 'default'.'/');
define('DBNAME', '');

define('WEBTITLE', 'Sunda Framework - SFRW');
define('WEBTITLETOP', 'SFRW');
define('WEBNAME', 'Sunda Framework - SFRW');
define('MAILTITLE', 'Sunda Framework - SFRW');
define('VERSION', '1.0');
define('TVERSION', '');
if(date('Y')=='2025'){ define('COPYR', ''.date('Y')); }else{ define('COPYR', '2025 - '.date('Y')); }

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
define('MAILUSER', 'yourmail@yourdomain.com');
define('MAILPASS', '');
define('MAILPORT', '587');

/* 
/* Autoload and File Extension
*/

define('AUTOLOAD', 'app');
define('EXT', '.php');
define('EXTENSIONLIBRARY', include('library/Library'.EXT));
define('EXTENSIONAUTOLOAD', include('library/Autoload'.EXT));
define('FILECONFIG', 'library/config.txt');