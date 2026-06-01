<?php

// Optimasi Refresh: Bersihkan trigger error lama di awal setiap request
// Ini mencegah error dari request sebelumnya muncul kembali saat halaman di-refresh
if (isset($_SESSION)) {
    unset($_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj'], $_SESSION['zyA2QF2M25e3TyVmi2w99n2tB'], $_SESSION['6vhow83GCbV6jdXTMEgAJdqEN'], $_SESSION['error_data']);
}

// file rute
if(defined('BASEPATH')){
    require_once BASEPATH . 'Rute' . EXT;
} else {
    require_once __DIR__ . '/../../../../../../../library/Rute.php';
}

// Koneksi Database Global untuk fungsi-fungsi di Container.php
$GLOBALS['sfrw_db_conn'] = null;

function get_db_conn() {
    if ($GLOBALS['sfrw_db_conn'] === null) {
        $GLOBALS['sfrw_db_conn'] = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($GLOBALS['sfrw_db_conn']->connect_error) {
            die("Koneksi database gagal: " . $GLOBALS['sfrw_db_conn']->connect_error);
        }
    }
    return $GLOBALS['sfrw_db_conn'];
}

function ambilJson($query){
    if (!($query instanceof mysqli_result)) {
        return json_encode(["error" => "Invalid query result"]);
    }
    $data = array();
    while ($row = $query->fetch_assoc()) {
        $data[] = $row;
    }
    return json_encode($data, JSON_PRETTY_PRINT);
}

function barisAngkaMysql($query){
    if ($query instanceof mysqli_result) {
        return $query->num_rows;
    }
    return 0;
}
// end database

//Site asset public
function asset($path) {
    return BASEURL.''. $path;
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

function is_ajax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') || isset($_GET['ajax']);
}

// Helper untuk meneruskan data PHP ke Lit-HTML (JavaScript)
function pass_to_js($data, $var_name = 'pageData') {
    $json = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    return "<script>window.{$var_name} = {$json};</script>";
}

//Site include mvc/view
function view($get, $data = []) {
    // Definisikan konstanta AJAX untuk digunakan di view
    if (!defined('IS_AJAX')) {
        define('IS_AJAX', is_ajax());
    }

    $base = MODPATH."view/{$get}.view".EXT;
    $_REQUEST['errorlogview'] = $base;
    return $base;      
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

//Site include app/services
function services($get) {
    $base = "app/Services/{$get}".EXT;
    $_REQUEST['errorlogclass'] = $base;
    return $base;
}

//flasher
function alert($alert, $title, $message, $redirect = 'javascript:history.go(-1)') {
    $alert_html = "";
    if($alert=='warning'){
        $alert_html = "<div class='alert alert-danger animated fadeInDown'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button><p><em><i class='bi bi-exclamation-diamond'></i> ".anti_injection($title)."</em></p><p>".$message."</p></div>";
    } elseif($alert=='success'){
        $alert_html = "<div class='alert alert-info animated fadeInDown'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button><p><em><i class='bi bi-check-circle'></i> ".anti_injection($title)."</em></p><p>".$message."</p></div>";
    }

    $_SESSION['alert'] = $alert_html;

    if (defined('IS_AJAX') && IS_AJAX) {
        header('X-SPA-Redirect: ' . $redirect);
        exit();
    } else {
        echo "<script>document.location='".$redirect."'</script>";
        exit();
    }
}

//flasher static
function alertstatic($alert, $title, $message) {
    if($alert=='warning'){
        return "<div class='alert alert-danger animated fadeInDown'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button><p><em>".anti_injection($title)."</em></p><p>".anti_injection($message)."</p></div>";
    }
    if($alert=='success'){
        return "<div class='alert alert-info animated fadeInDown'><button aria-hidden='true' data-dismiss='alert' class='close' type='button'>×</button><p><em>".anti_injection($title)."</em></p><p>".anti_injection($message)."</p></div>";
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
    $requiredform = (isset($data[5])) ? $data[5]:'';

    $bootstrapgroup = (isset($bootstrap[0])) ? $bootstrap[0]:'';
    $bootstrapgroupalign = (isset($bootstrap[1])) ? $bootstrap[1]:'right';
    $bootstrapgroupicon = (isset($bootstrap[2])) ? $bootstrap[2]:'';

    if($bootstrapgroup=='group'){
        if($bootstrapgroupalign=='left'){
            $input = '<div class="input-group rounded-3">'.$bootstrapgroupicon.'<input type="'.$typeform.'" name="'.$nameform.'" id="'.$idform.'" value="'.$valueform.'" class="form-control focus-ring focus-ring-danger rounded-end-3" placeholder="'.$placeholderform.'" autocomplete="'.$autocompleteform.'" '.$requiredform.' /></div>';
        }else{
            $input = '<div class="input-group rounded-3"><input type="'.$typeform.'" name="'.$nameform.'" id="'.$idform.'" value="'.$valueform.'" class="form-control focus-ring focus-ring-danger rounded-start-3" placeholder="'.$placeholderform.'" autocomplete="'.$autocompleteform.'" '.$requiredform.' />'.$bootstrapgroupicon.'</div>';
        }
    }else{
        $input = '<input type="'.$typeform.'" name="'.$nameform.'" id="'.$idform.'" value="'.$valueform.'" class="form-control focus-ring focus-ring-danger rounded-3" placeholder="'.$placeholderform.'" autocomplete="'.$autocompleteform.'" '.$requiredform.' />';
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
            $input = '<div class="input-group rounded-3">'.$bootstrapgroupicon.'<div class="form-floating"><select name="'.$nameform.'" id="'.$idform.'" class="form-select focus-ring focus-ring-danger rounded-end-3" '.$requiredform.'">';
            foreach ($select as $selects) {
                $input .= '<option>'.$selects.'</option>';
            }
            $input .= '</select>';
            $input .= '<label for="'.$idform.'">'.$placeholderform.'</label></div></div>';
        }else{
            $input = '<div class="input-group rounded-3"><div class="form-floating"><select name="'.$nameform.'" id="'.$idform.'" class="form-select focus-ring focus-ring-danger rounded-start-3" '.$requiredform.'">';
            foreach ($select as $selects) {
                $input .= '<option>'.$selects.'</option>';
            }
            $input .= '</select>';
            $input .= '<label for="'.$idform.'">'.$placeholderform.'</label></div>'.$bootstrapgroupicon.'</div>';
        }
    }else{
        $input = '<div class="form-floating"><select name="'.$nameform.'" id="'.$idform.'" class="form-select focus-ring focus-ring-danger rounded-3" '.$requiredform.'">';
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
                $input .= '<div class="input-group rounded-3">'.$bootstrapgroupicon.'<div class="form-check"><input class="form-check-input focus-ring focus-ring-danger" type="radio" name="'.$nameform.'" id="'.strtolower(str_replace(" ", "", $selects)).'" value="'.$selects.'" '.$requiredform.'><label class="form-check-label" for="'.str_replace(" ", "", $selects).'">'.$selects.'</label></div></div>';
            }
        }else{
            foreach ($select as $selects) {
                $input .= '<div class="input-group rounded-3"><div class="form-check"><input class="form-check-input focus-ring focus-ring-danger" type="radio" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'" value="'.$selects.'" '.$requiredform.'><label class="form-check-label" for="'.str_replace(" ", "", $selects).'">'.$selects.'</label></div>'.$bootstrapgroupicon.'</div>';
            }
        }
    }else{
        foreach ($select as $selects) {
            $input .= '<div class="form-check"><input class="form-check-input focus-ring focus-ring-danger" type="radio" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'" value="'.$selects.'" '.$requiredform.'><label class="form-check-label" for="'.str_replace(" ", "", $selects).'">'.$selects.'</label></div>';
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
                $valueselect = strtolower($selects);
                $valueselect = str_replace(" ", "-", $valueselect);
                $input .= '<div class="input-group rounded-3">'.$bootstrapgroupicon.'<div class="form-check"><input class="form-check-input focus-ring focus-ring-danger" type="checkbox" value="'.$selects.'" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'"><label class="form-check-label" for="'.str_replace(" ", "", $selects).'"><span id="id-'.$valueselect.'" class="title-class" data-lang-id="id-'.$valueselect.'">'.$selects.'</span></label></div></div>';
            }
        }else{
            foreach ($select as $selects) {
                $valueselect = strtolower($selects);
                $valueselect = str_replace(" ", "-", $valueselect);
                $input .= '<div class="input-group rounded-3"><div class="form-check"><input class="form-check-input focus-ring focus-ring-danger" type="checkbox" value="'.$selects.'" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'"><label class="form-check-label" for="'.str_replace(" ", "", $selects).'"><span id="id-'.$valueselect.'" class="title-class" data-lang-id="id-'.$valueselect.'">'.$selects.'</span></label></div>'.$bootstrapgroupicon.'</div>';
            }
        }
    }else{
        foreach ($select as $selects) {
            $valueselect = strtolower($selects);
            $valueselect = str_replace(" ", "-", $valueselect);
            $input .= '<div class="form-check"><input class="form-check-input focus-ring focus-ring-danger" type="checkbox" value="'.$selects.'" name="'.$nameform.'" id="'.str_replace(" ", "", $selects).'"><label class="form-check-label" for="'.str_replace(" ", "", $selects).'"><span id="id-'.$valueselect.'" class="title-class" data-lang-id="id-'.$valueselect.'">'.$selects.'</span></label></div>';
        }
    }

    return '<div class="mb-4 text-left">'.$input.'</div>';
}

