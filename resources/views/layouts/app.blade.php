<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth" :class="{ 'dark': darkMode }" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- ── Dynamic Title & Meta ── --}}
    <title>@yield('title', config('app.name', 'Web Berita'))</title>
    <meta name="description" content="@yield('meta_description', __('Portal berita terpercaya yang menyajikan informasi terkini, terlengkap, dan terpercaya untuk masyarakat Indonesia.'))">
    <meta name="keywords"    content="@yield('meta_keywords', 'berita, terkini, indonesia')">
    <meta name="robots"      content="index, follow">
    <link rel="canonical"    href="@yield('canonical', url()->current())">

    {{-- ── Open Graph ── --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:title"       content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', __('Portal berita terpercaya yang menyajikan informasi terkini, terlengkap, dan terpercaya untuk masyarakat Indonesia.'))">
    <meta property="og:image"       content="@yield('og_image', asset('og-default.png'))">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:site_name"   content="{{ config('app.name') }}">

    {{-- ── Twitter Card ── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('og_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('og_description', __('Portal berita terpercaya yang menyajikan informasi terkini, terlengkap, dan terpercaya untuk masyarakat Indonesia.'))">
    <meta name="twitter:image"       content="@yield('og_image', asset('og-default.png'))">

    {{-- ── JSON-LD Schema (diisi per halaman) ── --}}
    @stack('schema')

    {{-- ── Tailwind CSS CDN ── --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Merriweather', 'serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>

    {{-- ── Alpine.js ── --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- ── Google Fonts ── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Merriweather', serif; }
        .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
        .prose-content img { border-radius: .75rem; width: 100%; }
        .prose-content h2 { font-size: 1.5rem; font-weight: 700; margin: 2rem 0 .75rem; }
        .prose-content h3 { font-size: 1.25rem; font-weight: 600; margin: 1.5rem 0 .5rem; }
        .prose-content p  { margin-bottom: 1.25rem; line-height: 1.8; color: inherit; }
        .prose-content a  { color: #3b82f6; text-decoration: underline; }
        .prose-content blockquote { border-left: 4px solid #3b82f6; padding-left: 1rem; color: #6b7280; font-style: italic; margin: 1.5rem 0; }
        .prose-content ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; }
        .prose-content ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 1.25rem; }
        .prose-content li { margin-bottom: .5rem; line-height: 1.7; }
        /* Dark mode overrides for prose */
        .dark .prose-content { color: #d1d5db; }
        .dark .prose-content h2, .dark .prose-content h3 { color: #f9fafb; }
        .dark .prose-content blockquote { color: #9ca3af; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 antialiased transition-colors duration-300">

    {{-- ── NAVBAR ── --}}
    <header class="sticky top-0 z-50 bg-white/95 dark:bg-gray-800/95 backdrop-blur border-b border-gray-100 dark:border-gray-700 shadow-sm transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('posts.index') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name', 'WebBerita') }}</span>
                </a>

                {{-- Search --}}
                <form action="{{ route('posts.index') }}" method="GET" class="hidden md:flex items-center gap-2 flex-1 max-w-md mx-8">
                    <div class="relative w-full">
                        <input
                            type="text"
                            name="cari"
                            value="{{ request('cari') }}"
                            placeholder="{{ __('Cari berita...') }}"
                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 dark:border-gray-600 rounded-full bg-gray-50 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-800 transition"
                        >
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </form>

                {{-- Nav links & Toggles --}}
                <nav class="flex items-center gap-4">
                    <a href="{{ route('posts.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">{{ __('Beranda') }}</a>
                    
                    {{-- Language Toggle --}}
                    <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-full p-0.5">
                        <a href="{{ route('lang.switch', 'id') }}" class="px-2 py-1 text-xs font-semibold rounded-full transition-colors {{ app()->getLocale() === 'id' ? 'bg-white dark:bg-gray-600 shadow text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">ID</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs font-semibold rounded-full transition-colors {{ app()->getLocale() === 'en' ? 'bg-white dark:bg-gray-600 shadow text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">EN</a>
                    </div>

                    {{-- Dark Mode Toggle --}}
                    <button @click="darkMode = !darkMode" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </header>

    {{-- ── MAIN CONTENT ── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── FOOTER ── --}}
    <footer class="bg-gray-900 dark:bg-black text-gray-300 mt-20 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 bg-blue-500 rounded-md flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="font-bold text-white">{{ config('app.name') }}</span>
                    </div>
                    <p class="text-sm leading-relaxed">{{ __('Portal berita terpercaya yang menyajikan informasi terkini, terlengkap, dan terpercaya untuk masyarakat Indonesia.') }}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">{{ __('Navigasi') }}</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('posts.index') }}" class="hover:text-blue-400 transition">{{ __('Beranda') }}</a></li>
                        {{-- Login admin disembunyikan sesuai permintaan --}}
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">{{ __('Tentang') }}</h4>
                    <p class="text-sm leading-relaxed mb-4">{{ __('Dibangun dengan Laravel 11 + FilamentPHP. Menyajikan berita dengan tampilan modern dan SEO-friendly.') }}</p>
                    <p class="text-sm text-blue-400">{{ __('Dibuat oleh Rifqi Al Faridzi') }}</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>
