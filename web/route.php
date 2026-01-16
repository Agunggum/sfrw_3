<?php
if ( ! 'web') exit('No direct script access allowed');
extract($_GET);
/*
*----------------------------------------------------------------------
 * SFRW Framework Version 3.0
 * 
 * Contoh penggunaan session untuk mengatur halaman yang diakses memakai halaman login
if(BASESESSION==""){
    if(routeget('forgot-password', ROUTE)){
        require_once view('forgot-password');
    }else{
        require_once view('login');
    }
}else{
    //jika waktu sekarang kurang dari sesi timeout
    if(WAKTUINI < $_SESSION['timeout'])
    {
        //hapus sesi timeout yang lama ,buat sesi timeout yang baru
        unset($_SESSION['timeout']);
        $_SESSION['timeout']=WAKTUINI+KADALUARSA;

        require_once view('index');
    }else{
        require_once view('endsession');
    }
}
 *
 *---------------------------------------------------------------------
 *
*/
/*
  *route berfungsi untuk memanage halaman dan konten
*/      
if(routeget('/', ROUTE)){
  return Indexcontroller::index();
}else

if(routeget('login', ROUTE)){
  require_once view('login');
}else

if(routeget('forgot-password', ROUTE)){
  require_once view('forgot-password');
}else

if(routeget('datatable', ROUTE)){
  require_once view('table');
}else

if(routeget('signout', ROUTE)){
  require_once view('signout');
}else

if(routeget('logs-', ROUTE)){
  /* cara akses logs gunakan route logs-&file=[nama file di folder logs. contoh: error] */
  require_once vendors('logcarbon/logcarbon');
  require_once view('logs', [
    $data['title'] = "Logs",
    $data['breadcrumb'] = "Logs",
    $data['icon'] = "fa fa-logs",
  ]);
}else

if(routeget('logsfiles', ROUTE)){
  require_once view('logsfiles');
}else

{
  customErrorHandler(); // not found route
}

/* End of file route.php */
/* Location: ./web/route.php */