// Helper functions from Library.php
function tampilan($get, $data = []) {
    return view($get, $data);
}

function pengendali($get) {
    return controller($get);
}

function model_data($get) {
    return model($get);
}

function alihkan($url) {
    header("Location: " . $url);
    exit();
}

function validasi_tanggal($date, $format = 'Y-m-d'){
    return validateDate($date, $format);
}

function format_uang($angka) {
    return format_angka2($angka);
}

function terbilang_indonesia($x) {
    return terbilang($x);
}

function sesi($key, $value = null) {
    if ($value === null) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    $_SESSION[$key] = $value;
}

function hapus_sesi($key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

function rute_saat_ini() {
    return ROUTE;
}

function permintaan() {
    return $_REQUEST;
}

function kiriman() {
    return $_POST;
}

function dapatkan_data() {
    return $_GET;
}

function customError($errno, $errstr, $errfile, $errline) {
    // Abaikan error jika error reporting dimatikan (misal dengan @)
    if (!(error_reporting() & $errno)) {
        return false;
    }

    $dates = date("Y-m-d H:i:s");
    $lognote = "Error [{$errno}] :: Line {$errline} :: {$errstr} :: {$errfile} :: {$_SERVER['REQUEST_URI']}";
    
    // Logging ke file
    $dataToLog = array(
        $dates,
        get_client_ip(),
        get_client_browser(),
        $lognote,
    );
    $data = implode(" ~ ", $dataToLog) . PHP_EOL;
    
    $directory = "logs/";
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    file_put_contents($directory . 'error.log', $data, FILE_APPEND);

    // Simpan ke session untuk ditampilkan oleh Handler.php
    $_SESSION['error_data'] = [
        'errno' => $errno,
        'errstr' => $errstr,
        'errfile' => $errfile,
        'errline' => $errline,
        'uri' => $_SERVER['REQUEST_URI'],
        'time' => $dates
    ];

    $_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj'] = $errno;
    $_SESSION['zyA2QF2M25e3TyVmi2w99n2tB'] = $errstr;
    $_SESSION['6vhow83GCbV6jdXTMEgAJdqEN'] = "{$errline}, {$errstr}, {$errfile}, {$_SERVER['REQUEST_URI']}, {$dates}";

    // Jika mode DEBUG aktif, langsung tampilkan Handler
    if (DEBUG == 'true') {
        if (!headers_sent()) {
            // Bersihkan output buffer jika ada untuk memastikan hanya Handler yang tampil
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
        }
        
        // Load Handler secara langsung
        require_once BASEPATH . 'error/Handler' . EXT;
        exit();
    } else {
        // Jika mode produksi, tampilkan 500 generik
        if (!headers_sent()) {
            http_response_code(500);
        }
        require_once BASEPATH . 'error/500handler' . EXT;
        exit();
    }
}

function customExceptionHandler($exception) {
    customError(
        $exception->getCode(),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine()
    );
}

// Daftarkan handler
set_error_handler("customError");
set_exception_handler("customExceptionHandler");

function customErrorHandler() {
    $dates = date("Y-m-d H:i:s");
    $errstr = "Rute tidak ditemukan: " . (defined('ROUTE') ? ROUTE : 'Unknown');
    
    $_SESSION['error_data'] = [
        'errno' => '404',
        'errstr' => $errstr,
        'errfile' => 'Rute',
        'errline' => '0',
        'uri' => $_SERVER['REQUEST_URI'],
        'time' => $dates
    ];

    $_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj'] = "E-ROUTE-404";
    $_SESSION['zyA2QF2M25e3TyVmi2w99n2tB'] = $errstr;
    $_SESSION['6vhow83GCbV6jdXTMEgAJdqEN'] = "0, {$errstr}, -, {$_SERVER['REQUEST_URI']}, {$dates}";

    if (DEBUG == 'true') {
        $lognote = "Error 404 :: {$errstr} :: {$_SERVER['REQUEST_URI']}";
        $dataToLog = [$dates, get_client_ip(), get_client_browser(), $lognote];
        $data = implode(" ~ ", $dataToLog) . PHP_EOL;
        file_put_contents("logs/error.log", $data, FILE_APPEND);

        // Load Handler secara langsung
        require_once BASEPATH . 'error/Handler' . EXT;
        exit();
    }
}

function core($get) {
    $base = BASEPATH.$get.EXT;
    if(!empty($_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj']) and !empty($_SESSION['zyA2QF2M25e3TyVmi2w99n2tB'])){
        return BASEPATH.'error/Handler'.EXT;
    }else{
        return $base;
    }
}

function option($get) {
    $base = BASEPATH.$get.EXT;
    return $base;
}

function anti_injection($data){
    $filter = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
    return $filter;
}

function validateDate($date, $format = 'Y-m-d'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function anti_number_format($data){
    $filter = str_replace(",","",$data);
    return $filter;
}

function datelongind($ori) {
    $original = strtotime($ori);
    date_default_timezone_set('Asia/Jakarta');
    $chunks = array(
        array(60 * 60 * 24 * 365, 'tahun'),
        array(60 * 60 * 24 * 30, 'bulan'),
        array(60 * 60 * 24 * 7, 'minggu'),
        array(60 * 60 * 24, 'hari'),
        array(60 * 60, 'jam'),
        array(60, 'menit'),
        array(60/60, 'detik'),
    );

    list($tanggal,$waktu)=explode(" ",$ori);
    $nh = date('l', strtotime($tanggal));

	if($nh=="Sunday"){
        $namahari="Minggu";
	}else if($nh=="Monday"){
        $namahari="Senin";
	}else if($nh=="Tuesday"){
        $namahari="Selasa";
	}else if($nh=="Wednesday"){
        $namahari="Rabu";
	}else if($nh=="Thursday"){
        $namahari="Kamis";
	}else if($nh=="Friday"){
        $namahari="Jumat";
	}else if($nh=="Saturday"){
        $namahari="Sabtu";
	}else{
        $namahari="";
	}

    $today = gmdate("Y-m-d H:i:s", time()+60*60*7);
    $today = strtotime($today);
    $since = $today - $original;
    $long = "";

    if ($since > 18144000)
    {
        $print = $namahari.", ".date("j M", $original);
        if ($since > 31536000)
        {
            $print .= " " . date("Y", $original);
        }
        return $print;
    }

    for ($i = 0, $j = count($chunks); $i < $j; $i++)
    {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];

        if (($count = floor($since / $seconds)) != 0)
            break;
    }

    if ($since < 63072000)
    {
        if ($since < 86400)
        {
            $long = "";
        }
        else if ($since < 604800)
        {
            $long = "";
        }
        else if ($since < 18144000)
        {
            $long = "";
        }
        else if ($since < 31536000)
        {
            $long = $namahari.", ".date("j M", $original);
        }
        else if ($since > 31536000)
        {
            $long = $namahari.", ".date("j M", $original);
        }
    }

    $print = ($count == 1) ? '1 ' . $name : "$count {$name}";
    return $print . ' yang lalu ' . $long;
}

function thousandsCurrencyFormat($num) {
    $x = round($num);
    $x_number_format = number_format($x);
    $x_array = explode(',', $x_number_format);
    $x_parts = array('rb', 'jt', 'mil', 'tri');
    $x_count_parts = count($x_array) - 1;
    $x_display = $x;
    $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : ' ');
    $x_display .= $x_parts[$x_count_parts - 1];
    return $x_display;
}

function get_month_indo($bln) {
    $months = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    return $months[$bln] ?? '';
}

function date_indo($date) {
	list($thn,$bln,$tgl)=explode('-',$date);
	return $tgl." ".get_month_indo($bln)." ".$thn;
}

function datetime_indo($date) {
    list($ltgl,$time)=explode(' ',$date);
    list($thn,$bln,$tgl)=explode('-',$ltgl);
	return $tgl." ".get_month_indo($bln)." ".$thn;
}

function dateandtime_indo($date) {
    list($ltgl,$time)=explode(' ',$date);
    list($thn,$bln,$tgl)=explode('-',$ltgl);
	return $tgl." ".get_month_indo($bln)." ".$thn.", ".$time;
}

function daydateandtime_indo($date) {
    list($ltgl,$time)=explode(' ',$date);
    list($thn,$bln,$tgl)=explode('-',$ltgl);
    $hari = date ("D",  strtotime($ltgl));

    switch($hari){
		case 'Sun': $hari_ini = "Minggu"; break;
		case 'Mon': $hari_ini = "Senin"; break;
		case 'Tue': $hari_ini = "Selasa"; break;
		case 'Wed': $hari_ini = "Rabu"; break;
		case 'Thu': $hari_ini = "Kamis"; break;
		case 'Fri': $hari_ini = "Jumat"; break;
		case 'Sat': $hari_ini = "Sabtu"; break;
        default: $hari_ini = ""; break;
	}

	return $hari_ini.", ".$tgl." ".get_month_indo($bln)." ".$thn." ".$time;
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR')) $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED')) $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR')) $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED')) $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR')) $ipaddress = getenv('REMOTE_ADDR');
    else $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function getUserIP() {
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = $_SERVER['HTTP_CLIENT_IP'] ?? '';
    $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    $remote  = $_SERVER['REMOTE_ADDR'] ?? '';

    if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
    elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
    else $ip = $remote;
    return $ip;
}

