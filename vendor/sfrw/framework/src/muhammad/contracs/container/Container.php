<?php

// file config
function fileCon($folder) {
    $filecon = $folder."config.txt";
    $file = fopen($filecon,"r");$filedata = fread($file,filesize($filecon));fclose($file);
    return $array = explode(', ',$filedata);
}

// MySql or MySqli
function tables($select = '*', $table, $where, $first){
    $array = fileCon(BASEPATH);
    if($array[4] == 'MySql'){
        mysql_connect($array[0], $array[1], $array[2]);
        mysql_select_db($array[3]);
        if($where==""){
            $query = mysql_query("SELECT $select FROM $table");
        }else{
            $query = mysql_query("SELECT $select FROM $table $where");
        }
        if($first = 'satudata'){
            $data = mysql_fetch_array($query);
            $hasil[] = $data;
        }else{
            while($data = mysql_fetch_array($query)){
                $hasil[] = $data;
            }
        }
    }elseif($array[4] == 'MySqli'){
        $mysqli = new mysqli($array[0], $array[1], $array[2], $array[3]);
        if($where==""){
            $query = $mysqli->query("SELECT $select FROM $table");
        }else{
            $query = $mysqli->query("SELECT $select FROM $table $where");
        }
        if($first = 'satudata'){
            $data = $query->fetch_array(MYSQLI_ASSOC);
            $hasil[] = $data;
        }else{
            while($data = $query->fetch_array(MYSQLI_ASSOC)){
                $hasil[] = $data;
            }
        }
    }
    return $hasil;
}

function permintaanMysql($query){
    $array = fileCon(BASEPATH);
    if($array[4] == "MySql"){
        mysql_connect($array[0], $array[1], $array[2]);
        mysql_select_db($array[3]);
        $result = mysql_query($query);
    }elseif($array[4] == "MySqli"){
        $mysqli = new mysqli($array[0], $array[1], $array[2], $array[3]);
        $result = $mysqli->query($query);
    }
    if ($result) {
        return $result;
    }else{
        $_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj2'] = $query;
        require_once BASEPATH.'error/Handler'.EXT;
    }
}

function mysqlAmbilArray($query){
    $array = fileCon(BASEPATH);
    if($array[4] == "MySql"){
        mysql_connect($array[0], $array[1], $array[2]);
        mysql_select_db($array[3]);
        $result = mysql_fetch_array($query);
    }elseif($array[4] == "MySqli"){
        $mysqli = new mysqli($array[0], $array[1], $array[2], $array[3]);
        $result = $query->fetch_array(MYSQLI_ASSOC);
    }
    return $result;
}

function barisAngkaMysql($query){
    $array = fileCon(BASEPATH);
    if($array[4] == "MySql"){
        mysql_connect($array[0], $array[1], $array[2]);
        mysql_select_db($array[3]);
        $count = mysql_num_rows($query);
    }elseif($array[4] == "MySqli"){
        $mysqli = new mysqli($array[0], $array[1], $array[2], $array[3]);
        $count = $query->num_rows;
    }
    return $count;
}
// end MySql

//Site asset skin
function asset($skin) {
    $base = APPPATH.$skin."";
    if (is_readable($base)) {
        return $base;
    }
}

//Site library error
function errorlib($errorlib) {
    $base = BASEPATH."error/".$errorlib.EXT;
    if (is_readable($base)) {
        return $base;
    }
}

//Site base
function baseweb($get,$skin) {
    $base = APPPATH.$skin."{$get}".EXT;
    if (is_readable($base)) {
        return $base;
    }
}

//Site app
function app($get) {
    $base = "app/".$get.EXT;
    return $base;
    db::closeConnectMySQL(BASEPATH);
}
    
//Site route
function route($get) {
    $base = "web/".$get.EXT;
    if (is_readable($base)) {
        return $base;
    } else {
        return BASEPATH."error/viewnotfound".EXT;
    }
}

//Site route
function routeget($get, $route="", $access="") {
    $arraccess = explode(', ',$access);

    if($get=="" and ($route=="" or $access=="")){
        return "";
    }elseif($get=="/" and $route=="" and $access==""){
        return $get == "/";
    }elseif($get == $route and $access!=""){
        return $get == $route && in_array(ACCESSME, $arraccess);
    }elseif($get == $route and $access==""){
        return $get == $route && $access=="";
    }else{
        return "";
    }
}

