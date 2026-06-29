@props(['post', 'compact' => false])

<a href="{{ route('posts.show', $post->slug) }}"
   class="group flex flex-col bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300">

    {{-- Gambar --}}
    <div class="relative overflow-hidden {{ $compact ? 'aspect-video' : 'aspect-video' }} bg-gray-100 dark:bg-gray-700">
        @if($post->featured_image_url)
            <img
                src="{{ $post->featured_image_url }}"
                alt="{{ $post->title }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
            >
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                <svg class="w-12 h-12 text-white/40" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
            </div>
        @endif

        {{-- Category badge --}}
        @if($post->category)
            <div class="absolute top-3 left-3">
                <span class="text-xs font-semibold text-white px-2 py-1 rounded-full"
                      style="background-color: {{ $post->category->color }}">
                    {{ $post->category->name }}
                </span>
            </div>
        @endif
    </div>

    {{-- Konten --}}
    <div class="p-5 flex flex-col flex-1">
        <h3 class="{{ $compact ? 'text-sm' : 'text-base' }} font-bold text-gray-900 dark:text-white leading-snug group-hover:text-blue-700 dark:group-hover:text-blue-400 transition-colors line-clamp-2 mb-2">
            {{ $post->title }}
        </h3>

        @if(!$compact && $post->meta_description)
            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-3">
                {{ $post->meta_description }}
            </p>
        @endif

        <div class="mt-auto flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 pt-3 border-t border-gray-50 dark:border-gray-700">
            <span class="font-medium truncate max-w-[120px]">{{ $post->author?->name ?? __('Redaksi') }}</span>
            <div class="flex items-center gap-3 shrink-0">
                <time datetime="{{ $post->published_at?->toIso8601String() }}">
                    {{ $post->published_at?->diffForHumans() }}
                </time>
                <span>{{ $post->reading_time }}m</span>
            </div>
        </div>
    </div>
</a>