function get_client_browser() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $t = " " . strtolower($user_agent);
    if (strpos($t, 'opera') || strpos($t, 'opr/')) return 'Opera';
    elseif (strpos($t, 'edge')) return 'Edge';
    elseif (strpos($t, 'chrome')) return 'Chrome';
    elseif (strpos($t, 'safari')) return 'Safari';
    elseif (strpos($t, 'firefox')) return 'Firefox';
    elseif (strpos($t, 'msie') || strpos($t, 'trident/7')) return 'Internet Explorer';
    elseif (strpos($t, 'google')) return '[Bot] Googlebot';
    elseif (strpos($t, 'bing')) return '[Bot] Bingbot';
    elseif (strpos($t, 'slurp')) return '[Bot] Yahoo! Slurp';
    elseif (strpos($t, 'duckduckgo')) return '[Bot] DuckDuckBot';
    elseif (strpos($t, 'baidu')) return '[Bot] Baidu';
    elseif (strpos($t, 'yandex')) return '[Bot] Yandex';
    elseif (strpos($t, 'sogou')) return '[Bot] Sogou';
    elseif (strpos($t, 'exabot')) return '[Bot] Exabot';
    elseif (strpos($t, 'msn')) return '[Bot] MSN';
    elseif (strpos($t, 'mj12bot')) return '[Bot] Majestic';
    elseif (strpos($t, 'ahrefs')) return '[Bot] Ahrefs';
    elseif (strpos($t, 'semrush')) return '[Bot] SEMRush';
    elseif (strpos($t, 'rogerbot') || strpos($t, 'dotbot')) return '[Bot] Moz or OpenSiteExplorer';
    elseif (strpos($t, 'frog') || strpos($t, 'screaming')) return '[Bot] Screaming Frog';
    elseif (strpos($t, 'facebook')) return '[Bot] Facebook';
    elseif (strpos($t, 'pinterest')) return '[Bot] Pinterest';
    elseif (strpos($t, 'crawler') || strpos($t, 'api') || strpos($t, 'spider') || strpos($t, 'http') || strpos($t, 'bot') || strpos($t, 'archive') || strpos($t, 'info') || strpos($t, 'data')) return '[Bot] Other';
    return 'Other (Unknown)';
}

