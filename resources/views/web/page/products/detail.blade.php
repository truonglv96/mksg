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

        $seoDescription = $product->meta_description
            ?? $product->description
            ?? Str::limit(strip_tags($content->text ?? ''), 160)
            ?? Str::limit(strip_tags($tech->text ?? ''), 160);

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
        <meta name="description" content="{{ trim(strip_tags($seoDescription)) }}">
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
        <meta property="og:description" content="{{ trim(strip_tags($seoDescription)) }}">
    @endif
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="Mắt Kính Sài Gòn">
    <meta property="og:image" content="{{ $imageUrl }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    @if(!empty($seoDescription))
        <meta name="twitter:description" content="{{ trim(strip_tags($seoDescription)) }}">
    @endif
    <meta name="twitter:image" content="{{ $imageUrl }}">

    {{-- Product structured data --}}
    <script type="application/ld+json">
        {!! json_encode($schemaProduct, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection

@section('content')
    <main class="container mx-auto px-4 py-8">

        {{-- Breadcrumb Component --}}
        @include('web.partials.breadcrumb')

        <!-- Product Summary -->
        <section id="product-summary" class="product-summary bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8 mb-10" data-product-id="{{ $product->id }}">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 xl:gap-10">
                <!-- Gallery -->
                <div>
                    @php
                        $mainImage = isset($productImages) && $productImages ? $productImages->first() : null;
                        $mainImageUrl = $mainImage ? asset('img/product/' . $mainImage->image) : asset('img/product/no-image.png');
                    @endphp
                    <button type="button" id="lightbox-trigger"
                        class="relative w-full h-[420px] lg:h-[520px] flex items-center justify-center bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden group cursor-zoom-in focus:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-2"
                        aria-label="{{ config('texts.product_lightbox_zoom') }}">
                        <img id="main-product-image"
                            src="{{ $mainImageUrl }}"
                            alt="{{ $product->name }}"
                            class="max-h-full w-full object-contain transition-transform duration-500 ease-out group-hover:scale-110">
                        @if(isset($product) && $product->price_sale && $product->price && $product->price > $product->price_sale)
                            @php
                                $discount = round((($product->price - $product->price_sale) / $product->price) * 100);
                            @endphp
                            <span
                                class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1 text-xs font-semibold rounded-full shadow">
                                -{{ $discount }}%
                            </span>
                        @endif
                    </button>
                    @if(isset($productImages) && $productImages && $productImages->count() > 0)
                    <div class="flex gap-3 mt-4 overflow-x-auto pb-1">
                        @foreach($productImages as $image)
                        <button type="button" data-image-src="{{ asset('img/product/' . $image->image) }}"
                            class="thumbnail-button flex-shrink-0 border-2 border-transparent rounded-xl overflow-hidden w-24 h-24">
                            <img src="{{ asset('img/product/' . $image->image) }}"
                                alt="{{ $product->name }} - Hình {{ $loop->iteration }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        <div
                            class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 shadow-sm">
                            <span class="flex items-center justify-center w-10 h-10 rounded-full bg-red-50 text-red-600">
                                🚚
                            </span>
                            <div>
                                <p class="font-semibold text-gray-800">{{ config('texts.product_free_shipping') }}</p>
                                <p class="text-xs text-gray-500">{{ config('texts.product_shipping_time') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 shadow-sm">
                            <span class="flex items-center justify-center w-10 h-10 rounded-full bg-red-50 text-red-600">
                                🔁
                            </span>
                            <div>
                                <p class="font-semibold text-gray-800">{{ config('texts.product_return_policy') }}</p>
                                <p class="text-xs text-gray-500">{{ config('texts.product_return_free') }}</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100 shadow-sm">
                            <span class="flex items-center justify-center w-10 h-10 rounded-full bg-red-50 text-red-600">
                                🛡️
                            </span>
                            <div>
                                <p class="font-semibold text-gray-800">{{ config('texts.product_warranty') }}</p>
                                <p class="text-xs text-gray-500">{{ config('texts.product_warranty_info') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product content -->
                <div class="space-y-5">
                    <div class="space-y-2">
                        <!-- @if($brand)
                        <p id="product-brand" class="uppercase tracking-wide text-xs font-semibold text-red-600">{{ $brand->name }}</p>
                        @endif -->
                        <h1 id="product-name" class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight">
                            {{ $product->name }}
                        </h1>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                            <!-- <div class="flex items-center gap-1 text-yellow-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.802-2.034a1 1 0 00-1.175 0l-2.802 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.463a1 1 0 00.95-.69l1.068-3.292z" />
                                </svg>
                                4.8 (128 đánh giá)
                            </div> -->
                            @if($product->code)
                            <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-gray-300"></span>
                            <p>{{ config('texts.product_code') }} <span class="font-medium text-gray-700">{{ $product->code }}</span></p>
                            @endif
                            <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-gray-300"></span>
                            <p>{{ config('texts.product_brand') }} <span class="font-medium text-green-600">
                                @if($brand && $brand->name)
                                    {{ $brand->name }}
                                @elseif($product->brand && $product->brand->name)
                                    {{ $product->brand->name }}
                                @endif
                            </span></p>
                            <span class="hidden sm:inline-block w-1 h-1 rounded-full bg-gray-300"></span>
                            <div class="social-sharing flex items-center">
                                <script async defer crossorigin="anonymous"
                                    src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v15.0"
                                    nonce="IrDKTUDJ"></script>
                                <div class="fb-share-button"
                                    data-href="{{ route('product.detail', ['categoryPath' => $mainCategory ? $product->getCategoryPath() : '', 'productAlias' => $product->alias]) }}"
                                    data-layout="button_count" data-size="large">
                                    <a target="_blank"
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('product.detail', ['categoryPath' => $mainCategory ? $product->getCategoryPath() : '', 'productAlias' => $product->alias])) }}"
                                        class="fb-xfbml-parse-ignore">{{ config('texts.product_share') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="price-card bg-red-50 border border-red-100 rounded-2xl p-4 sm:p-5 space-y-2">
                        <div class="flex items-center gap-2">
                            @php
                                $currentPrice = $product->price_sale ?? $product->price ?? 0;
                                $oldPrice = $product->price ?? 0;
                                $hasDiscount = $product->price_sale && $product->price && $product->price > $product->price_sale;
                            @endphp
                            <span class="price-current text-3xl font-bold text-red-600" data-product-price data-base-price="{{ $currentPrice }}">{{ number_format($currentPrice, 0, ',', '.') }} VNĐ</span>
                            @if($hasDiscount)
                            <span class="price-old text-sm text-gray-500 line-through">{{ number_format($oldPrice, 0, ',', '.') }} VNĐ</span>
                            @php
                                $savingPercent = round((($oldPrice - $currentPrice) / $oldPrice) * 100);
                            @endphp
                            <span class="price-saving text-xs font-semibold text-white bg-red-500 px-2 py-1 rounded-full">{{ config('texts.product_save') }} {{ $savingPercent }}%</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4">
                        @if(isset($productColors) && $productColors && $productColors->count() > 0)
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-2 uppercase">{{ config('texts.product_frame_color') }}</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach($productColors as $index => $color)
                                <button type="button" class="color-chip border border-gray-200 shadow-sm bg-cover bg-center transition hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 {{ $index === 0 ? 'active' : '' }}"
                                    data-color="{{ $color->name }}" 
                                    data-color-id="{{ $color->id }}"
                                    @if($color->url_img)
                                    style="background-image: url('{{ asset('img/color/' . $color->url_img) }}');"
                                    @endif
                                    aria-label="{{ $color->name }}" 
                                    aria-pressed="{{ $index === 0 ? 'true' : 'false' }}">
                                </button>
                                @endforeach
                            </div>
                            <p id="selected-color" class="mt-2 text-xs text-gray-500">{{ config('texts.product_selected') }} <span class="font-medium text-gray-700">{{ $productColors->first()->name ?? '' }}</span></p>
                        </div>
                        @endif

                        @if(isset($discountedCombos) && $discountedCombos && $discountedCombos->count() > 0)
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-2 uppercase">{{ config('texts.product_lens_package') }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($discountedCombos as $index => $combo)
                                <button type="button" 
                                    class="option-pill rounded-xl px-4 py-3 text-left bg-gray-50 hover:border-red-400 hover:bg-red-50 transition {{ $index === 0 ? 'active border-red-400 bg-red-50' : '' }}"
                                    data-option="{{ $combo->name }}" 
                                    data-combo-id="{{ $combo->id }}"
                                    data-option-price="{{ $combo->price ?? 0 }}"
                                    aria-pressed="{{ $index === 0 ? 'true' : 'false' }}">
                                    <p class="font-semibold text-gray-800">{{ $combo->name }}</p>
                                    @if($combo->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ $combo->description }}</p>
                                    @endif
                                    @if($combo->price && $combo->price > 0)
                                    <p class="text-xs font-semibold text-red-600 mt-1">+{{ number_format($combo->price, 0, ',', '.') }} VNĐ</p>
                                    @endif
                                </button>
                                @endforeach
                            </div>
                            <p id="selected-option" class="mt-2 text-xs text-gray-500">
                                {{ config('texts.product_selected') }} <span class="font-medium text-gray-700" id="selected-options-list">{{ $discountedCombos->first()->name ?? '' }}</span>
                            </p>
                        </div>
                        @endif
                    </div>

                    <div class="cta-buttons flex flex-col sm:flex-row gap-3">
                        <button
                            class="flex-1 bg-red-600 text-white py-3 sm:py-4 rounded-xl font-semibold hover:bg-red-700 transition shadow-lg shadow-red-200 add-to-cart-btn">
                            {{ config('texts.product_add_to_cart') }}
                        </button>
                        <button
                            class="flex-1 border border-red-600 text-red-600 py-3 sm:py-4 rounded-xl font-semibold hover:bg-red-50 transition buy-now-btn">
                            {{ config('texts.product_buy_now') }}
                        </button>
                    </div>

                    <div class="support-chips flex flex-wrap items-center gap-3 text-sm">
                        <span class="px-3 py-2 rounded-full bg-green-50 text-green-600 font-medium flex items-center gap-2">
                            🛠️ {{ config('texts.product_install_time') }}
                        </span>
                        <a href="tel:0888368889"
                            class="px-4 py-2 rounded-full bg-yellow-50 text-yellow-700 font-semibold flex items-center gap-2 hover:bg-yellow-100 transition">
                            ☎ {{ config('texts.product_hotline') }} 0888 368 889
                        </a>
                    </div>

                    <div class="info-badges bg-gray-50 border border-gray-100 rounded-2xl p-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs sm:text-sm text-gray-600">
                        <div class="flex items-start gap-2">
                            <span class="text-red-500 mt-0.5">🏬</span>
                            <p><strong>{{ config('texts.product_pickup_showroom') }}</strong><br>{{ config('texts.product_pickup_address') }}</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="text-red-500 mt-0.5">🎁</span>
                            <p><strong>{{ config('texts.product_gift') }}</strong><br>{{ config('texts.product_gift_info') }}</p>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="text-red-500 mt-0.5">💳</span>
                            <p><strong>{{ config('texts.product_payment') }}</strong><br>{{ config('texts.product_payment_info') }}</p>
                        </div>
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
            <div class="highlight-card p-5 bg-white border border-gray-100 rounded-2xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ config('texts.product_guarantee_title') }}</h3>
                <p class="text-sm text-gray-600">{{ config('texts.product_guarantee_desc') }}</p>
            </div>
            <div class="highlight-card p-5 bg-white border border-gray-100 rounded-2xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ config('texts.product_eye_test_title') }}</h3>
                <p class="text-sm text-gray-600">{{ config('texts.product_eye_test_desc') }}</p>
            </div>
            <div class="highlight-card p-5 bg-white border border-gray-100 rounded-2xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ config('texts.product_after_sale_title') }}</h3>
                <p class="text-sm text-gray-600">{{ config('texts.product_after_sale_desc') }}</p>
            </div>
        </section>

        <!-- Tabs -->
        <section class="product-tabs bg-white border border-gray-100 rounded-2xl shadow-sm mb-10">
            <div class="flex items-center gap-6 px-4 sm:px-6 border-b overflow-x-auto">
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
                        {!! $content->text !!}
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>{{ config('texts.product_no_description') }}</p>
                        </div>
                    @endif
                </div>

                <div id="tab-specs" class="tab-panel">
                    @if(isset($tech) && isset($tech->text) && $tech->text)
                        <div class="text-sm text-gray-700">
                            {!! $tech->text !!}
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
                            {!! $service->text !!}
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
                            {!! $tutorial->text !!}
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
                            {!! $address_sale->text !!}
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

        <!-- Showrooms -->
        <section class="showroom-section bg-gradient-to-br from-red-50 via-white to-white border border-red-100 rounded-2xl p-6 md:p-8 mb-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="max-w-xl">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-4">{{ config('texts.product_showroom_title') }}</h2>
                    <p class="text-sm text-gray-600 mb-4">{{ config('texts.product_showroom_desc') }}</p>
                    @if(isset($address_sale) && isset($address_sale->text) && $address_sale->text)
                        <div class="text-sm text-gray-700 mb-4">
                            {!! $address_sale->text !!}
                        </div>
                    @else
                    <ul class="space-y-3 text-sm text-gray-700 font-sans">
                        <li><strong>{{ config('texts.product_showroom_1') }}</strong> {{ config('texts.product_showroom_1_address') }}</li>
                        <li><strong>{{ config('texts.product_showroom_2') }}</strong> {{ config('texts.product_showroom_2_address') }}</li>
                        <li><strong>{{ config('texts.product_showroom_3') }}</strong> {{ config('texts.product_showroom_3_address') }}</li>
                    </ul>
                    @endif
                    @if(isset($open_time) && isset($open_time->text) && $open_time->text)
                        <div class="text-sm text-gray-700 mt-4">
                            {!! $open_time->text !!}
                        </div>
                    @else
                        <p class="text-sm text-gray-700 mt-4"><strong>{{ config('texts.product_work_time') }}</strong> {{ config('texts.product_work_time_value') }}</p>
                    @endif
                </div>
                <div class="flex-1 w-full">
                    <div class="consult-form-card h-full">
                        <div class="consult-form-header">
                            <span>📅</span>
                            <div>
                                <h3 class="consult-form-title">{{ config('texts.product_book_appointment_title') }}</h3>
                                <p class="consult-form-subtitle">{{ config('texts.product_book_appointment_desc') }}</p>
                            </div>
                        </div>
                        <form class="consult-form" autocomplete="on">
                            <div class="consult-form-grid">
                                <div class="form-field">
                                    <label for="consult-name">{{ config('texts.product_form_name') }}</label>
                                    <input id="consult-name" type="text" name="name" placeholder="{{ config('texts.product_form_name_placeholder') }}" autocomplete="name"
                                        required>
                                </div>
                                <div class="form-field">
                                    <label for="consult-phone">{{ config('texts.product_form_phone') }}</label>
                                    <input id="consult-phone" type="tel" name="phone" placeholder="{{ config('texts.product_form_phone_placeholder') }}" autocomplete="tel"
                                        required>
                                </div>
                            </div>
                            <div class="consult-form-grid">
                                <div class="form-field form-field--select">
                                    <label for="consult-showroom">{{ config('texts.product_form_showroom') }}</label>
                                    <select id="consult-showroom" name="showroom" required>
                                        <option value="" disabled selected>{{ config('texts.product_form_showroom_placeholder') }}</option>
                                        <option>{{ config('texts.product_form_showroom_1') }}</option>
                                        <option>{{ config('texts.product_form_showroom_2') }}</option>
                                        <option>{{ config('texts.product_form_showroom_3') }}</option>
                                    </select>
                                </div>
                                <div class="form-field form-field--datetime">
                                    <label for="consult-datetime">{{ config('texts.product_form_datetime') }}</label>
                                    <input id="consult-datetime" type="datetime-local" name="datetime" required>
                                </div>
                            </div>
                            <div class="form-field">
                                <label for="consult-message">{{ config('texts.product_form_message') }}</label>
                                <textarea id="consult-message" name="message" rows="3"
                                    placeholder="{{ config('texts.product_form_message_placeholder') }}"></textarea>
                            </div>
                            <button type="submit">
                                <span>📨</span> {{ config('texts.product_form_submit') }}
                            </button>
                        </form>
                        <p class="consult-form-help">{{ config('texts.product_form_help') }} <a href="tel:0966666301">0966 666 301</a> • <a
                                href="mailto:mksgvn@gmail.com">mksgvn@gmail.com</a></p>
                    </div>
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
                                <a href="{{ route('product.detail', ['categoryPath' => $relatedProduct->getCategoryPath(), 'productAlias' => $relatedProduct->alias]) }}">
                                    <div class="relative overflow-hidden">
                                        <img src="{{ $mainImageUrl }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                                        @if($hoverImage && $hoverImage->id !== $mainImage->id)
                                        <img src="{{ $hoverImageUrl }}"
                                            alt="{{ $relatedProduct->name }} - Hover"
                                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                                        @endif
                                        @if($hasRelatedDiscount)
                                            @php
                                                $relatedDiscount = round((($relatedOldPrice - $relatedPrice) / $relatedOldPrice) * 100);
                                            @endphp
                                            <span class="discount-badge absolute top-3 left-3 bg-red-600 text-white text-xs font-semibold px-2 py-1 rounded-full">-{{ $relatedDiscount }}%</span>
                                        @endif
                                    </div>
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
                                        <span class="text-red-600 font-bold text-base">{{ number_format($relatedPrice, 0, ',', '.') }} VNĐ</span>
                                        @if($hasRelatedDiscount)
                                        <span class="text-xs text-gray-400 line-through block">{{ number_format($relatedOldPrice, 0, ',', '.') }} VNĐ</span>
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
