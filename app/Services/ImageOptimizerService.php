<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageOptimizerService
{
    /**
     * Resize + compress gambar featured post.
     * Output: WebP 1200x675 (max 200KB) + thumbnail 600x338
     *
     * @param  string  $storagePath  Path relatif di storage/app/public (e.g. posts/featured/xxx.jpg)
     * @return string                Path WebP yang sudah dioptimasi
     */
    public function optimizeFeaturedImage(string $storagePath): string
    {
        $fullPath = Storage::disk('public')->path($storagePath);

        if (! file_exists($fullPath)) {
            return $storagePath;
        }

        // Tentukan path output WebP
        $webpPath = Str::replaceLast(
            pathinfo($fullPath, PATHINFO_EXTENSION),
            'webp',
            $storagePath
        );
        $webpFullPath = Storage::disk('public')->path($webpPath);

        // Buat direktori jika belum ada
        $dir = dirname($webpFullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // ── Resize & compress ke WebP (maks 1200×675, quality 80) ──
        $image = Image::read($fullPath);

        // Scale down jika lebih besar dari target (tidak stretch ke atas)
        if ($image->width() > 1200 || $image->height() > 675) {
            $image->scaleDown(width: 1200, height: 675);
        }

        $image->toWebp(quality: 80)->save($webpFullPath);

        // ── Buat thumbnail 600×338 untuk card listing ──
        $thumbDir  = dirname($webpFullPath) . '/thumbs';
        $thumbPath = $thumbDir . '/' . basename($webpFullPath);

        if (! is_dir($thumbDir)) {
            mkdir($thumbDir, 0755, true);
        }

        Image::read($fullPath)
            ->cover(600, 338)
            ->toWebp(quality: 70)
            ->save($thumbPath);

        // Hapus file original jika berhasil membuat WebP
        if (file_exists($webpFullPath) && $storagePath !== $webpPath) {
            @unlink($fullPath);
        }

        return $webpPath;
    }

    /**
     * Hapus semua file gambar terkait (webp + thumb) saat post dihapus.
     */
    public function deleteImages(string $storagePath): void
    {
        // Main WebP
        $webpPath = Str::replaceLast(
            pathinfo($storagePath, PATHINFO_EXTENSION),
            'webp',
            $storagePath
        );

        // Thumbnail
        $thumbPath = dirname($webpPath) . '/thumbs/' . basename($webpPath);

        foreach ([$storagePath, $webpPath, $thumbPath] as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
