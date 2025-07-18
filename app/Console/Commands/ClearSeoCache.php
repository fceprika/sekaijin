<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearSeoCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'seo:clear-cache';

    /**
     * The console command description.
     */
    protected $description = 'Clear all SEO-related caches (sitemap, meta tags, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing SEO caches...');

        // Clear sitemap cache
        Cache::forget('sitemap.xml');
        $this->line('✓ Sitemap cache cleared');

        // Clear any other SEO-related caches
        $seoKeys = [
            'meta.homepage',
            'meta.about',
            'meta.contact',
            'structured_data.homepage',
        ];

        foreach ($seoKeys as $key) {
            Cache::forget($key);
        }

        $this->line('✓ Meta tags cache cleared');

        $this->info('All SEO caches cleared successfully!');

        return 0;
    }
}
