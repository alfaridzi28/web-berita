<?php

namespace App\Providers;

use App\Models\Post;
use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ── Keamanan: strict mode di development ──
        Model::shouldBeStrict(! app()->isProduction());

        // ── Observer: image optimization + cache bust + sitemap ──
        Post::observe(PostObserver::class);

        // ── Scheduled Tasks ──
        Schedule::command('sitemap:generate')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();
    }
}
