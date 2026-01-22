@extends('web.master')

@section('title', $settings->meta_title ?? config('texts.page_title'))

@section('meta')
    @php
        $homeTitle = $settings->meta_title ?? config('texts.page_title');
        $homeDescription = $settings->meta_description ?? null;
        $homeKeywords = $settings->meta_keyword ?? null;
        $homeCanonical = request()->url();
        $homeImage = ($settings && $settings->logo)
            ? $settings->getLogo()
            : asset('img/logo.png');
        $homeImageSecure = preg_replace('/^http:/i', 'https:', $homeImage);
    @endphp
    @if(!empty($homeDescription))
        <meta name="description" content="{{ trim(strip_tags($homeDescription)) }}">
    @endif
    @if(!empty($homeKeywords))
        <meta name="keywords" content="{{ $homeKeywords }}">
    @endif
    <link rel="canonical" href="{{ $homeCanonical }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $homeTitle }}">
    @if(!empty($homeDescription))
        <meta property="og:description" content="{{ trim(strip_tags($homeDescription)) }}">
    @endif
    <meta property="og:url" content="{{ $homeCanonical }}">
    <meta property="og:site_name" content="{{ $homeTitle }}">
    <meta property="og:image" content="{{ $homeImage }}">
    <meta property="og:image:secure_url" content="{{ $homeImageSecure }}">
    <meta property="og:image:alt" content="{{ $homeTitle }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $homeTitle }}">
    @if(!empty($homeDescription))
        <meta name="twitter:description" content="{{ trim(strip_tags($homeDescription)) }}">
    @endif
    <meta name="twitter:image" content="{{ $homeImage }}">
@endsection

@section('content')
<!-- Banner Slider Full Màn Hình -->
<div class="swiper-container banner-slider">
    <div class="swiper-wrapper">
        <!-- Slide 1 -->
         @if(isset($banners) && $banners->count() > 0)
         @foreach($banners as $banner)
        <div class="swiper-slide">
            <img src="{{ $banner->getImage() }}"
                alt="{{ $banner->title }}"
                loading="eager"
                width="1920"
                height="600"
                class="w-full h-auto">
        </div>
        @endforeach
        @endif
    </div>
</div>

