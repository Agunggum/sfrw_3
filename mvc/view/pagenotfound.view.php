<?php if ( ! defined('APPPATH')) exit('No direct script access allowed');
$dates = gmdate("Y-m-d H:i:s", time()+60*60*7);
$_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj'] = "E-ROUTE-404";
$_SESSION['zyA2QF2M25e3TyVmi2w99n2tB'] = "Route not found: ".ROUTE;
$_SESSION['6vhow83GCbV6jdXTMEgAJdqEN'] = "0".", "."Route not found: ".ROUTE.", , ".$_SERVER['REQUEST_URI'].", ".$dates;
if($_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj']=='E-ROUTE-404'){
echo "<script>setTimeout(function () { document.location='".$_SERVER['REQUEST_URI']."'; }, 2);</script>";
}
?>