//Site storage
function storage() {
    $base = "app/storage/";
    return $base;
    db::closeConnectMySQL(BASEPATH);
}
    
function get($data = []) {
    return $data[0]->$data[1];
}

//Site include mvc/view
function view($get, $data = []) {
    $base = MODPATH."view/{$get}.view".EXT;
    $_REQUEST['errorlogview'] = $base;
    if (is_readable($base)) {
        return $base;
    } else {
        return BASEPATH."error/viewnotfound".EXT;
    }        
}

//Site include mvc/controller
function controller($get) {
    $base = MODPATH."controller/{$get}.controller".EXT;
    $_REQUEST['errorlogclass'] = $base;
    return $base;
}

//Site include mvc/model
function model($get) {
    $base = MODPATH."model/{$get}.model".EXT;
    $_REQUEST['errorlogclass'] = $base;
    return $base;
}

//Site include app/models
function models($get) {
    $base = "app/Models/{$get}".EXT;
    $_REQUEST['errorlogclass'] = $base;
    return $base;
}

//Site include app/vendors
function vendors($get) {
    $base = "vendor/{$get}".EXT;
    $_REQUEST['errorlogclass'] = $base;
    return $base;
}

//flasher
function alert($alert, $title, $message, $redirect = 'javascript:history.go(-1)') {
if($alert=='warning'){
    $_SESSION['alert'] = "<div class='alert alert-danger animated fadeInDown'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button><p class='h6'><em>".anti_injection($title)."</em></p><p class='h6'><strong><i class='fa fa-exclamation-circle'></i> oops..!</strong> ".$message."</p></div>";
    echo "<script>document.location='".$redirect."'</script>";exit();
}
if($alert=='success'){
    $_SESSION['alert'] = "<div class='alert alert-info animated fadeInDown'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button><p class='h6'><em>".anti_injection($title)."</em></p><p class='h6'><strong><i class='fa fa-check'></i> succesfuly!</strong> ".$message."</p></div>";
    echo "<script>document.location='".$redirect."'</script>";exit();
}
}

//flasher static
function alertstatic($alert, $title, $message) {
    if($alert=='warning'){
        return "<div class='alert alert-danger animated fadeInDown'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button><p class='h6'><em>".anti_injection($title)."</em></p><p class='h6'><strong><i class='fa fa-exclamation-circle'></i> oops..!</strong> ".anti_injection($message)."</p></div>";
    }
    if($alert=='success'){
        return "<div class='alert alert-info animated fadeInDown'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button><p class='h6'><em>".anti_injection($title)."</em></p><p class='h6'><strong><i class='fa fa-check'></i> succesfuly!</strong> ".anti_injection($message)."</p></div>";
    }
}

function arahkan($redirect = 'javascript:history.go(-1)', $func = '0', $message = '0') {
    if($func != '0'){ $_SESSION[$func] = $func; }
    if($message != '0'){ $_SESSION['message'] = $message; }
    $direct = "<script>setTimeout(function () { window.location.href = '".$redirect."'; }, 1000);</script>";
    echo $direct;
}
//end flasher

//Replace stripe to space On title page
function explodetopageTitle($get) {
    $base = str_replace("-", " ", $get);
    if(empty($base)){
        return "Home";
    }else{
        return ucfirst($base);
    }
}

//Replace title news to url
function replacetourl($get) {
    $base = str_replace(" ", "-", $get);
    return $base;
}

//Replace stripe to space
function replacetospace($get) {
    $base = str_replace("-", " ", $get);
    return $base;
}

//Replace stripe to space by explode
function explodetopage($get) {
    $array = explode('-', $get);
    $base = $array[0];
    return $base;
}

//Replace stripe to space by explode
function explodetoid($get) {
    $array = explode('-', $get);
    $base = $array[1];
    return $base;
}

//Replace / to 2F% and : to %3A
function replacetoshare($get) {
    $base = str_replace("/", "2F%", $get);
    $base = str_replace(":", "%3A", $base);
    return $base;
}

//Replace space to %20
function replacetotweet($get) {
    $base = str_replace(" ", "%20", $get);
    return $base;
}

//Readmore news
function readmore($get,$id) {
    if($id=='0'){
        $base = substr($get,0,1000)." [...]";
    }else{
        $base = $get;
    }
    return $base;
}

//Readmore
function readmoreins($get) {
    $base = substr($get,0,100)." [...]";
    return $base;
}
/* End of file Container.php */
