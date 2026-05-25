<?php 
if (defined('IS_AJAX') && IS_AJAX) {
    // Jika AJAX, kita kirimkan judul halaman via header agar bisa diupdate oleh JS
    header('X-Page-Title: ' . WEBTITLETOP);
    return; 
} 
?>
<!doctype html>
<html class="no-js" lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo WEBTITLETOP; ?></title>
    <meta name="description" content="<?php echo WEBTITLE; ?> <?php echo VERSIONFRMAEWORK; ?>" />
    <link rel="icon" href="<?php echo asset('bootstrap/theme/logo-sfrw.ico'); ?>" sizes="any" >
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
        :root { --bg-body: #ffffff; --text-main: #212529; --card-bg: #f8f9fa; --border-color: #dee2e6; --accent-color: #dc3545; }
        [data-bs-theme="dark"] { --bg-body: #121212; --text-main: #f8f9fa; --card-bg: #1e1e1e; --border-color: #333333; --accent-color: #dc3545; }
        [aria-labelledby="bd-theme-text"] {
        --bs-dropdown-link-active-bg: #dc3545; /* Ganti dengan warna aktif yang Anda inginkan */
        --bs-dropdown-link-active-color: #ffffff; /* Warna teks saat aktif */
        }
        footer { background-color: var(--card-bg); border: 1px solid var(--border-color); }
        /* Loading Animation Styles */
        .btn { transition: all 0.2s ease-in-out; position: relative; }
        .btn:disabled { cursor: not-allowed; opacity: 0.8; }
        .spinner-border-sm { width: 1rem; height: 1rem; border-width: 0.15em; }
        /* Global Progress Bar */
        #nprogress { position: fixed; top: 0; left: 0; width: 100%; height: 3px; background: transparent; z-index: 9999; }
        #nprogress .bar { background: #dc3545; width: 0; height: 100%; transition: width 0.3s ease; }
        /* Menghapus gaya default hover Bootstrap dan menerapkan animasi */
        .animated-link {
        text-decoration: none !important;
        position: relative;
        display: inline-block;
        /* Atur warna teks utama di sini jika ingin mengubahnya */
        }
        /* Memaksa hover Bootstrap agar tidak mengubah latar belakang / border */
        .animated-link:hover, 
        .animated-link:focus, 
        .animated-link:active {
        background-color: transparent !important;
        border-color: transparent !important;
        box-shadow: none !important; /* Menghapus efek glow fokus */
        color: inherit; /* Menjaga warna teks tetap sama saat di-hover */
        }
        /* Membuat animasi garis bawah dari kiri ke kanan */
        .animated-link::after {
        content: '';
        position: absolute;
        width: 100%;
        transform: scaleX(0);
        height: 2px;
        bottom: -2px; /* Jarak garis dari teks */
        left: 0;
        background-color: currentColor; /* Warna garis otomatis mengikuti warna teks */
        transform-origin: bottom left;
        transition: transform 0.25s ease-out;
        }
        /* Jalankan animasi saat hover */
        .animated-link:hover::after {
        transform: scaleX(1);
        }
    </style>
    <script src="<?php echo asset('bootstrap/theme/js/jquery-1.11.1.min.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/js/jquery-3.7.1.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/fontawesome/js/all.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/js/datatables.js'); ?>"></script>
    <script src="<?php echo asset('bootstrap/theme/js/dataTables.bootstrap5.js'); ?>"></script>
    
    <script type="module">
        import { html, render } from 'https://cdn.jsdelivr.net/npm/lit-html@3.2.1/lit-html.js';
        window.lit = { html, render };
    </script>
    <script>
        // Mencegah flash tema (FOUC)
        (() => {
            const getStoredTheme = () => localStorage.getItem('theme');
            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme();
                if (storedTheme && storedTheme !== 'auto') return storedTheme;
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };
            const theme = getPreferredTheme();
            if (theme === 'dark') document.documentElement.setAttribute('data-bs-theme', 'dark');
            else document.documentElement.setAttribute('data-bs-theme', 'light');
        })();
    </script>
</head>
<body>
    <div id="spa-content">