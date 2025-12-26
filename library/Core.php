<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*----------------------------------------------------------------------
 * SFRW Framework Version 3.0
 *---------------------------------------------------------------------
*
*/
// Config file
require_once BASEPATH."Config".EXT;
// Settings file
require_once BASEPATH."Settings".EXT;
/*
 *---------------------------------------------------------------
 * REDIRECT TO APP
 *---------------------------------------------------------------
 *
*/
require_once app(AUTOLOAD);
/* End Alert */
if(isset($_SESSION['alert'])){ $_SESSION['alert']=""; }
/* End Connection */
if(DBNAME != ''){ db::closeConnectMySQL(BASEPATH,'config'); }
/* End of file core.php */
/* Location: ./library/core.php */
