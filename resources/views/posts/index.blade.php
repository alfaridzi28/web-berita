@extends('layouts.app')

@section('title', config('app.name') . ' — ' . __('Beranda'))
@section('meta_description', __('Portal berita terpercaya yang menyajikan informasi terkini, terlengkap, dan terpercaya untuk masyarakat Indonesia.'))
@section('og_title',       config('app.name') . ' — ' . __('Beranda'))
@section('og_description', __('Portal berita terpercaya yang menyajikan informasi terkini, terlengkap, dan terpercaya untuk masyarakat Indonesia.'))

@section('content')

{{-- ════════════════════════════════════════════ --}}
{{--  HERO — Berita Utama                         --}}
{{-- ════════════════════════════════════════════ --}}
@if($heroPost && !request()->filled('cari') && !request()->filled('kategori'))
<section class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <a href="{{ route('posts.show', $heroPost->slug) }}" class="group grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

            {{-- Gambar hero --}}
            <div class="relative overflow-hidden rounded-2xl aspect-video bg-gray-100 dark:bg-gray-700 shadow-lg">
                @if($heroPost->featured_image_url)
                    <img
                        src="{{ $heroPost->featured_image_url }}"
                        alt="{{ $heroPost->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        loading="eager"
                    >
                @else
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center">
                        <svg class="w-24 h-24 text-white/30" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            </div>

            {{-- Konten hero --}}
            <div class="space-y-4">
                @if($heroPost->category)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold text-white"
                          style="background-color: {{ $heroPost->category->color }}">
                        {{ $heroPost->category->name }}
                    </span>
                @endif

                <h1 class="font-serif text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white leading-tight group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors">
                    {{ $heroPost->title }}
                </h1>

                @if($heroPost->meta_description)
                    <p class="text-gray-500 dark:text-gray-400 text-lg leading-relaxed line-clamp-3">
                        {{ $heroPost->meta_description }}
                    </p>
                @endif

                <div class="flex items-center gap-4 text-sm text-gray-400 dark:text-gray-500 pt-2">
                    <span class="font-medium text-gray-600 dark:text-gray-300">{{ $heroPost->author?->name ?? __('Redaksi') }}</span>
                    <span>&bull;</span>
                    <time datetime="{{ $heroPost->published_at?->toIso8601String() }}">
                        {{ $heroPost->published_at?->translatedFormat('d F Y') }}
                    </time>
                    <span>&bull;</span>
                    <span>{{ $heroPost->reading_time }} {{ __('menit baca') }}</span>
                </div>

                <div class="pt-2">
                    <span class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 font-semibold group-hover:gap-3 transition-all">
                        {{ __('Baca Selengkapnya') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </span>
                </div>
            </div>
        </a>
    </div>
</section>
@endif

{{-- ════════════════════════════════════════════ --}}
{{--  MAIN — Sidebar + Grid                       --}}
{{-- ════════════════════════════════════════════ --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-12">

        {{-- ── KONTEN UTAMA ── --}}
        <div class="flex-1 min-w-0">

            {{-- Header section --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    @if(request()->filled('cari'))
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ __('Hasil pencarian:') }} "<span class="text-blue-600 dark:text-blue-400">{{ request('cari') }}</span>"
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $posts->total() }} {{ __('artikel ditemukan') }}</p>
                    @elseif(request()->filled('kategori'))
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Kategori:') }} <span class="text-blue-600 dark:text-blue-400">{{ request('kategori') }}</span></h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $posts->total() }} {{ __('artikel') }}</p>
                    @else
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Berita Terbaru') }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $posts->total() }} {{ __('artikel tersedia') }}</p>
                    @endif
                </div>

                @if(request()->filled('cari') || request()->filled('kategori'))
                    <a href="{{ route('posts.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('Reset filter') }}
                    </a>
                @endif
            </div>

            {{-- Grid Artikel --}}
            @if($posts->isEmpty())
                <div class="text-center py-24 text-gray-400 dark:text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-200 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-lg font-medium dark:text-gray-300">{{ __('Belum ada artikel') }}</p>
                    <p class="text-sm mt-1">{{ __('Coba kata kunci atau kategori lain.') }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <x-post-card :post="$post"/>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $posts->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>

        {{-- ── SIDEBAR ── --}}
        <aside class="lg:w-72 shrink-0 space-y-8">

            {{-- Search (mobile) --}}
            <div class="lg:hidden">
                <form action="{{ route('posts.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="cari" value="{{ request('cari') }}" placeholder="{{ __('Cari berita...') }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm dark:text-white transition-colors duration-300">
                        <svg class="absolute left-3 top-3.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </form>
            </div>

            {{-- Kategori --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                    {{ __('Kategori') }}
                </h3>
                <ul class="space-y-2">
                    @forelse($categories as $cat)
                        <li>
                            <a href="{{ route('posts.index', ['kategori' => $cat->slug]) }}"
                               class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition group {{ request('kategori') === $cat->slug ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'text-gray-600 dark:text-gray-300' }}">
                                <span class="flex items-center gap-2 text-sm font-medium">
                                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $cat->color }}"></span>
                                    {{ $cat->name }}
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-full group-hover:bg-blue-100 dark:group-hover:bg-blue-900/50">
                                    {{ $cat->posts_count }}
                                </span>
                            </a>
                        </li>
                    @empty
                        <li class="text-sm text-gray-400 dark:text-gray-500">{{ __('Belum ada kategori.') }}</li>
                    @endforelse
                </ul>
            </div>

        </aside>
    </div>
</div>

@endsection
