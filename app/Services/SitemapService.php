<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    private string $outputPath;

    public function __construct()
    {
        $this->outputPath = public_path('sitemap.xml');
    }

    /**
     * Generate sitemap.xml lengkap dari semua post published.
     * Dipanggil otomatis oleh PostObserver saat status berubah.
     */
    public function generate(): void
    {
        try {
            $sitemap = Sitemap::create();

            // ── Halaman Utama ──
            $sitemap->add(
                Url::create(route('posts.index'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_HOURLY)
                    ->setPriority(1.0)
            );

            // ── Semua Artikel Published ──
            Post::published()
                ->with('category')
                ->orderByDesc('published_at')
                ->chunk(100, function ($posts) use ($sitemap) {
                    foreach ($posts as $post) {
                        $sitemap->add(
                            Url::create(route('posts.show', $post->slug))
                                ->setLastModificationDate($post->updated_at)
                                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                                ->setPriority(0.8)
                                ->addImage(
                                    $post->featured_image_url ?? '',
                                    $post->title
                                )
                        );
                    }
                });

            $sitemap->writeToFile($this->outputPath);

            Log::info('Sitemap generated', [
                'path'  => $this->outputPath,
                'posts' => Post::published()->count(),
            ]);

        } catch (\Throwable $e) {
            Log::error('Sitemap generation failed', ['error' => $e->getMessage()]);
        }
    }
}
