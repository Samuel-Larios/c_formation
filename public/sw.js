const CACHE_NAME = 'v1';
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js',
  // Ajoutez d'autres assets à mettre en cache
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});
