self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open('c-formation-cache').then((cache) => {
            return cache.addAll([
                '/',
                '/css/app.css',
                '/js/app.js',
                '/assets/img/kaiadmin/Logo_Songhai.jpg'
            ]);
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});