function encrypt($in, $key = '', $iv = '', $method = ''){
    $encrypt_method = empty( $method ) ? 'AES-256-CBC' : $method;
    $secret_key = empty( $key ) ? 'random' : $key;
    $secret_iv  = empty( $iv  ) ? 'random' : $iv;
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
    return base64_encode( openssl_encrypt( $in, $encrypt_method, $key, 0, $iv ) );
}

function decrypt($in, $key = '', $iv = '', $method = ''){
    $encrypt_method = empty( $method ) ? 'AES-256-CBC' : $method;
    $secret_key = empty( $key ) ? 'random' : $key;
    $secret_iv  = empty( $iv  ) ? 'random' : $iv;
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
    return openssl_decrypt( base64_decode( $in ), $encrypt_method, $key, 0, $iv );
}

function anti_right($in){
  $simple_string = $in;$ciphering = "AES-128-CTR";$options = 0;$m_iv = '1234567891011121';$m_key = "antimaling";$d=openssl_decrypt($simple_string, $ciphering, $m_key, $options, $m_iv);
  if($d){ return $d; }else{ echo "not be open"; exit();  }
}

function done($in){
  $simple_string = $in;$ciphering = "AES-128-CTR";$options = 0;$m_iv = '1234567891011121';$m_key = "antimaling";$d=openssl_decrypt($simple_string, $ciphering, $m_key, $options, $m_iv);
  if($d){ echo $d; }else{ echo "not be open"; exit();  }
}

