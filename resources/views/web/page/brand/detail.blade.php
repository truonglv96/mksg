@extends('web.master')

@section('title', $title ?? config('texts.brand_detail_title'))

@section('content')
<main class="container mx-auto px-4 py-8">
    {{-- Breadcrumb Component --}}
    @include('web.partials.breadcrumb')

    {{-- Brand Header Section --}}
    <section class="mb-8">
        <div class="bg-gradient-to-r from-red-50 to-white rounded-2xl p-6 md:p-8 border border-red-100">
            @php
                $hasImages = isset($brandImages) && $brandImages && $brandImages->count() > 0;
            @endphp
            
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                {{-- Image Slider Section (Left on desktop, top on mobile) --}}
                @if($hasImages)
                <div class="w-full lg:w-1/2 flex-shrink-0 -mx-6 md:-mx-8 lg:mx-0">
                    <div class="brand-image-slider relative">
                        <div class="swiper-container brand-slider-main lg:rounded-xl overflow-hidden bg-white shadow-md">
                            <div class="swiper-wrapper">
                                {{-- Image slides --}}
                                @foreach($brandImages as $brandImage)
                                <div class="swiper-slide">
                                    <div class="w-full flex items-center justify-center bg-white p-0 lg:p-4">
                                        <img src="{{ $brandImage->getImage() }}" 
                                             alt="{{ $brand->name }} - Hình {{ $loop->iteration }}" 
                                             class="w-full h-auto max-w-full object-contain">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if($brandImages->count() > 1)
                            <!-- Navigation buttons -->
                            <!-- <div class="swiper-button-next brand-slider-next"></div>
                            <div class="swiper-button-prev brand-slider-prev"></div> -->
                            <!-- Pagination dots -->
                            <!-- <div class="swiper-pagination brand-slider-pagination"></div> -->
                            @endif
                        </div>
                        
                        @if($brandImages->count() > 1)
                        <!-- Thumbnail slider -->
                        <div class="swiper-container brand-slider-thumbs mt-4" style="height: 80px;">
                            <div class="swiper-wrapper">
                                @foreach($brandImages as $brandImage)
                                <div class="swiper-slide cursor-pointer">
                                    <div class="w-full h-full flex items-center justify-center bg-white border-2 border-transparent hover:border-red-600 rounded-lg overflow-hidden p-2 transition-all">
                                        <img src="{{ $brandImage->getImage() }}" 
                                             alt="{{ $brand->name }} - Thumb {{ $loop->iteration }}" 
                                             class="max-w-full max-h-full object-contain">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @elseif($brand->logo)
                <div class="w-full lg:w-1/2 flex-shrink-0 flex items-center justify-center">
                    <div class="bg-white rounded-xl p-8 shadow-md">
                        <img src="{{ $brand->getLogoBrand() }}" 
                             alt="{{ $brand->name }}" 
                             class="w-full max-w-xs object-contain">
                    </div>
                </div>
                @endif
                
                {{-- Content Section (Right on desktop, bottom on mobile) --}}
                <div class="flex-1 flex flex-col justify-center">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ $brand->name }}</h1>
                    @if($brand->content)
                    <div class="text-gray-600 text-base leading-relaxed prose prose-sm max-w-none">
                        {!! $brand->content !!}
                    </div>
                    @elseif($brand->description)
                    <div class="text-gray-600 text-lg leading-relaxed">
                        {!! nl2br(e($brand->description)) !!}
                    </div>
                    @else
                    <p class="text-gray-600 text-lg leading-relaxed">
                        {{ config('texts.brand_detail_default_desc') }} {{ $brand->name }} - {{ config('texts.brand_detail_default_desc_2') }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Filters and Sort Section --}}
    <section class="mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
            <div class="text-sm text-gray-600">
                @if(isset($products) && $products->count() > 0)
                    {{ config('texts.brand_detail_showing') }} <span class="font-semibold">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span> {{ config('texts.brand_detail_in') }} <span class="font-semibold">{{ $products->total() }}</span> {{ config('texts.brand_detail_products') }}
                @else
                    {{ config('texts.brand_detail_no_products') }}
                @endif
            </div>
            <select
                id="sort-select"
                name="sort"
                class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium text-gray-700 border-none focus:ring-2 focus:ring-red-600"
                onchange="handleSortChange(this.value)">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ config('texts.brand_detail_sort_newest') }}</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ config('texts.brand_detail_sort_price_asc') }}</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ config('texts.brand_detail_sort_price_desc') }}</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ config('texts.brand_detail_sort_name_asc') }}</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ config('texts.brand_detail_sort_name_desc') }}</option>
            </select>
        </div>

        {{-- Category Filter (if available) --}}
        @if(isset($categories) && $categories->count() > 0)
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">{{ config('texts.brand_detail_category') }}</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('brand.detail', ['alias' => $brand->alias]) }}"
                   class="px-3 py-1.5 text-sm rounded-full {{ !request('category') ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    {{ config('texts.brand_detail_all') }}
                </a>
                @foreach($categories as $category)
                    @if(($category->type ?? 'product') !== 'new')
                    <a href="{{ route('brand.detail', ['alias' => $brand->alias, 'category' => $category->id]) }}#products-section"
                       class="px-3 py-1.5 text-sm rounded-full {{ request('category') == $category->id ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                        {{ $category->name ?? $category->title ?? config('texts.brand_detail_category_fallback', 'Danh mục') }}
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
    </section>

    {{-- Products Grid --}}
    <section id="products-section">
        @if(isset($products) && $products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-4 md:gap-6">
            @foreach($products as $product)
            @php
                $productImages = \App\Models\ProductImage::getTwoImageCategoryProduct($product->id);
                $mainImage = $productImages->count() > 0 ? asset('img/product/' . $productImages->first()->image) : asset('img/product/no-image.jpg');
                $hoverImage = $productImages->count() > 1 ? asset('img/product/' . $productImages->get(1)->image) : $mainImage;
                $priceSale = $product->price_sale ?? $product->price ?? 0;
                $price = $product->price ?? 0;
                $discount = $price > 0 && $priceSale < $price ? round((($price - $priceSale) / $price) * 100) : 0;
            @endphp
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <a href="{{ $product->alias ? route('product.detail', ['categoryPath' => $product->getCategoryPath(), 'productAlias' => $product->alias]) : '#' }}" class="relative overflow-hidden block">
                    @if($discount > 0)
                    <span class="discount-badge absolute top-2 right-2 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-10" style="background-color: #ed1c24;">-{{ $discount }}%</span>
                    @endif
                    <img src="{{ $mainImage }}"
                        alt="{{ $product->name }}"
                        class="product-img-main w-full h-48 object-contain transition-opacity duration-300">
                    <img src="{{ $hoverImage }}"
                        alt="{{ $product->name }} - Hover"
                        class="product-img-hover w-full h-48 object-contain transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                </a>
                <div class="p-3">
                    <a href="{{ $product->alias ? route('product.detail', ['categoryPath' => $product->getCategoryPath(), 'productAlias' => $product->alias]) : '#' }}">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem] product-title" style="color: #000; transition: color 0.3s;" onmouseover="this.style.color='#ed1c24'" onmouseout="this.style.color='#000'">{{ $product->name }}</h3>
                    </a>
                    <div class="mb-3 text-right">
                        <p class="font-bold text-lg leading-tight" style="color: #ed1c24;">{{ number_format($priceSale, 0, ',', '.') }} {{ config('texts.currency') }}</p>
                        @if($price > $priceSale)
                        <p class="text-xs text-gray-400 line-through mt-0.5">{{ number_format($price, 0, ',', '.') }} {{ config('texts.currency') }}</p>
                        @else
                        <p class="text-xs text-gray-400 invisible mt-0.5">&#8203;</p>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="flex-1 text-white py-1.5 px-3 rounded text-xs font-medium transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn"
                            style="background-color: #ed1c24;"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-price="{{ $priceSale }}"
                            onmouseover="this.style.backgroundColor='#d0171f'"
                            onmouseout="this.style.backgroundColor='#ed1c24'">
                            {{ config('texts.add_to_cart') }}
                        </button>
                        <a href="{{ $product->alias ? route('product.detail', ['categoryPath' => $product->getCategoryPath(), 'productAlias' => $product->alias]) : '#' }}"
                            class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if(method_exists($products, 'hasPages') && $products->hasPages())
        <div class="mt-8 flex justify-center">
            <nav class="flex gap-2 flex-wrap justify-center">
                @php
                    $products->appends(request()->query());
                @endphp
                @if($products->onFirstPage())
                <span class="px-4 py-2 bg-gray-100 rounded text-gray-400 cursor-not-allowed">«</span>
                @else
                <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">«</a>
                @endif

                @php
                    $currentPage = $products->currentPage();
                    $lastPage = $products->lastPage();
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($lastPage, $currentPage + 2);
                @endphp

                @if($startPage > 1)
                    <a href="{{ $products->url(1) }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">1</a>
                    @if($startPage > 2)
                    <span class="px-4 py-2 bg-gray-100 rounded text-gray-400">...</span>
                    @endif
                @endif

                @for($page = $startPage; $page <= $endPage; $page++)
                    @if($page == $currentPage)
                    <span class="px-4 py-2 text-white rounded" style="background-color: #ed1c24;">{{ $page }}</span>
                    @else
                    <a href="{{ $products->url($page) }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">{{ $page }}</a>
                    @endif
                @endfor

                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                    <span class="px-4 py-2 bg-gray-100 rounded text-gray-400">...</span>
                    @endif
                    <a href="{{ $products->url($lastPage) }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">{{ $lastPage }}</a>
                @endif

                @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">»</a>
                @else
                <span class="px-4 py-2 bg-gray-100 rounded text-gray-400 cursor-not-allowed">»</span>
                @endif
            </nav>
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">{{ config('texts.brand_detail_no_products_brand') }}</p>
        </div>
        @endif
    </section>
