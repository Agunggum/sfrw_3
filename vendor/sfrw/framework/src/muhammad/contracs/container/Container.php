<?php

// file config
function fileCon($folder) {
    $filecon = $folder."config.txt";
    $file = fopen($filecon,"r");$filedata = fread($file,filesize($filecon));fclose($file);
    return $array = explode(', ',$filedata);
}

// MySql or MySqli
function tables($select = '*', $table = "master_users", $where = "", $first = 'all'){
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

function ambilJson($query){
    $array = fileCon(BASEPATH);
    if($array[4] == "MySql"){
        return "MySql tidak mendukung format json";
    }elseif($array[4] == "MySqli"){
        $data = array(); // Array kosong untuk menyimpan data
        while ($row = mysqli_fetch_assoc($query)) {
            $data[] = $row; // Tambahkan setiap baris ke array $data
        }
        return json_encode($data, JSON_PRETTY_PRINT);
    }
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

//form
function forminput($data = [], $bootstrap = []) {
    $typeform = (isset($data[0])) ? strtolower($data[0]):'text';
    $nameform = (isset($data[1])) ? $data[1]:'name';
    $sesiform = (isset($data[1])) ? $data[1].'_form':'name_form';
    $valueform = (isset($_SESSION[$sesiform])) ? $_SESSION[$sesiform]:'';
    $idform = (isset($data[2])) ? $data[2]:'id';
    $placeholderform = (isset($data[3])) ? $data[3]:'';
    $autocompleteform = (isset($data[4])) ? $data[4]:'off';
    $requiredform = (isset($data[5]) and $data[5] == 'required') ? $data[5]:'';
    $maxlength = ($typeform=="password") ? 'minlength="8"':'';

    $bootstrapgroup = (isset($bootstrap[0])) ? $bootstrap[0]:'';
    $bootstrapgroupalign = (isset($bootstrap[1])) ? $bootstrap[1]:'right';
    $bootstrapgroupicon = (isset($bootstrap[2])) ? $bootstrap[2]:'';

    if($bootstrapgroup=='group'){
        if($bootstrapgroupalign=='left'){
            $input = '<div class="input-group">'.$bootstrapgroupicon.'<input type="'.$typeform.'" name="'.$nameform.'" id="'.$idform.'" value="'.$valueform.'" class="form-control" placeholder="'.$placeholderform.'" autocomplete="'.$autocompleteform.'" '.$maxlength.' '.$requiredform.' /></div>';
        }else{
            $input = '<div class="input-group"><input type="'.$typeform.'" name="'.$nameform.'" id="'.$idform.'" value="'.$valueform.'" class="form-control" placeholder="'.$placeholderform.'" autocomplete="'.$autocompleteform.'" '.$maxlength.' '.$requiredform.' />'.$bootstrapgroupicon.'</div>';
        }
    }else{
        $input = '<input type="'.$typeform.'" name="'.$nameform.'" id="'.$idform.'" value="'.$valueform.'" class="form-control" placeholder="'.$placeholderform.'" autocomplete="'.$autocompleteform.'" '.$maxlength.' '.$requiredform.' />';
    }

    return '<div class="mb-4 text-left">'.$input.'</div>';
}

function formselect($select = [], $data = [], $bootstrap = []) {
    $nameform = (isset($data[0])) ? $data[0]:'name';
    $idform = (isset($data[1])) ? $data[1]:'id';
    $placeholderform = (isset($data[2])) ? $data[2]:'';
    $requiredform = (isset($data[3]) and $data[3] == 'required') ? $data[3]:'';

    $bootstrapgroup = (isset($bootstrap[0])) ? $bootstrap[0]:'';
    $bootstrapgroupalign = (isset($bootstrap[1])) ? $bootstrap[1]:'right';
    $bootstrapgroupicon = (isset($bootstrap[2])) ? $bootstrap[2]:'';

    if($bootstrapgroup=='group'){
        if($bootstrapgroupalign=='left'){
            $input = '<div class="input-group">'.$bootstrapgroupicon.'<div class="form-floating"><select name="'.$nameform.'" id="'.$idform.'" class="form-select" '.$requiredform.'">';
            foreach ($select as $selects) {
                $input .= '<option>'.$selects.'</option>';
            }
            $input .= '</select>';
            $input .= '<label for="'.$idform.'">'.$placeholderform.'</label></div></div>';
        }else{
            $input = '<div class="input-group"><div class="form-floating"><select name="'.$nameform.'" id="'.$idform.'" class="form-select" '.$requiredform.'">';
            foreach ($select as $selects) {
                $input .= '<option>'.$selects.'</option>';
            }
            $input .= '</select>';
            $input .= '<label for="'.$idform.'">'.$placeholderform.'</label></div>'.$bootstrapgroupicon.'</div>';
        }
    }else{
        $input = '<div class="form-floating"><select name="'.$nameform.'" id="'.$idform.'" class="form-select" '.$requiredform.'">';
        foreach ($select as $selects) {
            $input .= '<option>'.$selects.'</option>';
        }
        $input .= '</select>';
        $input .= '<label for="'.$idform.'">'.$placeholderform.'</label></div>';
    }

    return '<div class="mb-4 text-left">'.$input.'</div>';
}

function formradio($select = [], $data = [], $bootstrap = []) {
    $nameform = (isset($data[0])) ? $data[0]:'name';
    $requiredform = (isset($data[1]) and $data[1] == 'required') ? $data[1]:'';

    $bootstrapgroup = (isset($bootstrap[0])) ? $bootstrap[0]:'';
    $bootstrapgroupalign = (isset($bootstrap[1])) ? $bootstrap[1]:'right';
    $bootstrapgroupicon = (isset($bootstrap[2])) ? $bootstrap[2]:'';

    $input = '';
    if($bootstrapgroup=='group'){
        if($bootstrapgroupalign=='left'){
            foreach ($select as $selects) {
                $input .= '<div class="input-group">'.$bootstrapgroupicon.'<div class="form-check"><input class="form-check-input" type="radio" name="'.$nameform.'" id="'.strtolower(str_replace(" ", "", $selects)).'" value="'.$selects.'" '.$requiredform.'><label class="form-check-label" for="'.str_replace(" ", "", $selects).'">'.$selects.'</label></div></div>';
            }
        }else{
            foreach ($select as $selects) {
                $input .= '<div class="input-group"><div class="form-check"><input class="form-check-input" type="radio" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'" value="'.$selects.'" '.$requiredform.'><label class="form-check-label" for="'.str_replace(" ", "", $selects).'">'.$selects.'</label></div>'.$bootstrapgroupicon.'</div>';
            }
        }
    }else{
        foreach ($select as $selects) {
            $input .= '<div class="form-check"><input class="form-check-input" type="radio" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'" value="'.$selects.'" '.$requiredform.'><label class="form-check-label" for="'.str_replace(" ", "", $selects).'">'.$selects.'</label></div>';
        }
    }

    return '<div class="mb-4 text-left">'.$input.'</div>';
}

function formcheck($select = [], $data = [], $bootstrap = []) {
    $nameform = (isset($data[0])) ? $data[0]:'name';
    $requiredform = (isset($data[1]) and $data[1] == 'required') ? $data[1]:'';

    $bootstrapgroup = (isset($bootstrap[0])) ? $bootstrap[0]:'';
    $bootstrapgroupalign = (isset($bootstrap[1])) ? $bootstrap[1]:'right';
    $bootstrapgroupicon = (isset($bootstrap[2])) ? $bootstrap[2]:'';

    $input = '';
    if($bootstrapgroup=='group'){
        if($bootstrapgroupalign=='left'){
            foreach ($select as $selects) {
                $input .= '<div class="input-group">'.$bootstrapgroupicon.'<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$selects.'" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'"><label class="form-check-label" for="'.str_replace(" ", "", $selects).'">'.$selects.'</label></div></div>';
            }
        }else{
            foreach ($select as $selects) {
                $input .= '<div class="input-group"><div class="form-check"><input class="form-check-input" type="checkbox" value="'.$selects.'" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'"><label class="form-check-label" for="'.str_replace(" ", "", $selects).'">'.$selects.'</label></div>'.$bootstrapgroupicon.'</div>';
            }
        }
    }else{
        foreach ($select as $selects) {
            $input .= '<div class="form-check"><input class="form-check-input" type="checkbox" value="'.$selects.'" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'"><label class="form-check-label" for="'.str_replace(" ", "", $selects).'">'.$selects.'</label></div>';
        }
    }

    return '<div class="mb-4 text-left">'.$input.'</div>';
}
/* End of file Container.php */
