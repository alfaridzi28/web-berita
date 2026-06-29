<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\ImageOptimizerService;
use App\Services\PostCacheService;
use App\Services\SitemapService;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    public function __construct(
        private ImageOptimizerService $imageOptimizer,
        private SitemapService        $sitemap,
    ) {}

    /**
     * Sebelum post disimpan — lakukan auto-translate ke bahasa Inggris.
     */
    public function saving(Post $post): void
    {
        $fields = ['title', 'content', 'meta_description'];
        $translator = new GoogleTranslate('en', 'id');

        foreach ($fields as $field) {
            // Cek apakah ada perubahan pada field tersebut
            if ($post->isDirty($field)) {
                // Ambil inputan user (Filament menyimpan ke current locale, biasanya 'en' atau 'id')
                $currentInput = $post->getTranslation($field, 'id', false) 
                             ?: $post->getTranslation($field, app()->getLocale(), false);
                
                if (!empty($currentInput)) {
                    try {
                        // Terjemahkan ke Inggris
                        $translated = $translator->translate($currentInput);
                        
                        // Set nilai eksplisit untuk ID dan EN
                        $post->setTranslation($field, 'id', $currentInput);
                        $post->setTranslation($field, 'en', $translated);
                    } catch (\Throwable $e) {
                        Log::error("Auto-translate failed for field {$field}", [
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Setelah post dibuat — optimasi gambar jika ada.
     */
    public function created(Post $post): void
    {
        $this->optimizeImage($post);
    }

    /**
     * Setelah post diupdate — optimasi gambar + bust cache + regenerate sitemap jika published.
     */
    public function updated(Post $post): void
    {
        // Optimasi gambar jika featured_image berubah
        if ($post->wasChanged('featured_image') && $post->featured_image) {
            $this->optimizeImage($post);
        }

        // Bust cache
        PostCacheService::forget($post);

        // Regenerate sitemap saat status berubah ke published
        if ($post->wasChanged('status') && $post->status === 'published') {
            $this->sitemap->generate();
        }
    }

    /**
     * Saat post dihapus (soft delete) — bust cache.
     */
    public function deleted(Post $post): void
    {
        PostCacheService::forget($post);
        $this->sitemap->generate();
    }

    /**
     * Saat post di-restore dari soft delete.
     */
    public function restored(Post $post): void
    {
        PostCacheService::forget($post);
        $this->sitemap->generate();
    }

    /**
     * Saat post dihapus permanen — hapus file gambar juga.
     */
    public function forceDeleted(Post $post): void
    {
        if ($post->featured_image) {
            $this->imageOptimizer->deleteImages($post->featured_image);
        }

        PostCacheService::forget($post);
        $this->sitemap->generate();
    }

    // ─── Private Helpers ──────────────────────────────────

    private function optimizeImage(Post $post): void
    {
        if (! $post->featured_image) {
            return;
        }

        try {
            $optimizedPath = $this->imageOptimizer->optimizeFeaturedImage($post->featured_image);

            // Update path di database tanpa trigger observer lagi
            if ($optimizedPath !== $post->featured_image) {
                Post::withoutEvents(fn () =>
                    $post->update(['featured_image' => $optimizedPath])
                );
            }
        } catch (\Throwable $e) {
            // Log error tapi jangan crash proses simpan
            \Log::error('Image optimization failed', [
                'post_id' => $post->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
