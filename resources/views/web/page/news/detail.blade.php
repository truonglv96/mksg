@extends('web.master')

@section('title', $title ?? 'Tin Tức - Mắt Kính Sài Gòn')

@section('meta')
    @php
        use Illuminate\Support\Str;

        $seoDescription = $news->meta_description
            ?? $news->description
            ?? Str::limit(strip_tags($news->content ?? ''), 160);

        $seoKeywords = $news->kw
            ?? $news->keyword
            ?? $news->meta_keyword
            ?? null;

        $seoTitle = $news->title ?? $news->name ?? ($title ?? 'Tin Tức - Mắt Kính Sài Gòn');
        $canonicalUrl = route('new.detail', [$news->alias]);
        $imageUrl = method_exists($news, 'getImage') ? $news->getImage() : '';
        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => Str::limit(trim(strip_tags($seoTitle)), 110, ''),
            'description' => Str::limit(trim(strip_tags($seoDescription ?? '')), 160, ''),
            'datePublished' => optional($news->created_at)->toIso8601String(),
            'dateModified' => optional($news->updated_at ?? $news->created_at)->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $news->author ?? 'Mắt Kính Sài Gòn',
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $canonicalUrl,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Mắt Kính Sài Gòn',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('img/setting/logo_mksg_2025.png'),
                ],
            ],
        ];
        if (!empty($imageUrl)) {
            $schemaData['image'] = $imageUrl;
        }
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
    <meta property="og:site_name" content="Mắt Kính Sài Gòn">
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
    <script type="application/ld+json">
        {!! json_encode($schemaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('content')

<main class="container mx-auto px-4 py-8">
    {{-- Hero chi tiết bài viết: dùng lại style news-hero --}}
    <section class="news-hero mb-8">
        <span
            class="inline-flex items-center gap-2 px-3 py-1 text-sm font-semibold text-red-600 bg-white/70 border border-red-200 rounded-full mb-4 shadow-sm w-fit">
            📰 {{ $relatedCategory->name ?? 'Tin tức' }}
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold leading-snug text-slate-900">
            {{ $news->title ?? $news->name }}
        </h1>

        @php
            $created = optional($news->created_at);
            $dateText = $created->isValid() ? $created->format('d/m/Y') : null;
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
                    {{ number_format($news->view) }} lượt xem
                </span>
            @endif
        </div>
       
    </section>

    {{-- Layout: nội dung + sidebar giống tinh thần new-category --}}
    <section class="news-layout">
        <article class="bg-white rounded-3xl shadow-xl overflow-hidden">
            {{-- Nội dung bài viết --}}
            <div class="px-5 sm:px-8 md:px-10 py-8 md:py-10 news-detail-content">
                {!! $news->content !!}
            </div>

            {{-- Box “Từ khóa / chia sẻ” đơn giản --}}
            <div class="px-5 sm:px-8 md:px-10 pb-8 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100">
                <div class="flex flex-wrap gap-2 items-center text-sm text-slate-600">
                    <span class="font-semibold text-slate-800">Chia sẻ:</span>
                    <ul class="flex items-center">
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous"
                            src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v15.0"></script>
                        <div class="fb-share-button"
                            data-href="{{ route('new.detail', [$news->alias]) }}"
                            data-layout="button_count" data-size="large">
                            <a target="_blank"
                               href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('new.detail', [$news->alias])) }}"
                               class="fb-xfbml-parse-ignore">Chia sẻ</a>
                        </div>
                    </ul>
                </div>

                @if($relatedCategory)
                    <div class="text-xs sm:text-sm text-slate-500">
                        Thuộc chuyên mục:
                        <span class="font-semibold text-red-600">
                            {{ $relatedCategory->name ?? $relatedCategory->title }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Tin liên quan --}}
            @if(isset($relatedNews) && $relatedNews->count())
                <div class="border-t border-slate-100 px-5 sm:px-8 md:px-10 pt-7 pb-8">
                    <h2 class="text-lg md:text-xl font-bold text-slate-900 mb-4">
                        Tin liên quan
                    </h2>
                    <div class="swiper-container news-slider">
                        <div class="swiper-wrapper">
                            @foreach($relatedNews as $item)
                            @php
                                $createdAt = optional($item->created_at);
                                $itemDate = $createdAt->isValid() ? $createdAt->format('d/m/Y') : null;
                                $excerptSource = $item->description ?? $item->content ?? '';
                                $excerpt = \Illuminate\Support\Str::limit(strip_tags($excerptSource), 90);
                            @endphp
                            <div class="swiper-slide">
                                <article
                                    class="border rounded-3xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white flex flex-col group">
                                    @if(method_exists($item, 'getImage') && $item->getImage())
                                        <a href="{{ route('new.detail', $item->alias) }}" class="block">
                                            <img src="{{ $item->getImage() }}" alt="{{ $item->title ?? $item->name }}"
                                                 class="w-full h-52 object-cover">
                                        </a>
                                    @endif
                                    <div class="p-5 md:p-6 flex flex-col gap-3 flex-1">
                                        <div>
                                            <span
                                                class="inline-block text-xs font-semibold px-4 py-1 rounded-full mb-3"
                                                style="background-color: #fee2e2; color: #ed1c24;">
                                                {{ $relatedCategory->name ?? 'Tin tức' }}
                                            </span>
                                            <a href="{{ route('new.detail', $item->alias) }}">
                                                <h3 class="font-bold text-lg leading-snug mb-2 text-slate-900 line-clamp-2"
                                                    style="transition: color 0.3s;"
                                                    onmouseover="this.style.color='#ed1c24'"
                                                    onmouseout="this.style.color='#111827'">
                                                    {{ $item->title ?? $item->name }}
                                                </h3>
                                            </a>
                                            @if($excerpt)
                                                <p class="text-sm text-slate-500 leading-relaxed line-clamp-2">
                                                    {!! $excerpt !!}
                                                </p>
                                            @endif
                                        </div>
                                        @if($itemDate)
                                            <div
                                                class="mt-auto pt-2 text-xs md:text-sm text-slate-500 flex items-center flex-wrap gap-2">
                                                <span>{{ $itemDate }}</span>
                                                <span>• 5 phút đọc</span>
                                                <a href="{{ route('new.detail', $item->alias) }}"
                                                   class="font-semibold text-red-500 ml-auto">
                                                    Đọc tiếp →
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

@endsection