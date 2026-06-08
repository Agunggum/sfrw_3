<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

// Pastikan variabel yang dibutuhkan tersedia
$error_view = $_REQUEST['errorlogview'] ?? 'Unknown View';
$route_path = BASEPATH . 'route.php';

if (DEBUG == 'true'):
    // Gunakan template Handler jika tersedia
    if (file_exists(BASEPATH . 'error/Handler.php')) {
        $_SESSION['error_data'] = [
            'errno' => 'VIEW-NOT-FOUND',
            'errstr' => "File view '<b>$error_view</b>' tidak ditemukan. Pastikan file tersebut ada di folder mvc/view/ dan telah didaftarkan di route.php.",
            'errfile' => $route_path,
            'errline' => 'Check your route or controller',
            'uri' => $_SERVER['REQUEST_URI'],
            'time' => date("Y-m-d H:i:s")
        ];
        require_once BASEPATH . 'error/Handler.php';
        exit();
    }
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S-FRW Error: View Not Found</title>
    <link href="<?php echo asset('bootstrap/theme/css/bootstrap.min.css?v=5.3.8'); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script>
        (() => {
            const theme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>
    <style>
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background-color: var(--bs-tertiary-bg); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .error-card { max-width: 600px; width: 100%; border: none; border-radius: 1.5rem; box-shadow: 0 1rem 3rem rgba(0,0,0,0.1); overflow: hidden; }
        .error-icon { font-size: 4rem; color: #ffc107; margin-bottom: 1.5rem; }
        .code-path { background: var(--bs-secondary-bg); padding: 0.75rem 1rem; border-radius: 0.5rem; font-family: 'Cascadia Code', monospace; font-size: 0.85rem; border-left: 4px solid #ffc107; word-break: break-all; }
        .btn-home { border-radius: 0.75rem; padding: 0.75rem 1.5rem; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container p-4">
        <div class="card error-card mx-auto">
            <div class="card-body p-5 text-center">
                <div class="error-icon">
                    <i class="bi bi-file-earmark-x-fill"></i>
                </div>
                <h1 class="h3 mb-3 fw-bold">View Tidak Ditemukan</h1>
                <p class="text-secondary mb-4">Maaf, sistem tidak dapat menemukan file tampilan yang diminta. Ini biasanya terjadi karena file belum dibuat atau salah penulisan di controller/route.</p>
                
                <div class="text-start mb-4">
                    <p class="small text-uppercase fw-bold text-muted mb-2">File yang dicari:</p>
                    <div class="code-path mb-3">
                        <i class="bi bi-search me-2"></i><?php echo htmlspecialchars($error_view); ?>
                    </div>
                    
                    <p class="small text-uppercase fw-bold text-muted mb-2">Lokasi Pengecekan:</p>
                    <div class="code-path">
                        <i class="bi bi-signpost-split me-2"></i><?php echo htmlspecialchars($route_path); ?>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?php echo BASEURL; ?>" class="btn btn-primary btn-home">
                        <i class="bi bi-house-door me-2"></i> Kembali ke Beranda
                    </a>
                    <button onclick="window.location.reload()" class="btn btn-outline-secondary btn-home">
                        <i class="bi bi-arrow-clockwise me-2"></i> Coba Lagi
                    </button>
                </div>
            </div>
            <div class="card-footer bg-light py-3 border-0 text-center">
                <small class="text-muted">S-FRW Framework &bull; Debug Mode Aktif</small>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
else:
    // Tampilan untuk Production (DEBUG = false)
    if (file_exists(BASEPATH . 'error/404handler.php')) {
        require_once BASEPATH . 'error/404handler.php';
    } else {
        echo "<h1>404 - Halaman Tidak Ditemukan</h1>";
    }
endif;
exit();
?>