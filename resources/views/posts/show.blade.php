@extends('layouts.app')

{{-- ── Dynamic SEO Meta ── --}}
@section('title',            $post->title . ' — ' . config('app.name'))
@section('meta_description', $post->meta_description ?? Str::limit(strip_tags($post->content), 155))
@section('meta_keywords',    $post->meta_keywords)
@section('canonical',        route('posts.show', $post->slug))
@section('og_type',          'article')
@section('og_title',         $post->title)
@section('og_description',   $post->meta_description ?? Str::limit(strip_tags($post->content), 155))
@section('og_image',         $post->featured_image_url ?? asset('og-default.png'))

{{-- ── Schema.org JSON-LD (NewsArticle) ── --}}
@push('schema')
<script type="application/ld+json">
{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')

{{-- ════════════════════════════════════════════ --}}
{{--  BREADCRUMB                                   --}}
{{-- ════════════════════════════════════════════ --}}
<div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <nav class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('posts.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">{{ __('Beranda') }}</a>
            <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            @if($post->category)
                <a href="{{ route('posts.index', ['kategori' => $post->category->slug]) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">
                    {{ $post->category->name }}
                </a>
                <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            @endif
            <span class="truncate max-w-xs text-gray-700 dark:text-gray-300">{{ Str::limit($post->title, 50) }}</span>
        </nav>
    </div>
</div>

{{-- ════════════════════════════════════════════ --}}
{{--  ARTIKEL UTAMA                               --}}
{{-- ════════════════════════════════════════════ --}}
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Header artikel --}}
    <header class="mb-8">

        {{-- Badge kategori --}}
        @if($post->category)
            <a href="{{ route('posts.index', ['kategori' => $post->category->slug]) }}"
               class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white mb-4 hover:opacity-90 transition"
               style="background-color: {{ $post->category->color }}">
                {{ $post->category->name }}
            </a>
        @endif

        {{-- Judul --}}
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white leading-tight mb-6">
            {{ $post->title }}
        </h1>

        {{-- Meta info --}}
        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-gray-500 dark:text-gray-400 pb-6 border-b border-gray-100 dark:border-gray-700">
            {{-- Author --}}
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($post->author?->name ?? 'R', 0, 1)) }}
                </div>
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ $post->author?->name ?? __('Redaksi') }}</span>
            </div>

            <span class="text-gray-200 dark:text-gray-600">|</span>

            {{-- Tanggal --}}
            <time datetime="{{ $post->published_at?->toIso8601String() }}" class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                {{ $post->published_at?->translatedFormat('d F Y, H:i') }}
            </time>

            <span class="text-gray-200 dark:text-gray-600">|</span>

            {{-- Reading time --}}
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $post->reading_time }} {{ __('menit baca') }}
            </span>
        </div>
    </header>

    {{-- Featured Image --}}
    @if($post->featured_image_url)
        <figure class="mb-10">
            <img
                src="{{ $post->featured_image_url }}"
                alt="{{ $post->title }}"
                class="w-full rounded-2xl shadow-md object-cover aspect-video bg-gray-100 dark:bg-gray-800"
                loading="eager"
            >
        </figure>
    @endif

    {{-- Body konten --}}
    <div class="prose-content text-gray-700 text-lg leading-relaxed">
        {!! $post->content !!}
    </div>

    {{-- Tags SEO keywords --}}
    @if($post->meta_keywords)
        <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400 mr-2">Tags:</span>
            @foreach(explode(',', $post->meta_keywords) as $tag)
                <span class="inline-block text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1 rounded-full mr-2 mb-2 hover:bg-blue-50 dark:hover:bg-blue-900/50 hover:text-blue-700 dark:hover:text-blue-300 transition cursor-default">
                    #{{ trim($tag) }}
                </span>
            @endforeach
        </div>
    @endif

    {{-- Share buttons --}}
    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">{{ __('Bagikan artikel ini:') }}</p>
        <div class="flex flex-wrap gap-3">
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(route('posts.show', $post->slug)) }}"
               target="_blank" rel="noopener"
               class="flex items-center gap-2 px-4 py-2 bg-sky-500 text-white text-sm font-medium rounded-full hover:bg-sky-600 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                X / Twitter
            </a>
            <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . route('posts.show', $post->slug)) }}"
               target="_blank" rel="noopener"
               class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-full hover:bg-green-600 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('posts.show', $post->slug)) }}"
               target="_blank" rel="noopener"
               class="flex items-center gap-2 px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-full hover:bg-blue-800 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </a>
        </div>
    </div>
</article>

{{-- ════════════════════════════════════════════ --}}
{{--  BERITA TERKAIT                              --}}
{{-- ════════════════════════════════════════════ --}}
@if($relatedPosts->isNotEmpty())
<section class="bg-gray-50 dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 py-16 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('Berita Terkait') }}
                @if($post->category)
                    <span class="text-blue-600 dark:text-blue-400">— {{ $post->category->name }}</span>
                @endif
            </h2>
            @if($post->category)
                <a href="{{ route('posts.index', ['kategori' => $post->category->slug]) }}"
                   class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                    {{ __('Lihat semua') }} &rarr;
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedPosts as $related)
                <x-post-card :post="$related" compact/>
            @endforeach
        </div>
    </div>
</section>
@endif

@php
use Illuminate\Support\Str;
@endphp

@endsection
