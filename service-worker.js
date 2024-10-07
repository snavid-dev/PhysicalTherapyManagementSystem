self.addEventListener('install', function (event) {
  event.waitUntil(
    caches.open('your-app-cache').then(function (cache) {
      return cache.addAll([
        '/',
        'https://erfan.cyborgtech.co/Admin/',
        'https://erfan.cyborgtech.co/Admin/patients/',
        'https://erfan.cyborgtech.co/Admin/turns/',
        'https://crown-cdn.cyborgtech.co/assets/images/brand/favicon.ico',
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