function requestme($a,$b) {
    $code = '5KD1z/ptxT6PwA==';
    $anticheat = '5KD1z/ptxT6PwA==';
    $sub=$b;$fuk=anti_right($code);if($sub>=$fuk or $code!=$anticheat){ done($a);exit(); }else{ return $anticheat;  }
}

function requestyou($a,$get) {
  if(empty($get)){ done($a);exit(); }
}

function hpindo($nohp) {
     $nohp = str_replace([" ","(",")","."],"",$nohp);
     if(!preg_match('/[^+0-9]/',trim($nohp))){
         if(substr(trim($nohp), 0, 2)=='62') $hp = trim($nohp);
         elseif(substr(trim($nohp), 0, 1)=='0') $hp = '62'.substr(trim($nohp), 1);
         else $hp = trim($nohp);
     }else{
        $hp = '62';
     }
     return $hp;
 }

 function mydays($tanggal1,$tanggal2){
    $start = new DateTime($tanggal1);
    $end = new DateTime($tanggal2);
    $days = $start->diff($end);
    return $days->days;
 }

function mydaysnull($days){
    return ($days == 0 ? 1 : $days).' Hari';
}

function tgl_ind_to_eng($tgl) {
    return substr($tgl,6,4)."-".substr($tgl,3,2)."-".substr($tgl,0,2);
}

