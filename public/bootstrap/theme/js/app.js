import { html, render } from 'https://cdn.jsdelivr.net/npm/lit-html@3.2.1/lit-html.js';
window.lit = { html, render };

/**
 * Frontend Application Router (Mirip route.php)
 */
const App = (() => {
    const routes = [];

    const addRoute = (method, path, handler) => {
        // Ubah format rute {param} menjadi regex
        const regexPath = path.replace(/\{([^}]+)\}/g, '([^/]+)').replace(/\//g, '\\/');
        routes.push({
            method: method.toUpperCase(),
            pattern: new RegExp(`^${regexPath}$`),
            handler: handler,
            originalPath: path
        });
    };

    const handle = (url) => {
        // Bersihkan URL dari BASEURL
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

// Mencegah flash tema (FOUC) & Inisialisasi Global
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
    
    document.addEventListener('DOMContentLoaded', () => {
        document.body.classList.add('lang-ready');
        App.handle(window.location.href);
    });

    // Sinkronisasi dengan SPA Navigator
    document.addEventListener('spa:content-loaded', (e) => {
        App.handle(e.detail.url);
    });
})();

/**
 * Definisi Rute Frontend (Metode route.php)
 * --------------------------------------------------------------------------
 */

App.ambil('/', () => {
    console.log('SPA: Halaman Beranda Aktif');
});

App.ambil('/login', () => {
    console.log('SPA: Halaman Login Aktif');
});

App.ambil('/dashboard', () => {
    console.log('SPA: Halaman Dashboard Aktif');
});