<main class="container mx-auto px-4 py-8">

    <section class="mb-12">
        <div class="mb-6">
            <h1 class="section-title text-gray-800 mb-2">{{ config('texts.featured_categories') }}</h1>
            <div class="h-1 w-20" style="background-color: #ed1c24;"></div>
        </div>

        <!-- Desktop: Grid tĩnh 4 cột -->
        <div class="hidden md:grid md:grid-cols-4 gap-6">
            @if(isset($featuredCategories) && $featuredCategories->count() > 0)
                @foreach($featuredCategories as $item)
                <a href="{{ $item->link ?? '#' }}" 
                   class="p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300 cursor-pointer block"
                   style="background-color: {{ $item->getBackgroundColor() }};">
                    @if($item->image)
                        <div class="mb-3 flex justify-center">
                            <img src="{{ $item->getImage() }}" alt="{{ $item->name }}" class="w-16 h-16 object-contain">
                        </div>
                    @else
                        <div class="text-5xl mb-3">👓</div>
                    @endif
                    <h3 class="font-bold text-lg mb-2">{{ $item->name }}</h3>
                    <span class="text-sm text-gray-600 hover:text-[#ed1c24] transition-colors">{{ config('texts.view_now') }}</span>
                </a>
                @endforeach
            @else
                <!-- Fallback nếu không có dữ liệu -->
                <div class="bg-purple-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300 cursor-pointer">
                    <div class="text-5xl mb-3">👓</div>
                    <h3 class="font-bold text-lg mb-2">{{ config('texts.category_glasses') }}</h3>
                    <a href="#" class="text-sm text-gray-600 hover:text-[#ed1c24] transition-colors">{{ config('texts.view_now') }}</a>
                </div>
            @endif
        </div>

        <!-- Mobile: Swiper slider 2 cột -->
        <div class="md:hidden swiper-container categories-slider">
            <div class="swiper-wrapper">
                @if(isset($featuredCategories) && $featuredCategories->count() > 0)
                    @foreach($featuredCategories as $item)
                    <div class="swiper-slide">
                        <a href="{{ $item->link ?? '#' }}" 
                           class="p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300 block"
                           style="background-color: {{ $item->getBackgroundColor() }};">
                            @if($item->image)
                                <div class="mb-3 flex justify-center">
                                    <img src="{{ $item->getImage() }}" alt="{{ $item->name }}" class="w-16 h-16 object-contain">
                                </div>
                            @else
                                <div class="text-5xl mb-3">👓</div>
                            @endif
                            <h3 class="font-bold text-lg mb-2">{{ $item->name }}</h3>
                            <span class="text-sm text-gray-600 hover:text-red-600 transition-colors">{{ config('texts.view_now') }}</span>
                        </a>
                    </div>
                    @endforeach
                @else
                    <!-- Fallback nếu không có dữ liệu -->
                    <div class="swiper-slide">
                        <div class="bg-purple-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300">
                            <div class="text-5xl mb-3">👓</div>
                            <h3 class="font-bold text-lg mb-2">{{ config('texts.category_glasses') }}</h3>
                            <a href="#" class="text-sm text-gray-600 hover:text-red-600 transition-colors">{{ config('texts.view_now') }}</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="section-title text-gray-800 mb-2">{{ config('texts.newest_products') }}</h1>
                <div class="h-1 w-20" style="background-color: #ed1c24;"></div>
            </div>
            <div class="flex items-center space-x-6" id="product-tabs">
                @if(isset($categoriesProduct) && $categoriesProduct->count() > 0)
                @foreach($categoriesProduct as $index => $category)
                <a data-category="{{ $category->alias }}"
                    class="product-tab text-base font-medium transition-colors relative pb-1 border-b-2 hover:text-red-600 {{ $index === 0 ? 'active border-red-600 text-red-600' : 'border-transparent text-gray-800' }}">{{ $category->name }}</a>
                @endforeach
                @endif
            </div>
        </div>

        <!-- Container để chứa các nhóm sản phẩm -->
        <div class="product-container">
            @if(isset($categoriesProduct) && $categoriesProduct->count() > 0)
            @foreach($categoriesProduct as $index => $category)
            @php
                $processedProducts = isset($processedProductsByCategory[$category->alias]) 
                    ? $processedProductsByCategory[$category->alias] 
                    : collect([]);
            @endphp
            <div class="product-group {{ $index === 0 ? '' : 'hidden' }}" data-category="{{ $category->alias }}">
                <div class="swiper-container category-swiper">
                    <div class="swiper-wrapper">
                        @if($processedProducts->count() > 0)
                        @foreach($processedProducts as $item)
                        @php
                            $product = $item['product'];
                            $mainImage = $item['mainImage'];
                            $hoverImage = $item['hoverImage'];
                            $priceSale = $item['priceSale'];
                            $price = $item['price'];
                            $discount = $item['discount'];
                        @endphp
                        <div class="swiper-slide group">
                            <div
                                class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                                <a href="{{ $product->alias ? route('product.detail', ['categoryPath' => $product->getCategoryPath(), 'productAlias' => $product->alias]) : '#' }}" class="relative overflow-hidden block">
                                    <img src="{{ $mainImage }}"
                                        alt="{{ $product->name }}"
                                        loading="lazy"
                                        width="400"
                                        height="192"
                                        class="product-img-main w-full h-48 object-contain transition-opacity duration-300">
                                    <img src="{{ $hoverImage }}"
                                        alt="{{ $product->name }} - Hình 2"
                                        loading="lazy"
                                        width="400"
                                        height="192"
                                        class="product-img-hover w-full h-48 object-contain transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                                    @if($discount > 0)
                                    <!-- Badge giảm giá góc phải trên -->
                                    <span
                                        class="discount-badge absolute top-2 right-2 text-white text-xs font-bold px-2 py-1 rounded shadow-lg" style="background-color: #ed1c24;">-{{ $discount }}%</span>
                                    @endif
                                </a>
                                <div class="p-4">
                                    <a href="{{ $product->alias ? route('product.detail', ['categoryPath' => $product->getCategoryPath(), 'productAlias' => $product->alias]) : '#' }}">
                                        <h3 class="font-semibold text-sm mb-1 line-clamp-2 min-h-[2.5rem] product-title hover:text-red-600 transition-colors">{{ $product->name }}</h3>
                                    </a>
                                    <div class="mb-3 text-right">
                                        <p class="font-bold text-lg leading-tight" style="color: #ed1c24;">{{ number_format($priceSale, 0, ',', '.') }} {{ config('texts.currency') }}</p>
                                        @if($price > $priceSale)
                                        <p class="text-xs text-gray-400 line-through mt-0.5">{{ number_format($price, 0, ',', '.') }} {{ config('texts.currency') }}</p>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            class="flex-1 text-white py-1.5 px-3 rounded text-xs font-medium transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn hover:bg-red-700" 
                                            style="background-color: #ed1c24;" 
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ $priceSale }}"
                                            data-product-image="{{ $mainImage }}"
                                            data-product-brand="{{ $product->brand ?? '' }}"
                                            data-product-id="{{ $product->id }}">
                                            {{ config('texts.add_to_cart') }}
                                        </button>
                                        <a href="{{ $product->alias ? url('/san-pham/' . $product->alias) : '#' }}"
                                            class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="swiper-slide">
                            <div class="text-center py-8 text-gray-500">
                                <p>{{ config('texts.no_products_in_category') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </section>

    <section class="mb-12">
        <div class="mb-6">
            <div>
                <h1 class="section-title text-gray-800 mb-2">{{ config('texts.newest_news') }}</h1>
                <div class="h-1 w-20" style="background-color: #ed1c24;"></div>
            </div>
        </div>

        <div class="swiper-container news-slider">
            <div class="swiper-wrapper">
                @if(isset($processedNews) && $processedNews->count() > 0)
                @foreach($processedNews as $item)
                @php
                    $newsItem = $item['news'];
                    $imageUrl = $item['imageUrl'];
                    $date = $item['date'];
                    $detailUrl = $item['detailUrl'];
                @endphp
                <div class="swiper-slide">
                    <div
                        class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                        <a href="{{ $detailUrl }}" class="relative block">
                            @if(!empty($imageUrl))
                            <img src="{{ $imageUrl }}"
                                alt="{{ $newsItem->title ?? $newsItem->name }}" 
                                loading="lazy"
                                width="400"
                                height="192"
                                class="w-full h-48 object-cover">
                            @endif
                        </a>
                        <div class="p-4">
                            @if(!empty($date))
                            <div
                                class="inline-block text-xs font-medium px-3 py-1 rounded mb-2" style="background-color: #fee2e2; color: #ed1c24;">
                                {{ $date }}
                            </div>
                            @endif
                            <a href="{{ $detailUrl }}">
                                <h3 class="font-bold text-base mb-2 line-clamp-2 min-h-[3rem] news-title hover:text-red-600 transition-colors">{{ $newsItem->title ?? $newsItem->name }}</h3>
                            </a>
                            @if(!empty($newsItem->description))
                            <p class="text-sm text-gray-600 line-clamp-2">{!! strip_tags($newsItem->description) !!}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <!-- <div class="swiper-button-next hidden lg:block"></div>
            <div class="swiper-button-prev hidden lg:block"></div> -->
        </div>
    </section>

    <section class="my-4">
        <div class="swiper-container brands-slider">
            <div class="swiper-wrapper items-center">
                @if(isset($brands) && $brands->count() > 0)
                @foreach($brands as $brand)
                <div class="swiper-slide flex justify-center">
                    <img src="{{ $brand->getLogoBrand() }}" 
                         alt="{{ $brand->name }}" 
                         loading="lazy"
                         width="120"
                         height="48"
                         class="h-10 md:h-12 hover:opacity-100 transition">
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </section>

</main>
@endsection