function tgl_eng_to_ind($tgl) {
    return substr($tgl,8,2)."-".substr($tgl,5,2)."-".substr($tgl,0,4);
}

function tgl_eng_to_format($tgl) {
    return substr($tgl,5,2)."/".substr($tgl,8,2)."/".substr($tgl,0,4);
}

function tgl_eng_to_format_time($tgl) {
    return substr($tgl,5,2)."/".substr($tgl,8,2)."/".substr($tgl,0,4)."".substr($tgl,10,6);
}

function tgl_eng_to_format_timesec($tgl) {
    return substr($tgl,5,2)."/".substr($tgl,8,2)."/".substr($tgl,0,4)."".substr($tgl,10,9);
}

function tgl_eng_to_year($tgl) {
    return substr($tgl,0,4);
}

function tgl_eng_to_ind_no_date($tgl) {
    $bl = substr($tgl,5,2);
    $bl2 = strtoupper(get_month_indo($bl));
    return $bl2." ".substr($tgl,0,4);
}

function tgl_eng_to_ind_no_date2($tgl) {
    $bl = substr($tgl,5,2);
    $months = ['01'=>'JAN','02'=>'FEB','03'=>'MAR','04'=>'APR','05'=>'MAY','06'=>'JUN','07'=>'JUL','08'=>'AUG','09'=>'SEP','10'=>'OKT','11'=>'NOV','12'=>'DEC'];
    $bl2 = $months[$bl] ?? '';
    return substr($tgl,8,2)."-".$bl2."-".substr($tgl,0,4);
}

