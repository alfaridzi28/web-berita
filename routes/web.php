<?php

use App\Http\Controllers\PostController;
use App\Services\SitemapService;
use Illuminate\Support\Facades\Route;

// Redirect root ke halaman berita
Route::get('/', [PostController::class, 'index'])->name('posts.index');

// Halaman detail berita
Route::get('/berita/{slug}', [PostController::class, 'show'])->name('posts.show');

// ── Switch Language ──
Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['id', 'en'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// ── SEO: Sitemap XML ──
Route::get('/sitemap.xml', function (SitemapService $sitemap) {
    // Generate on-demand jika file belum ada
    if (! file_exists(public_path('sitemap.xml'))) {
        $sitemap->generate();
    }
    return response()->file(public_path('sitemap.xml'), [
        'Content-Type' => 'application/xml',
    ]);
})->name('sitemap');

// ── SEO: Robots.txt ──
Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\nDisallow: /admin\n\nSitemap: " . route('sitemap');
    return response($content, 200, ['Content-Type' => 'text/plain']);
})->name('robots');

