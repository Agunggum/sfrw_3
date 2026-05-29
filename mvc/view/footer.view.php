</div>
<?php if (defined('IS_AJAX') && IS_AJAX) return; ?>
<script src="<?php echo asset('bootstrap/theme/js/main.js?v=0.1'); ?>"></script>
<?php require_once view('bahasaIndex'); ?>
<?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'local'): ?>
<!-- Native Live Reload Integration (SSE) -->
<script>
    (() => {
        const initLiveReload = () => {
            console.log('%c S-FRW: Native Live Reload Active ', 'background: #198754; color: #fff');
            
            const eventSource = new EventSource('<?php echo asset('livereload-server.php'); ?>');

            eventSource.addEventListener('reload', (e) => {
                const data = JSON.parse(e.data);
                console.log('%c S-FRW: File change detected, updating... ', 'background: #dc3545; color: #fff');
                
                if (window.SPANavigator) {
                    SPANavigator.navigateTo(window.location.pathname + window.location.search);
                } else {
                    location.reload();
                }
            });

            eventSource.onerror = () => {
                eventSource.close();
                // Reconnect after 3 seconds if connection lost
                setTimeout(initLiveReload, 3000);
            };
        };

        initLiveReload();
    })();
</script>
<?php endif; ?>
</body>
</html>