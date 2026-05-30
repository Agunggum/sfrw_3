import { html, render } from 'https://cdn.jsdelivr.net/npm/lit-html@3.2.1/lit-html.js';
window.lit = { html, render };
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

    // Logika Pencegah Flash Bahasa
    // Ambil bahasa yang tersimpan, jika tidak ada default-kan ke 'id'
    const currentLang = localStorage.getItem('user_language') || 'id';
    document.documentElement.setAttribute('data-lang-current', currentLang);
            
    // Jika bahasanya 'id' (sesuai teks asli HTML Anda), kita bisa langsung tampilkan body nanti
    if (currentLang === 'id') {
        document.addEventListener('DOMContentLoaded', () => {
        document.body.classList.add('lang-ready');
        });
    }
})();