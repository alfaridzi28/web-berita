<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    /**
     * Halaman utama — daftar berita dengan pagination dan caching.
     */
    public function index(Request $request)
    {
        // Cache dinonaktifkan saat ada filter/search aktif
        $hasFilter = $request->filled('kategori') || $request->filled('cari');

        if ($hasFilter) {
            // Query langsung ke DB saat pencarian (dynamic, tidak di-cache)
            $posts = $this->buildIndexQuery($request)->paginate(9)->withQueryString();
        } else {
            // Cache halaman index per-page
            $page  = $request->get('page', 1);
            $key   = PostCacheService::keyIndex("page:{$page}");
            $posts = Cache::remember($key, 300, fn () =>
                $this->buildIndexQuery($request)->paginate(9)->withQueryString()
            );
        }

        $categories = PostCacheService::getCategories();
        $heroPost   = PostCacheService::getHero();

        return view('posts.index', compact('posts', 'categories', 'heroPost'));
    }

    /**
     * Halaman detail berita berdasarkan slug — dengan cache + Schema.org.
     */
    public function show(string $slug)
    {
        $post = PostCacheService::getPostBySlug($slug);

        abort_if(is_null($post), 404);

        $relatedPosts = PostCacheService::getRelatedPosts($post);

        // Schema.org JSON-LD (tidak di-cache — generated per request, ringan)
        $jsonLd = [
            '@context'         => 'https://schema.org',
            '@type'            => 'NewsArticle',
            'headline'         => $post->title,
            'description'      => $post->meta_description ?? '',
            'datePublished'    => $post->published_at?->toIso8601String(),
            'dateModified'     => $post->updated_at->toIso8601String(),
            'author'           => [
                '@type' => 'Person',
                'name'  => $post->author?->name ?? 'Redaksi',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name'  => config('app.name'),
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => asset('favicon.ico'),
                ],
            ],
            'image'            => $post->featured_image_url ?? asset('og-default.png'),
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id'   => route('posts.show', $post->slug),
            ],
            'keywords' => $post->meta_keywords ?? '',
        ];

        return view('posts.show', compact('post', 'relatedPosts', 'jsonLd'));
    }

    // ─── Private Helpers ─────────────────────────────────

    private function buildIndexQuery(Request $request)
    {
        $query = Post::published()
            ->with(['author', 'category'])
            ->latest('published_at');

        if ($request->filled('kategori')) {
            $query->whereHas('category', fn ($q) =>
                $q->where('slug', $request->kategori)
            );
        }

        if ($request->filled('cari')) {
            $search = $request->cari;
            $query->where(fn ($q) =>
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('meta_description', 'like', "%{$search}%")
            );
        }

        return $query;
    }
}
