const CACHE_NAME = 'spa-pwa-cache-v1.1';
const ASSETS = [
  '/',
  '/index.php', // Sesuaikan dengan file utama Anda
  '/manifest.json', // wajib masuk cache agar PWA terdeteksi offline
  'https://cdn.jsdelivr.net/npm/lit-html@3.2.1/lit-html.js'
];

// Deteksi perangkat mobile di level Service Worker
const isMobileUA = (userAgent) => {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent);
};

// Install Service Worker: Hanya simpan cache jika dibuka di Mobile
self.addEventListener('install', (e) => {
  e.waitUntil(
    (async () => {
      // Dapatkan data client untuk mengecek User-Agent
      const clientList = await self.clients.matchAll({ includeUncontrolled: true });
      const userAgent = clientList[0]?.userAgent || '';

      if (isMobileUA(userAgent)) {
        const cache = await caches.open(CACHE_NAME);
        return cache.addAll(ASSETS);
      }
    })()
  );
  self.skipWaiting();
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

// Strategi Cache: Hanya aktif menyajikan cache jika perangkat Mobile
self.addEventListener('fetch', (e) => {
  const userAgent = e.request.headers.get('user-agent') || '';

  // Jika Desktop: Biarkan request langsung ke jaringan tanpa intervensi cache PWA
  if (!isMobileUA(userAgent)) {
    return;
  }

  // Jika Mobile: Jalankan Network First, Fallback to Cache
  e.respondWith(
    fetch(e.request).catch(() => {
      return caches.match(e.request);
    })
  );
});