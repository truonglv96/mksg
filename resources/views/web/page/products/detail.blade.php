@extends('web.master')

@section('title', $title ?? config('texts.product_detail_title'))

@section('meta')
    @php
        use Illuminate\Support\Str;

        $productName = $product->name ?? config('texts.product_fallback');
        $brandName = $brand->name
            ?? ($product->brand->name ?? null)
            ?? null;

        $seoTitle = $title
            ?? ($productName . ($brandName ? ' - ' . $brandName : '') . ' | Mắt Kính Sài Gòn');

        $extractText = function ($value) {
            if (!is_string($value)) {
                return $value;
            }
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($decoded)) {
                    return $decoded['text'] ?? $decoded['value'] ?? '';
                }
                if (is_string($decoded)) {
                    $decodedInner = json_decode($decoded, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decodedInner)) {
                        return $decodedInner['text'] ?? $decodedInner['value'] ?? '';
                    }
                }
            }
            return $value;
        };

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
            // Xử lý nhiều trường hợp: sandbox, sandbox="", sandbox='', sandbox="allow-same-origin", etc.
            $decoded = preg_replace('/\s+sandbox\s*=\s*["\'][^"\']*["\']/i', '', $decoded);
            $decoded = preg_replace('/\s+sandbox\s*(?=\s|>)/i', '', $decoded);

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

        $rawDescription = $extractText($product->meta_description ?? null);
        if (empty($rawDescription) && !empty($product->description)) {
            $rawDescription = $extractText($product->description);
        }
        $rawDescription = $rawDescription
            ?? $content->text
            ?? $tech->text
            ?? '';

        $seoDescription = trim(strip_tags($rawDescription));
        if ($seoDescription === '') {
            $fallback = trim(strip_tags($settings->meta_description ?? ''));
            $seoDescription = $fallback ?: ($productName . ($brandName ? ' - ' . $brandName : ''));
        }
        $seoDescription = Str::limit($seoDescription, 160);

        $seoKeywords = $product->kw
            ?? $product->keyword
            ?? $product->meta_keyword
            ?? ($brandName ? $productName . ', ' . $brandName : null);

        $canonicalUrl = route('product.detail', [
            'categoryPath' => isset($mainCategory) && $mainCategory ? $product->getCategoryPath() : '',
            'productAlias' => $product->alias,
        ]);

        $imageUrl = isset($productImages) && $productImages && $productImages->first()
            ? asset('img/product/' . $productImages->first()->image)
            : asset('img/product/no-image.png');

        $currentPrice = $product->price_sale ?? $product->price ?? 0;
        $oldPrice = $product->price ?? 0;
        $hasDiscount = $product->price_sale && $product->price && $product->price > $product->price_sale;

        $schemaProduct = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $productName,
            'description' => Str::limit(trim(strip_tags($seoDescription ?? '')), 200, ''),
            'image' => [$imageUrl],
            'sku' => $product->code ?? null,
            'brand' => $brandName ? [
                '@type' => 'Brand',
                'name' => $brandName,
            ] : null,
            'offers' => [
                '@type' => 'Offer',
                'url' => $canonicalUrl,
                'priceCurrency' => 'VND',
                'price' => $currentPrice > 0 ? $currentPrice : null,
                'availability' => 'https://schema.org/InStock',
            ],
        ];

        // Loại bỏ key null để JSON sạch hơn
        $schemaProduct = array_filter($schemaProduct, function ($value) {
            return !is_null($value);
        });
        if (isset($schemaProduct['brand'])) {
            $schemaProduct['brand'] = array_filter($schemaProduct['brand'], function ($value) {
                return !is_null($value);
            });
        }
        if (isset($schemaProduct['offers'])) {
            $schemaProduct['offers'] = array_filter($schemaProduct['offers'], function ($value) {
                return !is_null($value);
            });
        }
    @endphp

    @if(!empty($seoDescription))
        <meta name="description" content="{{ strip_tags($seoDescription) }}">
    @endif
    @if(!empty($seoKeywords))
        <meta name="keywords" content="{{ $seoKeywords }}">
    @endif

    {{-- Canonical --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="product">
    <meta property="og:title" content="{{ $seoTitle }}">
    @if(!empty($seoDescription))
        <meta property="og:description" content="{!! strip_tags($seoDescription) !!}">
    @endif
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="Mắt Kính Sài Gòn">
    <meta property="og:image" content="{{ $imageUrl }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    @if(!empty($seoDescription))
        <meta name="twitter:description" content="{!! strip_tags($seoDescription) !!}">
    @endif
    <meta name="twitter:image" content="{{ $imageUrl }}">

    {{-- Product structured data --}}
    <script type="application/ld+json">
        {!! json_encode($schemaProduct, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('content')
    <main class="container mx-auto px-4 py-8 overflow-x-hidden">

        {{-- Breadcrumb Component --}}
        @include('web.partials.breadcrumb')

        <!-- Product Summary -->
        @php
            $priceSaleSelectLabel = (int)($product->type_color ?? 0) === 1
                ? config('texts.product_option_type_discount')
                : config('texts.product_option_type_color');
        @endphp
        <section id="product-summary" class="product-summary bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8 mb-10 overflow-x-hidden" data-product-id="{{ $product->id }}" data-product-unit="{{ $product->unit ?? '' }}" data-product-brand="{{ ($brand && $brand->name) ? $brand->name : (($product->brand && $product->brand->name) ? $product->brand->name : '') }}" data-price-sale-option-label="{{ e($priceSaleSelectLabel) }}">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 xl:gap-10">
                <!-- Gallery -->
                <div>
                    @php
                        $mainImage = isset($productImages) && $productImages ? $productImages->first() : null;
                        $mainImageUrl = $mainImage ? asset('img/product/' . $mainImage->image) : asset('img/product/no-image.png');
                    @endphp
                    <button type="button" id="lightbox-trigger"
                        class="relative w-full bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden group cursor-zoom-in focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                        aria-label="{{ config('texts.product_lightbox_zoom') }}">
                        <img id="main-product-image"
                            src="{{ $mainImageUrl }}"
                            alt="{{ $product->name }}"
                            class="w-full h-auto object-contain transition-transform duration-500 ease-out group-hover:scale-110">
                        @if(isset($product) && $product->price_sale && $product->price && $product->price > $product->price_sale)
                            @php
                                $discount = round((($product->price - $product->price_sale) / $product->price) * 100);
                            @endphp
                            
                        @endif
                    </button>
                    @if(isset($productImages) && $productImages && $productImages->count() > 0)
                    <div class="flex gap-3 mt-4 overflow-x-auto pb-1">
                        @foreach($productImages as $image)
                        <button type="button" data-image-src="{{ asset('img/product/' . $image->image) }}"
                            class="thumbnail-button flex-shrink-0 border-2 border-transparent rounded-xl overflow-hidden w-24 h-24">
                            <img src="{{ asset('img/product/' . $image->image) }}"
                                alt="{{ $product->name }} - Hình {{ $loop->iteration }}" class="w-full h-full object-contain">
                        </button>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        @foreach($summaryHighlights as $item)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 shadow-sm">
                                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-red-50 text-red-600">
                                    {{ $item['icon'] ?? '•' }}
                                </span>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $item['title'] ?? '' }}</p>
                                    @if(!empty($item['description']))
                                        <p class="text-xs text-gray-500">{{ $item['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Product content -->
                <div class="space-y-4">
                    <!-- Product Header -->
                    <div class="space-y-2 relative">
                        <h1 id="product-name" class="text-[22px] font-bold text-gray-900 leading-tight">
                            {{ $product->name }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-base text-gray-600">
                            <!-- <div class="flex items-center gap-1 text-yellow-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.802-2.034a1 1 0 00-1.175 0l-2.802 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.463a1 1 0 00.95-.69l1.068-3.292z" />
                                </svg>
                                4.8 (128 đánh giá)
                            </div> -->
                            @if($product->code)
                            <div class="flex items-center gap-1.5 min-w-0 flex-shrink">
                                <label for="code-select" class="text-sm sm:text-base font-bold text-gray-800 uppercase tracking-wide whitespace-nowrap flex-shrink-0">{{ config('texts.product_code') }}:</label>
                                <span class="font-semibold text-gray-800 truncate">{{ $product->code }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-1.5 min-w-0 flex-shrink">
                                <label for="brand-select" class="text-sm sm:text-base font-bold text-gray-800 uppercase tracking-wide whitespace-nowrap flex-shrink-0">{{ config('texts.product_brand') }}</label>
                                <span id="product-brand" class="font-semibold text-red-600 truncate">
                                @if($brand && $brand->name)
                                    {{ $brand->name }}
                                @elseif($product->brand && $product->brand->name)
                                    {{ $product->brand->name }}
                                @endif
                                </span>
                            </div>
                            @if($product->unit)
                            <div class="flex items-center gap-1.5 min-w-0 flex-shrink">
                                <label for="unit-select" class="text-sm sm:text-base font-bold text-gray-800 uppercase tracking-wide whitespace-nowrap flex-shrink-0">Đơn vị:</label>
                                <span id="product-unit" class="font-semibold text-red-600 truncate">{{ $product->unit }}</span>
                            </div>
                            @endif
                            @if(isset($product->type_sale))
                            <div class="flex items-center gap-1.5 min-w-0 flex-shrink">
                                <label for="type-sale-select" class="text-sm sm:text-base font-bold text-gray-800 uppercase tracking-wide whitespace-nowrap flex-shrink-0">Hình thức:</label>
                                <span class="font-semibold text-red-600 truncate">
                                    @if($product->type_sale == -1)
                                        Tại Shop & Online
                                    @elseif($product->type_sale == 0)
                                        Tại Shop
                                    @elseif($product->type_sale == 1)
                                        Online
                                    @else
                                        {{ $product->type_sale }}
                                    @endif
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="social-sharing inline-flex items-center absolute top-2 right-2 md:top-[-60px] md:right-[-20px]">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('product.detail', ['categoryPath' => $mainCategory ? $product->getCategoryPath() : '', 'productAlias' => $product->alias])) }}"
                               class="inline-flex items-center"
                               onclick="window.open(this.href, 'fbshare', 'width=640,height=480'); return false;">
                                <img src="{{ asset('img/tmp/pngtree-facebook-like-share-icon-button-png-image_1805237.png') }}"
                                     alt="Facebook Like &amp; Share"
                                     class="h-10 sm:h-12 md:h-20 w-auto object-contain">
                            </a>
                        </div>
                    </div>

                    <!-- Price Section -->
                    <div class="bg-gradient-to-br from-red-50 via-red-50/50 to-white border border-red-200 rounded-xl p-3 sm:p-4">
                        <div class="flex items-baseline gap-2.5 flex-wrap">
                            @php
                                $currentPrice = $product->price_sale ?? $product->price ?? 0;
                                $oldPrice = $product->price ?? 0;
                                $hasDiscount = $product->price_sale && $product->price && $product->price > $product->price_sale;
                            @endphp
                            <span class="price-current text-2xl sm:text-3xl font-bold text-red-600" data-product-price data-base-price="{{ $currentPrice }}">{{ number_format($currentPrice, 0, ',', '.') }} {{ config('texts.currency') }}</span>
                            @if($hasDiscount)
                            <div class="flex items-center gap-2.5 flex-wrap">
                            <span class="price-old text-sm text-gray-500 line-through">{{ number_format($oldPrice, 0, ',', '.') }} {{ config('texts.currency') }}</span>
                            @php
                                $savingPercent = round((($oldPrice - $currentPrice) / $oldPrice) * 100);
                            @endphp
                                <span class="price-saving inline-flex items-center gap-1.5 text-base font-bold text-white bg-gradient-to-r from-red-500 via-red-600 to-red-700 px-3 py-1.5 rounded-full shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all duration-300 hover:scale-105">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                    <span>{{ config('texts.product_save') }} {{ $savingPercent }}%</span>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Options -->
                    <div class="space-y-3">
                        @php
                            $visiblePriceSales = collect(isset($productPriceSales) ? $productPriceSales : [])->filter(function ($priceSale) {
                                $categoryName = '';
                                if (!empty($priceSale->category) && !empty($priceSale->category->name)) {
                                    $categoryName = trim((string) $priceSale->category->name);
                                } elseif (!empty($priceSale->mainCategory) && !empty($priceSale->mainCategory->name)) {
                                    $categoryName = trim((string) $priceSale->mainCategory->name);
                                }
                                return $categoryName !== '';
                            })->values();

                            $visibleDegreeRanges = collect(isset($productDegreeRanges) ? $productDegreeRanges : [])->filter(function ($degreeRange) {
                                return trim((string) ($degreeRange->name ?? '')) !== '';
                            })->values();

                            $visibleCombos = collect(isset($discountedCombos) ? $discountedCombos : [])->filter(function ($combo) {
                                return trim((string) ($combo->name ?? '')) !== '';
                            })->values();
                        @endphp

                        @if($visiblePriceSales->count() > 0 || $visibleDegreeRanges->count() > 0)
                        <div class="rounded-lg border border-gray-200 bg-gray-50/60 p-3 flex flex-col gap-3">
                            @if($visiblePriceSales->count() > 0)
                            <div class="w-full min-w-0 flex flex-row flex-wrap items-center gap-x-3 gap-y-2">
                                <h3 id="price-sale-group-label" class="product-option-field-label m-0 shrink-0 text-[16px] font-black uppercase tracking-wide text-gray-700">{{ $priceSaleSelectLabel }}:</h3>
                                <div class="price-sale-pills flex min-w-0 flex-1 flex-wrap items-center gap-1.5" role="group" aria-labelledby="price-sale-group-label">
                                        @foreach($visiblePriceSales as $priceSale)
                                            @php
                                                $categoryName = '';
                                                if ($priceSale->category && $priceSale->category->name) {
                                                    $categoryName = $priceSale->category->name;
                                                } elseif ($priceSale->mainCategory && $priceSale->mainCategory->name) {
                                                    $categoryName = $priceSale->mainCategory->name;
                                                }
                                                $displayPrice = ((int)($product->type_color ?? 0) === 1 && $loop->first)
                                                    ? 0
                                                    : ($priceSale->discount ?? $priceSale->price ?? 0);
                                                // Chiết suất (type_color=1): chỉ hiển thị phần số/phần sau chữ "Chiết suất", ví dụ "Chiết Suất 1.50" → "1.50"
                                                $priceSaleDisplayName = $categoryName;
                                                if ((int)($product->type_color ?? 0) === 1 && $categoryName !== '') {
                                                    $stripped = preg_replace('/^\s*chiết\s*suất\s*[:,：]?\s*/iu', '', $categoryName);
                                                    $stripped = trim((string) $stripped);
                                                    $priceSaleDisplayName = $stripped !== '' ? $stripped : $categoryName;
                                                }
                                            @endphp
                                            @if($categoryName)
                                            <button type="button"
                                                class="price-sale-pill min-h-9 inline-flex items-center justify-center rounded-md border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-800 transition-colors duration-150 hover:border-red-300 hover:bg-red-50/70 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-1 active:scale-[0.98]"
                                                data-price-sale-id="{{ $priceSale->id }}"
                                                data-price="{{ $displayPrice }}"
                                                data-category="{{ e($priceSaleDisplayName) }}"
                                                aria-pressed="false">
                                                {{ $priceSaleDisplayName }}
                                            </button>
                                            @endif
                                        @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if($visibleDegreeRanges->count() > 0)
                            <div id="degree-range-section" class="w-full min-w-0 space-y-2 {{ $visiblePriceSales->count() > 0 ? 'pt-2 border-t border-gray-200/80' : '' }}">
                                <!-- <label for="degree-range-select" class="product-option-field-label block text-xs font-black uppercase tracking-wide text-gray-700 cursor-pointer">Độ khúc xạ</label> -->
                                <h3 class="product-option-field-label text-[16px] font-black uppercase tracking-wide text-gray-700">Độ khúc xạ</h3>
                                <select id="degree-range-select" 
                                        class="w-full appearance-none rounded-lg border border-gray-200 bg-white py-2 pl-2.5 pr-9 text-xs font-medium text-gray-900 transition-colors hover:border-gray-300 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500 bg-[length:1rem] bg-[right_0.5rem_center] bg-no-repeat"
                                        style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke-width=%271.5%27 stroke=%27%236b7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 d=%27M19.5 8.25l-7.5 7.5-7.5-7.5%27/%3E%3C/svg%3E');">
                                        <option value="" data-price="0">—</option>
                                        @foreach($visibleDegreeRanges as $degreeRange)
                                            @if(!empty($degreeRange->name))
                                            @php
                                                // Ưu tiên price_sale, nếu price_sale = 0 hoặc null thì lấy price
                                                $displayPrice = 0;
                                                if (isset($degreeRange->price_sale) && $degreeRange->price_sale > 0) {
                                                    $displayPrice = $degreeRange->price_sale;
                                                } elseif (isset($degreeRange->price) && $degreeRange->price > 0) {
                                                    $displayPrice = $degreeRange->price;
                                                }
                                            @endphp
                                            <option value="{{ $degreeRange->id }}" 
                                                    data-price="{{ $displayPrice }}"
                                                    data-name="{{ $degreeRange->name }}"
                                                    data-price-sale-id="{{ $degreeRange->price_sale_id }}">
                                                {{ $degreeRange->name }}@if($displayPrice > 0)@endif
                                            </option>
                                            @endif
                                        @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($visibleCombos->count() > 0)
                        <div class="rounded-lg border border-gray-200 bg-gray-50/60 p-3">
                            <div class="space-y-2">
                            <!-- <p class="product-option-field-label block text-xs font-black uppercase tracking-wide text-gray-700">{{ config('texts.product_lens_package') }}</p> -->
                            <!-- <label for="combo-select" class="product-option-field-label block text-xs font-black uppercase tracking-wide text-gray-700 cursor-pointer">{{ config('texts.product_lens_package') }}</label> -->
                            <h3 class="product-option-field-label text-[16px] font-black uppercase tracking-wide text-gray-700">{{ config('texts.product_lens_package') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                                @foreach($visibleCombos as $index => $combo)
                                <button type="button" 
                                    class="option-pill rounded-lg px-2 py-1.5 text-left bg-white border border-gray-200 hover:border-red-400 hover:bg-red-50/50 transition-all duration-200"
                                    data-option="{{ $combo->name }}" 
                                    data-combo-id="{{ $combo->id }}"
                                    data-option-price="{{ $combo->price ?? 0 }}"
                                    aria-pressed="false">
                                    <p class="font-bold text-gray-900 text-base leading-tight">{{ $combo->name }}</p>
                                    @if($combo->description)
                                    <p class="text-base text-gray-600 mt-0.5 leading-tight">{{ $combo->description }}</p>
                                    @endif
                                    @if($combo->price && $combo->price > 0)
                                    <p class="text-base font-bold text-red-600 mt-0.5">+{{ number_format($combo->price, 0, ',', '.') }} {{ config('texts.currency') }}</p>
                                    @endif
                                </button>
                                @endforeach
                            </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Features Product - Clean 2 Column Design -->
                        @if(isset($productFeatures) && $productFeatures->count() > 0)
                        <div class="rounded-lg border border-gray-200 bg-gray-50/60 p-3">
                            <div class="space-y-2">
                            <h3 class="product-option-field-label text-[16px] font-black uppercase tracking-wide text-gray-700">Tính năng</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @php
                                    $featuresArray = $productFeatures->values()->all();
                                    $midPoint = ceil(count($featuresArray) / 2);
                                    $leftColumn = array_slice($featuresArray, 0, $midPoint);
                                    $rightColumn = array_slice($featuresArray, $midPoint);
                                @endphp
                                <div class="space-y-1.5">
                                    @foreach($leftColumn as $feature)
                                    <div class="flex items-center gap-2">
                                        @if($feature->image)
                                        <div class="w-7 h-7 flex-shrink-0 flex items-center justify-center">
                                            <img src="{{ $feature->getImageUrl() }}" alt="{{ $feature->name }}" class="w-full h-full object-contain">
                                        </div>
                                        @else
                                        <div class="w-7 h-7 flex-shrink-0 flex items-center justify-center">
                                            <span class="text-gray-500 text-2xl">📋</span>
                                        </div>
                                        @endif
                                        <p class="text-base text-gray-700 leading-tight">
                                            {{ $feature->name }}
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                                @if(count($rightColumn) > 0)
                                <div class="space-y-1.5">
                                    @foreach($rightColumn as $feature)
                                    <div class="flex items-center gap-2">
                                        @if($feature->image)
                                        <div class="w-7 h-7 flex-shrink-0 flex items-center justify-center">
                                            <img src="{{ $feature->getImageUrl() }}" alt="{{ $feature->name }}" class="w-full h-full object-contain">
                                        </div>
                                        @else
                                        <div class="w-7 h-7 flex-shrink-0 flex items-center justify-center">
                                            <span class="text-gray-500 text-2xl">📋</span>
                                        </div>
                                        @endif
                                        <p class="text-base text-gray-700 leading-tight">
                                            {{ $feature->name }}
                                        </p>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Tổng hợp lựa chọn -->
                    @php
                        $summary = [];
                        $summaryText = implode(' - ', array_filter($summary)) ?: 'Chưa chọn';
                        $summaryCount = count(array_filter($summary));
                        $isInline = $summaryCount <= 1;
                    @endphp
                    <div id="selected-summary-wrapper" class="hidden bg-gradient-to-br from-blue-50 via-blue-50/50 to-indigo-50/30 border border-blue-200/80 rounded-lg p-3 shadow-sm">
                        @if($isInline)
                            <div class="flex items-center gap-2 flex-wrap min-w-0">
                                <span class="text-sm sm:text-base font-semibold text-gray-800 whitespace-nowrap flex-shrink-0">
                                    {{ config('texts.product_selected') }}:
                                </span>
                                <span class="text-sm sm:text-base font-medium text-gray-700 min-w-0 flex-1" id="selected-summary">
                                    {{ $summaryText }}
                                </span>
                            </div>
                        @else
                            <p class="text-base font-semibold text-gray-800 mb-1.5">
                                {{ config('texts.product_selected') }}:
                            </p>
                            <p class="text-base font-medium text-gray-700 leading-relaxed" id="selected-summary">
                                {{ $summaryText }}
                            </p>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2.5">
                        <button
                            class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white py-3 rounded-lg font-bold text-sm hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-md shadow-red-200/50 hover:shadow-lg hover:shadow-red-300/50 add-to-cart-btn">
                            {{ config('texts.product_add_to_cart') }}
                        </button>
                        <button
                            class="flex-1 border-2 border-red-600 text-red-600 py-3 rounded-lg font-bold text-sm hover:bg-red-600 hover:text-white transition-all duration-200 hover:shadow-md hover:shadow-red-200/50 buy-now-btn">
                            {{ config('texts.product_buy_now') }}
                        </button>
                    </div>

                    <div class="support-chips flex flex-wrap items-center gap-3 text-[14px]">
                        <span class="px-3 py-2 rounded-full bg-green-50 text-green-600 font-medium flex items-center gap-2">
                            🛠️ {{ config('texts.product_install_time') }}
                        </span>
                        <a href="tel:0888368889"
                            class="px-4 py-2 rounded-full bg-yellow-50 text-yellow-700 font-semibold flex items-center gap-2 hover:bg-yellow-100 transition">
                            ☎ {{ config('texts.product_hotline') }} 0888 368 889
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div id="image-lightbox" class="image-lightbox hidden" role="dialog" aria-modal="true"
            aria-label="Xem hình sản phẩm phóng to">
            <div class="image-lightbox__content">
                <button type="button" id="lightbox-close" class="image-lightbox__close"
                    aria-label="{{ config('texts.product_lightbox_close') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="image-lightbox__main">
                    <button type="button" id="lightbox-prev"
                        class="image-lightbox__nav image-lightbox__nav--prev" aria-label="{{ config('texts.product_lightbox_prev') }}">&lsaquo;</button>
                    <img id="lightbox-main-image" src="" alt="Hình sản phẩm phóng to">
                    <button type="button" id="lightbox-next"
                        class="image-lightbox__nav image-lightbox__nav--next" aria-label="{{ config('texts.product_lightbox_next') }}">&rsaquo;</button>
                </div>
                <div id="lightbox-thumbnails" class="image-lightbox__thumbs"></div>
            </div>
        </div>

        <!-- Highlights -->
        <section class="highlights-grid grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-10">
            @foreach($detailHighlights as $item)
                <div class="highlight-card p-5 bg-white border border-gray-100 rounded-2xl shadow-sm">
                    @if(!empty($item['icon']))
                        <div class="text-2xl mb-2">{{ $item['icon'] }}</div>
                    @endif
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $item['title'] ?? '' }}</h3>
                    @if(!empty($item['description']))
                        <p class="text-sm text-gray-600">{{ $item['description'] }}</p>
                    @endif
                </div>
            @endforeach
        </section>

        

        <!-- Tabs -->
        <section class="product-tabs bg-white border border-gray-100 rounded-2xl shadow-sm mb-10 overflow-hidden">
            <div class="flex items-center gap-4 sm:gap-6 px-4 sm:px-6 border-b overflow-x-auto" style="-webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none;">
                <button class="tab-button active py-4 text-sm font-semibold text-gray-600 whitespace-nowrap"
                    data-tab-target="tab-description">{{ $content->name ?? config('texts.product_tab_description') }}</button>
                <button class="tab-button py-4 text-sm font-semibold text-gray-600 whitespace-nowrap"
                    data-tab-target="tab-specs">{{ $tech->name ?? config('texts.product_tab_specs') }}</button>
                <button class="tab-button py-4 text-sm font-semibold text-gray-600 whitespace-nowrap"
                    data-tab-target="tab-services">{{ $service->name ?? config('texts.product_tab_services') }}</button>
                <button class="tab-button py-4 text-sm font-semibold text-gray-600 whitespace-nowrap"
                    data-tab-target="tab-faq">{{ $tutorial->name ?? config('texts.product_tab_faq') }}</button>
                <button class="tab-button py-4 text-sm font-semibold text-gray-600 whitespace-nowrap"
                    data-tab-target="tab-address">{{ $address_sale->name ?? config('texts.product_tab_address') }}</button>
                <button class="tab-button py-4 text-sm font-semibold text-gray-600 whitespace-nowrap"
                    data-tab-target="tab-time">{{ $open_time->name ?? config('texts.product_tab_time') }}</button>
            </div>
            <div class="p-6 space-y-6">
                <div id="tab-description" class="tab-panel active space-y-4 text-sm leading-relaxed text-gray-700">
                    @if(isset($content) && isset($content->text) && $content->text)
                        {!! $decodeCkeditorEmbeds($content->text) !!}
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>{{ config('texts.product_no_description') }}</p>
                        </div>
                    @endif
                </div>

                <div id="tab-specs" class="tab-panel">
                    @if(isset($tech) && isset($tech->text) && $tech->text)
                        <div class="text-sm text-gray-700">
                            {!! $decodeCkeditorEmbeds($tech->text) !!}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>{{ config('texts.product_no_specs') }}</p>
                        </div>
                    @endif
                </div>

                <div id="tab-services" class="tab-panel">
                    @if(isset($service) && isset($service->text) && $service->text)
                        <div class="text-sm text-gray-700">
                            {!! $decodeCkeditorEmbeds($service->text) !!}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>{{ config('texts.product_no_services') }}</p>
                        </div>
                    @endif
                </div>

                <div id="tab-faq" class="tab-panel">
                    @if(isset($tutorial) && isset($tutorial->text) && $tutorial->text)
                        <div class="text-sm text-gray-700">
                            {!! $decodeCkeditorEmbeds($tutorial->text) !!}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>{{ config('texts.product_no_faq') }}</p>
                        </div>
                    @endif
                </div>

                <div id="tab-address" class="tab-panel">
                    @if(isset($address_sale) && isset($address_sale->text) && $address_sale->text)
                        <div class="text-sm text-gray-700">
                            {!! $decodeCkeditorEmbeds($address_sale->text) !!}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>{{ config('texts.product_no_address') }}</p>
                        </div>
                    @endif
                </div>

                <div id="tab-time" class="tab-panel">
                    @if(isset($open_time) && isset($open_time->text) && $open_time->text)
                        <div class="text-sm text-gray-700">
                            {!! $open_time->text !!}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>{{ config('texts.product_no_time') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Related products -->
        @if(isset($relatedProducts) && $relatedProducts && $relatedProducts->count() > 0)
        <section class="mb-12">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">{{ config('texts.product_related_title') }}</h2>
                <div class="h-1 bg-red-600 w-16 md:w-24 rounded-full"></div>
            </div>
            <div class="swiper-container related-swiper">
                <div class="swiper-wrapper">
                    @foreach($relatedProducts as $relatedProduct)
                        @php
                            $relatedImages = \App\Models\ProductImage::getTwoImageCategoryProduct($relatedProduct->id);
                            $mainImage = $relatedImages->first();
                            $hoverImage = $relatedImages->count() > 1 ? $relatedImages->get(1) : $mainImage;
                            $mainImageUrl = $mainImage ? asset('img/product/' . $mainImage->image) : asset('img/product/no-image.png');
                            $hoverImageUrl = $hoverImage ? asset('img/product/' . $hoverImage->image) : $mainImageUrl;
                            $relatedBrand = $relatedProduct->brand_id ? \App\Models\Brand::find($relatedProduct->brand_id) : null;
                            $relatedPrice = $relatedProduct->price_sale ?? $relatedProduct->price ?? 0;
                            $relatedOldPrice = $relatedProduct->price ?? 0;
                            $hasRelatedDiscount = $relatedProduct->price_sale && $relatedProduct->price && $relatedProduct->price > $relatedProduct->price_sale;
                        @endphp
                        <div class="swiper-slide group">
                            <div class="product-card bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                                <a href="{{ route('product.detail', ['categoryPath' => $relatedProduct->getCategoryPath(), 'productAlias' => $relatedProduct->alias]) }}" class="block relative overflow-hidden">
                                    <img src="{{ $mainImageUrl }}"
                                        alt="{{ $relatedProduct->name }}"
                                        class="product-img-main w-full h-48 object-contain transition-opacity duration-300">
                                    @if($hoverImage && $hoverImage->id !== $mainImage->id)
                                    <img src="{{ $hoverImageUrl }}"
                                        alt="{{ $relatedProduct->name }} - Hover"
                                        class="product-img-hover w-full h-48 object-contain transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                                    @endif
                                    @if($hasRelatedDiscount)
                                        @php
                                            $relatedDiscount = round((($relatedOldPrice - $relatedPrice) / $relatedOldPrice) * 100);
                                        @endphp
                                        <span class="discount-badge absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded-full">-{{ $relatedDiscount }}%</span>
                                    @endif
                                </a>
                                <div class="p-4 space-y-2">
                                    @if($relatedBrand)
                                    <p class="product-brand text-xs uppercase text-gray-500 font-semibold">{{ $relatedBrand->name }}</p>
                                    @endif
                                    <h3 class="text-sm font-medium text-gray-800 line-clamp-2 min-h-[2.5rem]">
                                        <a href="{{ route('product.detail', ['categoryPath' => $relatedProduct->getCategoryPath(), 'productAlias' => $relatedProduct->alias]) }}" class="hover:text-red-600">
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>
                                    <div class="text-right space-y-1">
                                        <span class="text-red-600 font-bold text-base">{{ number_format($relatedPrice, 0, ',', '.') }} {{ config('texts.currency') }}</span>
                                        @if($hasRelatedDiscount)
                                        <span class="text-xs text-gray-400 line-through block">{{ number_format($relatedOldPrice, 0, ',', '.') }} {{ config('texts.currency') }}</span>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            class="flex-1 bg-red-600 text-white py-2 rounded-lg text-xs font-semibold hover:bg-red-700 transition add-to-cart-btn"
                                            data-product-id="{{ $relatedProduct->id }}"
                                            data-product-name="{{ $relatedProduct->name }}"
                                            data-product-price="{{ $relatedPrice }}">{{ config('texts.product_related_add_to_cart') }}</button>
                                        <a href="{{ route('product.detail', ['categoryPath' => $relatedProduct->getCategoryPath(), 'productAlias' => $relatedProduct->alias]) }}"
                                            class="px-3 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

    </main>

@endsection
