const CACHE_NAME = 'spa-pwa-cache-v1';
const ASSETS = [
  '/',
  '/index.php', // Sesuaikan dengan file utama Anda
  'https://cdn.jsdelivr.net/npm/lit-html@3.2.1/lit-html.js'
];

// Install Service Worker dan simpan aset dasar ke cache
self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS);
    })
  );
});

// Aktivasi dan hapus cache lama jika ada update
self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.map((key) => {
          if (key !== CACHE_NAME) {
            return caches.delete(key);
          }
        })
      );
    })
  );
});

// Strategi Cache: Network First, Fallback to Cache
self.addEventListener('fetch', (e) => {
  e.respondWith(
    fetch(e.request).catch(() => {
      return caches.match(e.request);
    })
  );
});