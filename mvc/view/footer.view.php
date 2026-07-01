</div>
<?php if (defined('IS_AJAX') && IS_AJAX) return; ?>
<script src="<?php echo asset('bootstrap/theme/js/main.js?v=0.11'); ?>"></script>
<?php require_once view('bahasaIndex'); ?>
<!-- Native Live Reload Integration (SSE) -->
<script>
<?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'local'): ?>
    (() => {
        const initLiveReload = () => {
            console.log('%c S-FRW: Native Live Reload Active ', 'background: #198754; color: #fff');
            
            const eventSource = new EventSource('<?php echo asset('livereload-server.php'); ?>');

            eventSource.addEventListener('reload', (e) => {
                console.log('%c S-FRW: File change detected... ', 'background: #dc3545; color: #fff');
                
                // Buat custom event agar bisa ditangkap oleh modul lain (seperti SPA)
                const changeEvent = new CustomEvent('file-changed', { 
                    detail: JSON.parse(e.data) 
                });
                
                window.dispatchEvent(changeEvent);

                // Jika event tidak di-handle/di-prevent oleh SPA, lakukan hard reload default
                if (!changeEvent.defaultPrevented) {
                    location.reload();
                }
            });

            eventSource.onerror = () => {
                eventSource.close();
                // Reconnect setelah 3 detik jika koneksi putus
                setTimeout(initLiveReload, 3000);
            };
        };

        initLiveReload();
    })();
<?php endif; ?>
    (() => {
        // Pastikan SPANavigator sudah aktif sebelum memasang listener
        window.addEventListener('file-changed', (e) => {
            if (window.SPANavigator) {
                // Cegah skrip Live Reload utama melakukan location.reload()
                e.preventDefault(); 
                
                console.log('%c SPA: Performing soft reload via SPANavigator ', 'background: #007bff; color: #fff');
                
                // Lakukan navigasi ulang di tempat (Hot Reload / Soft Reload)
                SPANavigator.navigateTo(window.location.pathname + window.location.search);
            }
        });
    })();
</script>
</body>
</html>