<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PWAGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pwa:init
                            {--force : Overwrite existing files}
                            {--icons : Generate placeholder icons}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Progressive Web App configuration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->generateManifest();
        $this->generateServiceWorker();
        $this->handleIcons();

        $this->newLine();
        $this->info('PWA setup completed successfully!');
        $this->line('Don\'t forget to:');
        $this->line('- Add the PWA meta tags to your layout file');
        $this->line('- Serve your application over HTTPS');
        $this->line('- Test the installation flow');

        return Command::SUCCESS;
    }

    /**
     * Generate the manifest.json file
     */
    protected function generateManifest()
    {
        $manifestPath = public_path('manifest.json');

        if (File::exists($manifestPath)) {
            if (!$this->option('force')) {
                $this->warn('manifest.json already exists. Use --force to overwrite.');
                return;
            }
            $this->warn('Overwriting existing manifest.json');
        }

        $manifestData = [
            "name" => config('app.name', 'Laravel PWA'),
            "short_name" => Str::slug(config('app.name', 'Laravel PWA')),
            "start_url" => "/",
            "display" => "standalone",
            "theme_color" => "#28a745",
            "background_color" => "#ffffff",
            "icons" => [
                [
                    "src" => "/images/icons/icon-72x72.png",
                    "sizes" => "72x72",
                    "type" => "image/png"
                ],
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
            ],
            "splash_screens" => [
                [
                    "src" => "/images/icons/splash-640x1136.png",
                    "sizes" => "640x1136",
                    "type" => "image/png"
                ]
            ]
        ];

        File::put($manifestPath, json_encode($manifestData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->info('manifest.json generated successfully');
    }

    /**
     * Generate the service worker file
     */
    protected function generateServiceWorker()
    {
        $swPath = public_path('sw.js');

        if (File::exists($swPath)) {
            if (!$this->option('force')) {
                $this->warn('sw.js already exists. Use --force to overwrite.');
                return;
            }
            $this->warn('Overwriting existing sw.js');
        }

        $swContent = <<<'SW'
const CACHE_NAME = 'app-cache-v1';
const ASSETS_TO_CACHE = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/manifest.json',
  '/images/icons/icon-72x72.png',
  '/images/icons/icon-192x192.png',
  '/images/icons/icon-512x512.png'
];

// Install event - cache static assets
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(ASSETS_TO_CACHE))
      .then(() => self.skipWaiting())
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames
          .filter(name => name !== CACHE_NAME)
          .map(name => caches.delete(name))
      );
    })
  );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(cachedResponse => {
        return cachedResponse || fetch(event.request);
      })
  );
});
SW;

        File::put($swPath, $swContent);
        $this->info('sw.js generated successfully');
    }

    /**
     * Handle icon generation
     */
    protected function handleIcons()
    {
        $iconsDir = public_path('images/icons');
        File::ensureDirectoryExists($iconsDir);

        if ($this->option('icons')) {
            $this->generatePlaceholderIcons($iconsDir);
        } else {
            $this->warn('No icons generated. Use --icons to create placeholder icons');
            $this->line('Please add your own icons to: '.$iconsDir);
            $this->line('Required sizes: 72x72, 192x192, 512x512');
        }
    }

    /**
     * Generate placeholder icons for development
     *
     * @param string $directory
     */
    protected function generatePlaceholderIcons($directory)
    {
        $sizes = [72, 192, 512];
        $appName = config('app.name', 'Laravel PWA');

        foreach ($sizes as $size) {
            $image = imagecreatetruecolor($size, $size);
            $bgColor = imagecolorallocate($image, 40, 167, 69); // #28a745
            $textColor = imagecolorallocate($image, 255, 255, 255);

            imagefill($image, 0, 0, $bgColor);
            $fontSize = $size / 4;
            $text = substr($appName, 0, 3);
            $font = 5; // Built-in GD font

            // Center text
            $textWidth = imagefontwidth($font) * strlen($text);
            $textHeight = imagefontheight($font);
            $x = ($size - $textWidth) / 2;
            $y = ($size - $textHeight) / 2;

            imagestring($image, $font, $x, $y, $text, $textColor);

            imagepng($image, "{$directory}/icon-{$size}x{$size}.png");
            imagedestroy($image);
        }

        $this->info('Placeholder icons generated in: '.$directory);
    }
}
