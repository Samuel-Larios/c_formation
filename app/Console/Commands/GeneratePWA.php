<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GeneratePWA extends Command
{
    protected $signature = 'pwa:generate';
    protected $description = 'Generate PWA files';

    public function handle()
    {
        // Créer le dossier des icônes
        File::ensureDirectoryExists(public_path('images/icons'));

        // Créer le manifest.json
        File::put(public_path('manifest.json'), json_encode([
            "name" => config('app.name'),
            "short_name" => "MyPWA",
            "start_url" => "/",
            "display" => "standalone",
            "theme_color" => "#000000",
            "background_color" => "#ffffff",
            "icons" => [
                [
                    "src" => "/images/icons/icon-192x192.png",
                    "sizes" => "192x192",
                    "type" => "image/png"
                ],
                [
                    "src" => "/images/icons/icon-512x512.png",
                    "sizes" => "512x512",
                    "type" => "image/png"
                ]
            ]
        ], JSON_PRETTY_PRINT));

        // Créer le service worker
        File::put(public_path('sw.js'), <<<'SW'
const CACHE_NAME = 'app-cache-v1';
const urlsToCache = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/manifest.json'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => response || fetch(event.request))
  );
});
SW
        );

        $this->info('PWA files generated successfully!');
    }
}
