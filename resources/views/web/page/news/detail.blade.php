@extends('web.master')

@section('title', $title ?? config('texts.news_detail_title'))

@section('meta')
    @php
        $seo = $seoData ?? [];
        $seoDescription = $seo['description'] ?? '';
        $seoKeywords = $seo['keywords'] ?? null;
        $seoTitle = $seo['title'] ?? ($title ?? config('texts.news_detail_title'));
        $canonicalUrl = $seo['canonicalUrl'] ?? route('new.detail', [$news->alias]);
        $imageUrl = $seo['imageUrl'] ?? '';
        $schemaData = $seo['schemaData'] ?? [];

        $decodeCkeditorEmbeds = function ($html) {
            if (!is_string($html) || $html === '') {
                return $html;
            }

            $normalizeYoutubeUrl = function ($url) {
                if (!is_string($url) || $url === '') {
                    return $url;
                }
                $parts = parse_url($url);
                if (!$parts || empty($parts['host'])) {
                    return $url;
                }
                $host = strtolower($parts['host']);
                $videoId = null;

                if (strpos($host, 'youtu.be') !== false) {
                    $videoId = ltrim($parts['path'] ?? '', '/');
                } elseif (strpos($host, 'youtube.com') !== false) {
                    $path = $parts['path'] ?? '';
                    if (strpos($path, '/embed/') === 0) {
                        $videoId = substr($path, strlen('/embed/'));
                    } elseif (strpos($path, '/shorts/') === 0) {
                        $videoId = substr($path, strlen('/shorts/'));
                    } elseif (strpos($path, '/watch') === 0) {
                        parse_str($parts['query'] ?? '', $query);
                        $videoId = $query['v'] ?? null;
                    }
                }

                if (!$videoId) {
                    return $url;
                }

                $videoId = preg_replace('/[^a-zA-Z0-9_-]/', '', $videoId);
                if ($videoId === '') {
                    return $url;
                }

                return 'https://www.youtube.com/embed/' . $videoId;
            };

            $decoded = preg_replace_callback(
                '/<img[^>]+data-cke-realelement=(["\'])(.*?)\1[^>]*>/i',
                function ($matches) {
                    $decoded = rawurldecode($matches[2]);
                    return htmlspecialchars_decode($decoded, ENT_QUOTES);
                },
                $html
            );

            // Remove sandbox attribute to avoid blocking iframe scripts
            $decoded = preg_replace('/\s+sandbox(=([\"\']).*?\2)?/i', '', $decoded);

            // Normalize YouTube URLs to embed form
            $decoded = preg_replace_callback(
                '/<iframe[^>]+src=(["\'])(.*?)\1[^>]*>/i',
                function ($matches) use ($normalizeYoutubeUrl) {
                    $originalUrl = $matches[2];
                    $normalized = $normalizeYoutubeUrl($originalUrl);
                    if ($normalized === $originalUrl) {
                        return $matches[0];
                    }
                    return str_replace($originalUrl, $normalized, $matches[0]);
                },
                $decoded
            );

            return $decoded;
        };
    @endphp
    @if(!empty($seoDescription))
        <meta name="description" content="{{ trim(strip_tags($seoDescription)) }}">
    @endif
    @if(!empty($seoKeywords))
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endif

    {{-- Canonical --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $seoTitle }}">
    @if(!empty($seoDescription))
        <meta property="og:description" content="{{ trim(strip_tags($seoDescription)) }}">
    @endif
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ config('texts.page_title') }}">
    @if(!empty($imageUrl))
        <meta property="og:image" content="{{ $imageUrl }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    @if(!empty($seoDescription))
        <meta name="twitter:description" content="{{ trim(strip_tags($seoDescription)) }}">
    @endif
    @if(!empty($imageUrl))
        <meta name="twitter:image" content="{{ $imageUrl }}">
    @endif

    {{-- Structured data Article --}}
    @if(!empty($schemaData))
    <script type="application/ld+json">
        {!! json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    @endif
@endsection

@section('content')

<main class="container mx-auto px-4 py-8">
    {{-- Breadcrumb Component --}}
    @include('web.partials.breadcrumb')
    
    {{-- Hero chi tiết bài viết: dùng lại style news-hero --}}
    <section class="news-hero mb-8">
        <span
            class="inline-flex items-center gap-2 px-3 py-1 text-sm font-semibold text-red-600 bg-white/70 border border-red-200 rounded-full mb-4 shadow-sm w-fit">
            📰 {{ $relatedCategory->name ?? config('texts.news_detail_category') }}
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold leading-snug text-slate-900">
            {{ $news->title ?? $news->name }}
        </h1>

        @php
            $dateText = $news->created_at && $news->created_at->isValid() 
                ? $news->created_at->format('d/m/Y') 
                : null;
        @endphp

        <div class="mt-4 flex flex-wrap gap-3 items-center text-sm text-slate-600">
            @if($dateText)
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 8v4l2.5 2.5M5 12a7 7 0 1114 0 7 7 0 01-14 0z" />
                    </svg>
                    {{ $dateText }}
                </span>
            @endif

            @if(!empty($news->author))
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M5.121 17.804A7 7 0 0112 15a7 7 0 016.879 2.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $news->author }}
                </span>
            @endif

            @if(!empty($news->view))
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    {{ number_format($news->view) }} {{ config('texts.news_detail_views') }}
                </span>
            @endif
        </div>
       
    </section>

    {{-- Layout: nội dung + sidebar giống tinh thần new-category --}}
    <section class="news-layout">
        <article class="bg-white rounded-3xl shadow-xl overflow-hidden">
            {{-- Nội dung bài viết --}}
            <div class="px-5 sm:px-8 md:px-10 py-8 md:py-10 news-detail-content">
                {!! $decodeCkeditorEmbeds($news->content) !!}
            </div>

            {{-- Box "Từ khóa / chia sẻ" đơn giản --}}
            <div class="px-5 sm:px-8 md:px-10 pb-8 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100">
                <div class="flex flex-wrap gap-2 items-center text-sm text-slate-600">
                    <span class="font-semibold text-slate-800">{{ config('texts.news_detail_share') }}:</span>
                    <ul class="flex items-center">
                        @php
                            $shareUrl = $canonicalUrl ?? route('new.detail', [$news->alias]);
                        @endphp
                        <a target="_blank" rel="noopener noreferrer"
                           href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}"
                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            {{ config('texts.news_detail_share') }}
                        </a>
                    </ul>
                </div>

                @if($relatedCategory)
                    <div class="text-xs sm:text-sm text-slate-500">
                        {{ config('texts.news_detail_category_label') }}
                        <span class="font-semibold text-red-600">
                            {{ $relatedCategory->name ?? $relatedCategory->title }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Tin liên quan --}}
            @if(isset($relatedNewsData) && $relatedNewsData->count())
                <div class="border-t border-slate-100 px-5 sm:px-8 md:px-10 pt-7 pb-8">
                    <h2 class="text-lg md:text-xl font-bold text-slate-900 mb-4">
                        {{ config('texts.news_detail_related_title') }}
                    </h2>
                    <div class="swiper-container news-slider">
                        <div class="swiper-wrapper">
                            @foreach($relatedNewsData as $item)
                            <div class="swiper-slide">
                                <article
                                    class="border rounded-3xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white flex flex-col group">
                                    @if(!empty($item['imageUrl']))
                                        <a href="{{ $item['detailUrl'] }}" class="block">
                                            <img src="{{ $item['imageUrl'] }}" 
                                                 alt="{{ $item['title'] }}"
                                                 loading="lazy"
                                                 width="400"
                                                 height="208"
                                                 class="w-full h-52 object-cover">
                                        </a>
                                    @endif
                                    <div class="p-5 md:p-6 flex flex-col gap-3 flex-1">
                                        <div>
                                            <span
                                                class="inline-block text-xs font-semibold px-4 py-1 rounded-full mb-3"
                                                style="background-color: #fee2e2; color: #ed1c24;">
                                                {{ $item['categoryName'] }}
                                            </span>
                                            <a href="{{ $item['detailUrl'] }}">
                                                <h3 class="font-bold text-lg leading-snug mb-2 text-slate-900 line-clamp-2 hover:text-red-600 transition-colors">
                                                    {{ $item['title'] }}
                                                </h3>
                                            </a>
                                            @if(!empty($item['excerpt']))
                                                <p class="text-sm text-slate-500 leading-relaxed line-clamp-2">
                                                    {{ $item['excerpt'] }}
                                                </p>
                                            @endif
                                        </div>
                                        @if(!empty($item['date']))
                                            <div
                                                class="mt-auto pt-2 text-xs md:text-sm text-slate-500 flex items-center flex-wrap gap-2">
                                                <span>{{ $item['date'] }}</span>
                                                <span>• {{ config('texts.news_detail_read_time') }}</span>
                                                <a href="{{ $item['detailUrl'] }}"
                                                   class="font-semibold text-red-500 ml-auto hover:underline">
                                                    {{ config('texts.news_detail_read_more') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </article>

    </section>
    
</main>

@push('scripts')
<script>
    // Khởi tạo Swiper cho tin liên quan khi DOM ready - chỉ khi có slides
    (function() {
        function initNewsSlider() {
            const newsSliderEl = document.querySelector('.news-slider');
            if (!newsSliderEl) return;
            
            const slides = newsSliderEl.querySelectorAll('.swiper-slide');
            if (slides.length === 0) return;
            
            if (typeof Swiper !== 'undefined') {
                new Swiper(newsSliderEl, {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: false,
                    autoplay: slides.length > 1 ? {
                        delay: 4000,
                        disableOnInteraction: false,
                    } : false,
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 24,
                        },
                    },
                });
            } else {
                // Retry nếu Swiper chưa load
                setTimeout(initNewsSlider, 100);
            }
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initNewsSlider);
        } else {
            initNewsSlider();
        }
    })();
</script>
@endpush

@endsection