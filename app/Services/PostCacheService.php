<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostCacheService
{
    // TTL defaults (detik)
    const TTL_HERO       = 300;   //  5 menit — hero post
    const TTL_CATEGORIES = 3600;  //  1 jam   — daftar kategori (jarang berubah)
    const TTL_DETAIL     = 1800;  // 30 menit — detail artikel
    const TTL_RELATED    = 1800;  // 30 menit — berita terkait

    // ─── KEY HELPERS ─────────────────────────────────────

    public static function keyHero(): string         { return 'posts:hero'; }
    public static function keyCategories(): string   { return 'categories:all_with_count'; }
    public static function keyDetail(string $slug): string   { return "post:detail:{$slug}"; }
    public static function keyRelated(int $postId, ?int $catId): string
    {
        return "post:related:{$postId}:cat:{$catId}";
    }
    public static function keyIndex(string $params): string
    {
        return 'posts:index:' . md5($params);
    }

    // ─── GETTERS (cache-aside pattern) ───────────────────

    public static function getHero(): ?Post
    {
        return Cache::remember(self::keyHero(), self::TTL_HERO, function () {
            return Post::published()
                ->with(['author', 'category'])
                ->latest('published_at')
                ->first();
        });
    }

    public static function getCategories()
    {
        return Cache::remember(self::keyCategories(), self::TTL_CATEGORIES, function () {
            return \App\Models\Category::withCount([
                'posts' => fn ($q) => $q->published(),
            ])->get();
        });
    }

    public static function getPostBySlug(string $slug): ?Post
    {
        return Cache::remember(self::keyDetail($slug), self::TTL_DETAIL, function () use ($slug) {
            return Post::published()
                ->with(['author', 'category'])
                ->where('slug', $slug)
                ->first();
        });
    }

    public static function getRelatedPosts(Post $post): \Illuminate\Database\Eloquent\Collection
    {
        $key = self::keyRelated($post->id, $post->category_id);

        return Cache::remember($key, self::TTL_RELATED, function () use ($post) {
            return Post::published()
                ->with(['author', 'category'])
                ->where('id', '!=', $post->id)
                ->when(
                    $post->category_id,
                    fn ($q) => $q->where('category_id', $post->category_id),
                    fn ($q) => $q->latest('published_at')
                )
                ->latest('published_at')
                ->take(4)
                ->get();
        });
    }

    // ─── INVALIDATION ─────────────────────────────────────

    /**
     * Hapus cache terkait satu post (dipanggil saat publish/update/delete).
     */
    public static function forget(Post $post): void
    {
        Cache::forget(self::keyDetail($post->slug));
        Cache::forget(self::keyRelated($post->id, $post->category_id));
        Cache::forget(self::keyHero());
        Cache::forget(self::keyCategories());
        // Hapus semua cache halaman index (pakai tags jika Redis, atau flush prefix)
        Cache::flush(); // ⚠️ Ganti ke Cache::tags(['posts'])->flush() jika pakai Redis
    }
}
