self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open('your-app-cache').then(function (cache) {
      return cache.addAll([
        '/',
        'https://canin.cyborgtech.co/Admin/',
        'https://canin.cyborgtech.co/Admin/patients/',
        'https://canin.cyborgtech.co/Admin/turns/',
        'https://canin-cdn.cyborgtech.co/assets/images/brand/favicon.ico',
        // Add more assets to cache
      ]);
    })
  );
});

self.addEventListener('fetch', function (event) {
  event.respondWith(
    caches.match(event.request).then(function (response) {
      return response || fetch(event.request);
    })
  );
});
