<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$arrerr = explode(", ", $_SESSION['6vhow83GCbV6jdXTMEgAJdqEN']);

if(DEBUG == 'true'){
if($arrerr[1] == "Trying to get property of non-object"){ $errstr = "Query tidak terdeteksi pada fungsi query, cari di setiap folder yang memakai query atau telusuri halaman target"; }else{ $errstr = $arrerr[1]; }
?>
<!doctype html>
<html class="no-js" lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo "ATASI : ".$errstr; ?></title>
    <meta name="description" content="S-FRW">
    <link rel="icon" href="bootstrap/theme/logo-sfrw.ico" sizes="any" >
    <link rel="icon" href="bootstrap/theme/logo-sfrw.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="bootstrap/theme/logo-sfrw.png">
    <meta property="og:title" content="S-FRW>" />
    <meta property="og:image" content="<?php echo BASEURL; ?>bootstrap/theme/logo-sfrw.png" />
    <meta property="og:url" content="<?php echo  BASEURL; ?>" />
    <meta property="og:description" content="S-FRW <?php echo VERSIONFRMAEWORK; ?>" />
    <meta property="og:site_name" content="S-FRW" />
    <style>
        @import "bootstrap/theme/css/bootstrap.css?v=0.1";
        @import "bootstrap/theme/css/bootstrap.min.css?v=0.1";
        @import "bootstrap/theme/fontawesome/css/all.css";
    </style>
    <script src="bootstrap/theme/js/jquery-1.11.1.min.js"></script>
    <script src="bootstrap/theme/js/jquery-3.7.1.js"></script>
    <script src="bootstrap/theme/js/bootstrap.min.js"></script>
    <script src="bootstrap/theme/fontawesome/js/all.js"></script>
    <script src="bootstrap/theme/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="col-12">
        <div class="my-2">
            <div class="alert alert-dark">
                <small class="float-right font-weight-bold ml-1">S-FRW <?php echo VERSIONFRMAEWORK; ?> <i class="fa fa-copy"></i></small>
                <h3 class="col-12 p-1 text-wrap" title="ATASI : <?php echo $errstr; ?>"><span class="text-danger">ATASI : </span><?php echo $errstr; ?><br><br><code class="text-wrap"><?php echo (!empty($_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj2'])) ? $_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj2']:''; ?></code></h3>
                <?php if(!empty($arrerr[2])){ ?>
                <h5 class="col-12 p-1 text-wrap"><strong class="text-danger">pada file</strong> <?php echo $arrerr[2]; ?></h5>
                <?php } ?>
            </div>
            <div class="col-12 bg-secondary p-3 rounded">
                <div class="row">
                    <div class="col-lg-<?php if(!empty($arrerr[2])){ ?>4<?php }else{ ?>12<?php } ?>">
                        <div class="alert alert-secondary">
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <strong>Baris ke :</strong>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <span class="text-danger font-weight-bold"><?php echo $arrerr[0]; ?></span>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <strong>Halaman Target :</strong>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <span class="text-danger font-weight-bold"><?php echo $arrerr[3]; ?></span>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <strong>Nomor Kode :</strong>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <span class="text-danger font-weight-bold"><?php echo $_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj']; ?></span>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <strong>Waktu :</strong>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <span class="text-danger font-weight-bold"><?php echo $arrerr[4]; ?></span>
                            </div>
                        </div>
                    </div>

                    <?php if(!empty($arrerr[2])){ ?>
                    <div class="col-lg-8">
                        <div class="alert alert-secondary">
                            <pre class="comment bg-dark text-white rounded p-3 mt-3 border border-danger text-wrap">
                            <?php 
                            if(!empty($arrerr[2])){
                            $filename = $arrerr[2];
                                $read = file($filename); 
                                $numberline = 1;
                                foreach ($read as $line_number => $last_line) {
                                    if($numberline == $arrerr[0] or ($numberline == $arrerr[0] and basename($filename) == "option.php")){
                                        echo '<div class="row mb-2 bg-danger"><div class="col-1 mx-0 pl-1 pr-0">'.$numberline++.'.</div> <div class="col-10">'.htmlspecialchars(html_entity_decode($last_line))."</div></div>";
                                    }else{
                                        echo '<div class="row mb-2"><div class="col-1 mx-0 pl-1 pr-0">'.$numberline++.'.</div> <div class="col-10">'.htmlspecialchars(html_entity_decode($last_line)).'</div></div>';
                                    }
                                }
                            }
                            ?></pre>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="col-lg-12">
                        <div class="alert alert-secondary">
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <strong>Lingkungan :</strong>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <span class="text-danger font-weight-bold"><?php echo ENVIRONMENT; ?></span>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <strong>Browser :</strong>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <span class="text-danger font-weight-bold"><?php echo get_client_browser(); ?></span>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <strong>IP :</strong>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <span class="text-danger font-weight-bold"><?php echo get_client_ip(); ?></span>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <strong>Kirim e-Mail :</strong>
                            </div>
                            <div class="border-bottom border-dark p-2 mb-1 col-12 text-truncate">
                                <span class="text-danger font-weight-bold"><?php echo MAILACTIVATE; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="bootstrap/theme/js/main.js?v=0.6"></script>
<script language="javascript">
$(document).ready(function() {
    $(".comment").shorten();
	$(".comment-small").shorten({showChars: 10});
});
</script>
</body>
</html>
<?php $_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj'] = ""; $_SESSION['zyA2QF2M25e3TyVmi2w99n2tB'] = ""; $_SESSION['6vhow83GCbV6jdXTMEgAJdqEN'] = ""; ?>
<?php }else{ require_once BASEPATH.'error/500handler.php'; } exit(); ?>