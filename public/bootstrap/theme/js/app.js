import { html, render } from 'https://cdn.jsdelivr.net/npm/lit-html@3.2.1/lit-html.js';
window.lit = { html, render };

/**
 * Frontend Application Router (Mirip route.php)
 */
const App = (() => {
    const routes = [];

    const addRoute = (method, path, handler) => {
        const regexPath = path.replace(/\{([^}]+)\}/g, '([^/]+)').replace(/\//g, '\\/');
        routes.push({
            method: method.toUpperCase(),
            pattern: new RegExp(`^${regexPath}$`),
            handler: handler,
            originalPath: path
        });
    };

    const handle = (url) => {
        const baseUrl = window.location.origin + (window.BASEURL_PATH || '/');
        let path = url.replace(baseUrl, '').split('?')[0];
        if (!path.startsWith('/')) path = '/' + path;

        for (const route of routes) {
            const match = path.match(route.pattern);
            if (match) {
                const params = match.slice(1);
                if (typeof route.handler === 'function') {
                    route.handler(...params);
                }
                return true;
            }
        }
        return false;
    };

    return {
        ambil: (path, handler) => addRoute('GET', path, handler),
        kirim: (path, handler) => addRoute('POST', path, handler),
        handle: handle
    };
})();

window.App = App;

// Mencegah flash tema (FOUC) & Inisialisasi Global + PWA
(() => {
    const getStoredTheme = () => localStorage.getItem('theme');
    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme();
        if (storedTheme && storedTheme !== 'auto') return storedTheme;
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };
    
    const theme = getPreferredTheme();
    document.documentElement.setAttribute('data-bs-theme', theme === 'dark' ? 'dark' : 'light');

    const currentLang = localStorage.getItem('user_language') || 'id';
    document.documentElement.setAttribute('data-lang-current', currentLang);
    
    // --- FITUR KETERANGAN OFFLINE ---
    const updateOnlineStatus = () => {
        let offlineAlert = document.getElementById('pwa-offline-alert');
        
        if (!navigator.onLine) {
            // Jika offline dan elemen belum ada, buat elemennya
            if (!offlineAlert) {
                offlineAlert = document.createElement('div');
                offlineAlert.id = 'pwa-offline-alert';
                // Styling sederhana (bisa disesuaikan dengan Bootstrap Anda)
                offlineAlert.style = 'position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background: #dc3545; color: white; padding: 10px 20px; border-radius: 5px; z-index: 9999; font-family: sans-serif; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
                offlineAlert.innerText = '⚠️ Anda sedang offline. Beberapa fitur mungkin tidak tersedia.';
                document.body.appendChild(offlineAlert);
            }
        } else {
            // Jika kembali online, hapus keterangan offline
            if (offlineAlert) {
                offlineAlert.remove();
            }
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        document.body.classList.add('lang-ready');
        App.handle(window.location.href);

        // Cek status koneksi saat aplikasi pertama kali dimuat
        updateOnlineStatus();
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
    });

    // --- REGISTRASI SERVICE WORKER (PWA) ---
    /*if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/pwa.js')
                .then(reg => console.log('PWA: Service Worker terdaftar dengan sukses!', reg.scope))
                .catch(err => console.error('PWA: Gagal mendaftarkan Service Worker:', err));
        });
    }*/

    // Registrasi Service Worker KHUSUS MOBILE
    if ('serviceWorker' in navigator) {
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || window.innerWidth <= 768;

        if (isMobile) {
            window.addEventListener('load', () => {
            navigator.serviceWorker.register('/pwa.js')
                .then(reg => console.log('PWA Mobile: Registered', reg.scope))
                .catch(err => console.error('PWA Mobile Error:', err));
            });
        }
    }

    // Sinkronisasi dengan SPA Navigator
    document.addEventListener('spa:content-loaded', (e) => {
        App.handle(e.detail.url);
    });
})();

/**
 * Definisi Rute Frontend
 */
App.ambil('/', () => { console.log('SPA: Halaman Beranda Aktif'); });
App.ambil('/login', () => { console.log('SPA: Halaman Login Aktif'); });
App.ambil('/forgot-password', () => { console.log('SPA: Halaman Forgot Passwords Aktif'); });
App.ambil('/register', () => { console.log('SPA: Halaman Register Aktif'); });
App.ambil('/dashboard', () => { console.log('SPA: Halaman Dashboard Aktif'); });