</main>

<script>
function handleSortChange(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', value);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
</script>

@push('scripts')
<script>
// Scroll to products section when category filter is active
document.addEventListener('DOMContentLoaded', function() {
    // Check if hash exists or category parameter exists
    const hash = window.location.hash;
    const urlParams = new URLSearchParams(window.location.search);
    
    if (hash === '#products-section' || urlParams.has('category')) {
        setTimeout(function() {
            const productsSection = document.getElementById('products-section');
            if (productsSection) {
                // Scroll with offset to account for fixed headers if any
                const offset = 80; // Adjust this value if you have fixed header
                const elementPosition = productsSection.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - offset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        }, 300);
    }
});
</script>
@if($hasImages)
<script>
(function() {
    let retryCount = 0;
    const maxRetries = 50; // 5 seconds max wait
    
    function initBrandSlider() {
        const mainSliderEl = document.querySelector('.brand-slider-main');
        const thumbsSliderEl = document.querySelector('.brand-slider-thumbs');
        
        if (!mainSliderEl) return;
        
        // Check if Swiper is loaded
        if (typeof Swiper === 'undefined') {
            retryCount++;
            if (retryCount < maxRetries) {
                setTimeout(initBrandSlider, 100);
            }
            return;
        }
        
        try {
            // Initialize thumbnail slider first
            let thumbsSlider = null;
            if (thumbsSliderEl) {
                thumbsSlider = new Swiper(thumbsSliderEl, {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesProgress: true,
                    breakpoints: {
                        640: {
                            slidesPerView: 5,
                        },
                        1024: {
                            slidesPerView: 6,
                        },
                    },
                });
            }
            
            // Check if pagination element exists
            const paginationEl = document.querySelector('.brand-slider-pagination');
            
            // Initialize main slider
            const mainSlider = new Swiper(mainSliderEl, {
                spaceBetween: 10,
                slidesPerView: 1,
                autoHeight: true, // Auto adjust height based on slide content
                navigation: {
                    nextEl: '.brand-slider-next',
                    prevEl: '.brand-slider-prev',
                },
                pagination: paginationEl ? {
                    el: '.brand-slider-pagination',
                    clickable: true,
                    dynamicBullets: true,
                    type: 'bullets',
                } : false,
                thumbs: thumbsSlider ? {
                    swiper: thumbsSlider
                } : null,
                loop: false,
                touchEventsTarget: 'container',
                simulateTouch: true,
                allowTouchMove: true,
                grabCursor: true,
            });
            
            // Add click handler to thumbnails
            if (thumbsSliderEl) {
                const thumbSlides = thumbsSliderEl.querySelectorAll('.swiper-slide');
                thumbSlides.forEach((slide, index) => {
                    slide.addEventListener('click', () => {
                        mainSlider.slideTo(index);
                    });
                });
            }
        } catch (error) {
            console.error('Error initializing brand slider:', error);
        }
    }
    
    // Try to initialize immediately or wait for DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBrandSlider);
    } else {
        // DOM is already ready, try to initialize
        initBrandSlider();
    }
})();
</script>
@endif
@endpush

<style>
.brand-slider-main .swiper-button-next,
.brand-slider-main .swiper-button-prev {
    color: #ed1c24;
    background: rgba(255, 255, 255, 0.9);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.brand-slider-main .swiper-button-next:after,
.brand-slider-main .swiper-button-prev:after {
    font-size: 18px;
    font-weight: bold;
}

.brand-slider-main .swiper-button-next:hover,
.brand-slider-main .swiper-button-prev:hover {
    background: rgba(255, 255, 255, 1);
}

/* Navigation buttons styling for all screens */
.brand-slider-main .swiper-button-next,
.brand-slider-main .swiper-button-prev {
    display: flex !important;
}

/* Pagination dots styling - show on all screens */
.brand-slider-main .swiper-pagination {
    bottom: 15px !important;
    position: absolute;
    width: 100%;
    text-align: center;
}

.brand-slider-main .swiper-pagination-bullet {
    width: 8px;
    height: 8px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 1;
    margin: 0 4px;
    transition: all 0.3s;
}

.brand-slider-main .swiper-pagination-bullet-active {
    background: #ed1c24;
    width: 24px;
    border-radius: 4px;
}

.brand-slider-thumbs .swiper-slide-thumb-active > div {
    border-color: #ed1c24 !important;
}

/* Ensure slider is touchable on mobile */
.brand-slider-main {
    touch-action: pan-y pinch-zoom;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    user-select: none;
}

/* Make slider height auto to fit image size */
.brand-slider-main .swiper-wrapper {
    align-items: stretch;
}

.brand-slider-main .swiper-slide {
    height: auto;
    display: flex;
}

.brand-slider-main .swiper-slide > div {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.brand-slider-main .swiper-slide img {
    display: block;
    width: 100%;
    height: auto;
    max-width: 100%;
    object-fit: contain;
}

/* Mobile: Full image without padding */
@media (max-width: 1023px) {
    .brand-slider-main {
        border-radius: 0;
    }
}
</style>
@endsection

