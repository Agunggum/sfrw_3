<?php if (! defined('APPPATH')) exit('No direct script access allowed'); ?>
<?php
if (defined('IS_AJAX') && IS_AJAX) {
    // Jika AJAX, kita kirimkan judul halaman via header agar bisa diupdate oleh JS
    header('X-Page-Title: ' . WEBTITLETOP);
    return;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern AdminDashboard SFRW</title>
    <meta name="description" content="<?php echo WEBTITLE; ?> <?php echo VERSIONFRMAEWORK; ?>" />
    <link rel="icon" href="<?php echo asset('bootstrap/theme/logo-sfrw.ico'); ?>" sizes="any">
    <link rel="icon" href="<?php echo asset('bootstrap/theme/logo-sfrw.svg'); ?>" type="image/svg+xml">
    <link rel="apple-touch-icon" href="<?php echo asset('bootstrap/theme/logo-sfrw.png'); ?>">
    <meta property="og:title" content="<?php echo WEBTITLETOP; ?>" />
    <meta property="og:image" content="<?php echo asset('bootstrap/theme/logo-sfrw.png'); ?>" />
    <meta property="og:url" content="<?php echo BASEURL; ?>" />
    <meta property="og:description" content="<?php echo WEBTITLE; ?> <?php echo VERSIONFRMAEWORK; ?>" />
    <meta property="og:site_name" content="<?php echo WEBTITLE; ?>" />
    <style>
        @import "<?php echo asset('bootstrap/theme/css/bootstrap.css?v=5.3.8'); ?>";
        @import "<?php echo asset('bootstrap/theme/css/bootstrap.min.css?v=5.3.8'); ?>";
        @import "<?php echo asset('bootstrap/theme/css/datatables-bootstrap-5.css'); ?>";
        @import "<?php echo asset('bootstrap/theme/fontawesome/css/all.css'); ?>";
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css");
        @import url("https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.3.2/css/flag-icons.min.css");
        @import "<?php echo asset('bootstrap/theme/css/sfrw_dashboard.css?v=0.2'); ?>";
    </style>
    <script src="<?php echo asset('bootstrap/theme/js/jquery-3.7.1.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/fontawesome/js/all.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/js/datatables.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/js/dataTables.bootstrap5.js'); ?>"></script>
    <script type="module" src="<?php echo asset('bootstrap/theme/js/sfrw.js'); ?>"></script>
    
</head>
<body>
    <div id="spa-content">