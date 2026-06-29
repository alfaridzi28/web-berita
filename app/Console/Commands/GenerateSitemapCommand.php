<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;

class GenerateSitemapCommand extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml dari semua artikel published';

    public function handle(SitemapService $sitemap): int
    {
        $this->info('Generating sitemap...');
        $sitemap->generate();
        $this->info('✅ sitemap.xml berhasil dibuat di ' . public_path('sitemap.xml'));

        return self::SUCCESS;
    }
}