function tgl_eng_to_ind_no_date3($tgl) {
  	$bl = substr($tgl,5,2);
    $bl2 = strtoupper(get_month_indo($bl));
  	return substr($tgl,8,2)."-".$bl2."-".substr($tgl,0,4);
}

function format_angka($angka) {
    return number_format($angka,0, ",",".");
}

function format_angka2($angka) {
  	return number_format($angka,0, ",","");
}

function format_angka4($angka) {
  	return number_format($angka);
}

function format_decimal($angka) {
  	return number_format($angka,1);
}

function format_decimal2($angka) {
  	return number_format($angka,2, ",",".");
}

function terbilang($x) {
    $abil = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    if ($x < 12) return " " . $abil[$x];
    elseif ($x < 20) return terbilang($x - 10) . " Belas" ;
    elseif ($x < 100) return terbilang($x / 10) . " Puluh" . terbilang($x % 10);
    elseif ($x < 200) return " seratus" . terbilang($x - 100);
    elseif ($x < 1000) return terbilang($x / 100) . " Ratus" . terbilang($x % 100);
    elseif ($x < 2000) return " seribu" . terbilang($x - 1000);
    elseif ($x < 1000000) return terbilang($x / 1000) . " Ribu" . terbilang($x % 1000);
    elseif ($x < 1000000000) return terbilang($x / 1000000) . " Juta" . terbilang($x % 1000000);
}

function goodday(){
    $a = date('H:i');
    $hour = date("G");
    if ($hour>1 && $hour<5) return "[".$a." WIB] By morning";
    elseif ($hour>=5 && $hour<10) return "[".$a." WIB] Good morning";
    elseif ($hour >=10 && $hour<15) return "[".$a." WIB] Good afternoon";
    elseif ($hour >=15 && $hour<17) return "[".$a." WIB] Good afternoon";
    elseif ($hour >=17 && $hour<18) return "[".$a." WIB] Towards Evening";
    elseif ($hour >=18 && $hour<19) return "[".$a." WIB] Dusk";
    elseif ($hour >=19) return "[".$a." WIB] Good night";
}

function scriptisNumberKey(){
    echo '<script>function isNumberKey(evt){ var charCode = (evt.which) ? evt.which : evt.keyCode; if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) return false; return true; } </script>';
}

/* End of file Container.php */
