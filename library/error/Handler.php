<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$error = $_SESSION['error_data'] ?? [
    'errno' => $_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj'] ?? 'Unknown',
    'errstr' => $_SESSION['zyA2QF2M25e3TyVmi2w99n2tB'] ?? 'An unknown error occurred',
    'errfile' => 'Unknown',
    'errline' => '0',
    'uri' => $_SERVER['REQUEST_URI'],
    'time' => date("Y-m-d H:i:s")
];

// Parse data dari session lama jika ada
if (!isset($_SESSION['error_data']) && isset($_SESSION['6vhow83GCbV6jdXTMEgAJdqEN'])) {
    $parts = explode(", ", $_SESSION['6vhow83GCbV6jdXTMEgAJdqEN']);
    $error['errline'] = $parts[0] ?? '0';
    $error['errstr'] = $parts[1] ?? $error['errstr'];
    $error['errfile'] = $parts[2] ?? 'Unknown';
    $error['uri'] = $parts[3] ?? $error['uri'];
    $error['time'] = $parts[4] ?? $error['time'];
}

if (DEBUG == 'true'):
function auto_translate($text, $target_lang = 'id', $source_lang = 'en') {
    $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" 
            . $source_lang . "&tl=" . $target_lang . "&dt=t&q=" . urlencode($text);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    $response = curl_exec($ch);
    curl_init();
    $result = json_decode($response, true);
    if (isset($result[0][0][0])) {
        return $result[0][0][0];
    }
    return $text; // Kembalikan teks asli jika gagal
}
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S-FRW Error: <?php echo htmlspecialchars($error['errstr']); ?></title>
    <link href="<?php echo asset('bootstrap/theme/css/bootstrap.min.css?v=5.3.8'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" href="<?php echo asset('bootstrap/theme/logo-sfrw.ico'); ?>" sizes="any" >
    <link rel="icon" href="<?php echo asset('bootstrap/theme/logo-sfrw.svg'); ?>" type="image/svg+xml">
    <link rel="apple-touch-icon" href="<?php echo asset('bootstrap/theme/logo-sfrw.png'); ?>">
    <script>
        // Deteksi tema dari localStorage secepat mungkin sebelum halaman dirender
        (() => {
            const getStoredTheme = () => localStorage.getItem('theme');
            const getPreferredTheme = () => {
                const storedTheme = getStoredTheme();
                if (storedTheme && storedTheme !== 'auto') {
                    return storedTheme;
                }
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };
            
            const theme = getPreferredTheme();
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; transition: background-color 0.3s ease; }
        .error-header { /*background: #dc3545; color: white;*/ padding: 1rem 0; margin-bottom: 2rem; border-radius: 0 0 1rem 1rem; /*box-shadow: 0 4px 6px rgba(0,0,0,0.1);*/ }
        .card { border: none; border-radius: 1rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.05); transition: all 0.3s ease; }
        
        /* Tema Adaptif untuk Code Block */
        [data-bs-theme="dark"] .code-block { background: #121212; color: #e0e0e0; border: 1px solid #333; }
        [data-bs-theme="light"] .code-block { background: #212529; color: #f8f9fa; }
        
        .code-block { padding: 1.5rem; border-radius: 0.5rem; overflow-x: auto; font-family: 'Cascadia Code', 'Courier New', Courier, monospace; font-size: 0.9rem; line-height: 1.5; }
        .line-number { color: #6c757d; display: inline-block; width: 3rem; text-align: right; margin-right: 1rem; user-select: none; }
        
        /* Highlight baris error */
        [data-bs-theme="dark"] .line-highlight { background: #3d2b2b; display: block; margin: 0 -1.5rem; padding: 0 1.5rem; border-left: 4px solid #ff4d4d; }
        [data-bs-theme="light"] .line-highlight { background: #495057; display: block; margin: 0 -1.5rem; padding: 0 1.5rem; border-left: 4px solid #ffc107; }
        
        .stack-trace { font-size: 0.85rem; }
        
        /* Penyesuaian Card pada Dark Mode */
        [data-bs-theme="dark"] .card { background-color: #1e1e1e; border: 1px solid #333; }
        [data-bs-theme="dark"] .bg-white { background-color: #1e1e1e !important; }
    </style>
</head>
<body>
    <div class="error-header">
        <div class="container">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-1 me-3"></i>
                <div>
                    <h1 class="h3 mb-1">Terjadi Kesalahan pada Aplikasi</h1>
                    <p class="mb-0 opacity-75">S-FRW Framework <?php echo VERSIONFRMAEWORK; ?></p>
                </div>
                <div class="ms-auto">
                    <button onclick="window.location.reload()" class="btn btn-outline-light">
                        <i class="bi bi-arrow-clockwise me-1"></i> Coba Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5">
        <div class="row g-4">
            <div class="col-12">
                <div class="card bg-white">
                    <div class="card-body p-4">
                        <h2 class="h5 text-danger border-bottom pb-3 mb-3">
                            <i class="bi bi-bug me-2"></i>Detail Kesalahan
                        </h2>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <p class="fs-5 mb-1"><strong>Pesan:</strong></p>
                                <div class="alert alert-danger border-0 shadow-sm">
                                    <?php echo auto_translate(htmlspecialchars($error['errstr'])); ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p class="mb-1 text-muted small uppercase">Status</p>
                                <p class="fw-bold"><?php echo $error['errno']; ?></p>
                                <p class="mb-1 text-muted small uppercase">Baris Error</p>
                                <p class="fw-bold text-danger"><?php echo $error['errline']; ?></p>
                                <p class="mb-1 text-muted small uppercase">Waktu Kejadian</p>
                                <p class="fw-bold"><?php echo $error['time']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (file_exists($error['errfile'])): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3 d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-file-earmark-code me-2"></i>Sumber Kesalahan</span>
                            <small class="text-muted fs-6"><?php echo htmlspecialchars($error['errfile']); ?></small>
                        </h3>
                        <div class="code-block">
                            <?php 
                            $lines = file($error['errfile']);
                            $start = max(0, $error['errline'] - 10);
                            $end = min(count($lines), $error['errline'] + 10);
                            
                            for ($i = $start; $i < $end; $i++) {
                                $num = $i + 1;
                                $line = htmlspecialchars($lines[$i]);
                                $isHighlighted = ($num == $error['errline']);
                                echo $isHighlighted ? '<div class="line-highlight">' : '<div>';
                                echo '<span class="line-number">' . $num . '</span>' . $line;
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h4 class="h6 text-muted mb-3">INFORMASI LINGKUNGAN</h4>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2 d-flex justify-content-between border-bottom pb-2">
                                <span class="text-secondary">Environment:</span>
                                <span class="badge bg-info text-dark"><?php echo ENVIRONMENT; ?></span>
                            </li>
                            <li class="mb-2 d-flex justify-content-between border-bottom pb-2">
                                <span class="text-secondary">PHP Version:</span>
                                <span><?php echo PHP_VERSION; ?></span>
                            </li>
                            <li class="mb-2 d-flex justify-content-between border-bottom pb-2">
                                <span class="text-secondary">Request URI:</span>
                                <span class="text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($error['uri']); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <h4 class="h6 text-muted mb-3">INFORMASI KLIEN</h4>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2 d-flex justify-content-between border-bottom pb-2">
                                <span class="text-secondary">Browser:</span>
                                <span><?php echo get_client_browser(); ?></span>
                            </li>
                            <li class="mb-2 d-flex justify-content-between border-bottom pb-2">
                                <span class="text-secondary">IP Address:</span>
                                <span><?php echo get_client_ip(); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 text-muted">
        <div class="h1">S-FRW</div>
        <small>&copy; <?php echo date('Y'); ?> Dirancang untuk efisiensi.</small>
    </footer>
</body>
</html>
<?php 
else:
    // Tampilan untuk Production (DEBUG = false)
    if ($error['errno'] == '404' || (isset($_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj']) && $_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj'] == "E-ROUTE-404")) {
        require_once BASEPATH . 'error/404handler' . EXT;
    } else {
        require_once BASEPATH . 'error/500handler' . EXT;
    }
endif;

// Bersihkan session error
unset($_SESSION['XfTVKuhxT3LUAbp5C8z37lHdj'], $_SESSION['zyA2QF2M25e3TyVmi2w99n2tB'], $_SESSION['6vhow83GCbV6jdXTMEgAJdqEN'], $_SESSION['error_data']);
exit();
?>
