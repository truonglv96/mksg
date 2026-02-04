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

    {{-- Brand Categories & Products --}}
    <section class="mb-12" id="brand-products">
        <div class="flex flex-col gap-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Sản phẩm thuộc thương hiệu "{{ $brand->name }}"
                </h2>
            <p class="text-sm text-gray-500 mt-1">
                Chọn chất liệu để lọc nhanh sản phẩm.
            </p>
            </div>

            @if(isset($brandMaterials) && $brandMaterials->count() > 0)
            <div class="md:hidden">
                <button type="button"
                        id="brand-material-filter-btn"
                        class="w-full px-4 py-3 bg-red-600 text-white rounded-lg font-medium flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Lọc chất liệu
                </button>
            </div>

            <div class="hidden md:flex flex-wrap gap-2 border-b border-gray-100 pb-2">
                <button type="button"
                        class="brand-material-chip px-4 py-2 rounded-full text-sm font-semibold border border-red-200 bg-red-50 text-red-600 shadow-sm transition-all {{ empty($selectedMaterialId) || $selectedMaterialId === 'all' ? 'is-active' : '' }}"
                        data-material-id="all">
                    Tất cả
                </button>
                @foreach($brandMaterials as $material)
                @php
                    $materialCount = $brandMaterialCounts[$material->id] ?? 0;
                @endphp
                <button type="button"
                        class="brand-material-chip px-4 py-2 rounded-full text-sm font-medium border border-gray-200 bg-white text-gray-700 hover:border-red-200 hover:text-red-600 hover:bg-red-50 transition-all {{ (string) $selectedMaterialId === (string) $material->id ? 'is-active bg-red-50 text-red-600 border-red-200' : '' }}"
                        data-material-id="{{ $material->id }}">
                    {{ $material->name }}
                    <!-- <span class="ml-1 text-xs text-gray-400">
                        ({{ number_format($materialCount, 0, ',', '.') }})
                    </span> -->
                </button>
                @endforeach
            </div>
            @else
            <div class="bg-white border border-dashed border-gray-200 rounded-xl p-6 text-gray-500">
                Chưa có chất liệu phù hợp cho thương hiệu này.
            </div>
            @endif
        </div>
    </section>

    <section class="mb-6">

        @if(isset($processedBrandProducts) && $processedBrandProducts->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 2xl:grid-cols-4 gap-4" id="brand-products-grid">
            @foreach($processedBrandProducts as $item)
            @php
                $product = $item['product'];
                $mainImage = $item['mainImage'];
                $hoverImage = $item['hoverImage'];
                $priceSale = $item['priceSale'];
                $price = $item['price'];
                $discount = $item['discount'];
                $productUrl = $product->alias
                    ? route('product.detail', ['categoryPath' => $product->getCategoryPath(), 'productAlias' => $product->alias])
                    : '#';
                $brandName = $product->brand_name ?? ($product->brand ? $product->brand->name : '');
            @endphp
            <div class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group brand-product-card">
                <a href="{{ $productUrl }}" class="relative overflow-hidden block">
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
                    <a href="{{ $productUrl }}">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem] product-title" style="color: #000; transition: color 0.3s;" onmouseover="this.style.color='#ed1c24'" onmouseout="this.style.color='#000'">{{ $product->name }}</h3>
                    </a>
                    @if($brandName)
                    <p class="text-xs text-gray-500 mb-2">{{ $brandName }}</p>
                    @endif
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
                        <a href="{{ $productUrl }}"
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

        @if(isset($brandProducts) && $brandProducts->hasPages())
        <div class="mt-8 flex justify-center" id="brand-products-pagination">
            <nav class="flex gap-2 flex-wrap justify-center">
                @php
                    $brandProducts->appends(request()->query());
                @endphp
                @if($brandProducts->onFirstPage())
                <span class="px-4 py-2 bg-gray-100 rounded text-gray-400 cursor-not-allowed">«</span>
                @else
                <a href="{{ $brandProducts->previousPageUrl() }}#brand-products" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">«</a>
                @endif

                @php
                    $currentPage = $brandProducts->currentPage();
                    $lastPage = $brandProducts->lastPage();
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($lastPage, $currentPage + 2);
                @endphp

                @if($startPage > 1)
                    <a href="{{ $brandProducts->url(1) }}#brand-products" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">1</a>
                    @if($startPage > 2)
                    <span class="px-4 py-2 bg-gray-100 rounded text-gray-400">...</span>
                    @endif
                @endif

                @for($page = $startPage; $page <= $endPage; $page++)
                    @if($page == $currentPage)
                    <span class="px-4 py-2 text-white rounded" style="background-color: #ed1c24;">{{ $page }}</span>
                    @else
                    <a href="{{ $brandProducts->url($page) }}#brand-products" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">{{ $page }}</a>
                    @endif
                @endfor

                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                    <span class="px-4 py-2 bg-gray-100 rounded text-gray-400">...</span>
                    @endif
                    <a href="{{ $brandProducts->url($lastPage) }}#brand-products" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">{{ $lastPage }}</a>
                @endif

                @if($brandProducts->hasMorePages())
                <a href="{{ $brandProducts->nextPageUrl() }}#brand-products" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">»</a>
                @else
                <span class="px-4 py-2 bg-gray-100 rounded text-gray-400 cursor-not-allowed">»</span>
                @endif
            </nav>
        </div>
        @endif
        @else
        <div class="bg-white border border-dashed border-gray-200 rounded-xl p-6 text-gray-500">
            Chưa có sản phẩm nào thuộc thương hiệu này.
        </div>
        @endif
    </section>

</main>

@push('scripts')
<script>
(function () {
    const materialChips = document.querySelectorAll('.brand-material-chip');
    const countEl = document.getElementById('brand-products-count');
    const allFilter = 'all';
    const paginationEl = document.getElementById('brand-products-pagination');
    const mobileFilterBtn = document.getElementById('brand-material-filter-btn');
    const mobileFilterPanel = document.getElementById('brand-material-mobile-panel');
    const mobileFilterOverlay = document.getElementById('brand-material-mobile-overlay');
    const mobileFilterClose = document.getElementById('brand-material-mobile-close');

    function handleMaterialClick(materialId) {
        const normalizedId = String(materialId || '').trim();
        const url = new URL(window.location.href);
        if (!normalizedId || normalizedId === allFilter) {
            url.searchParams.delete('brand_material_id');
        } else {
            url.searchParams.set('brand_material_id', normalizedId);
        }
        url.searchParams.delete('page');
        url.hash = 'brand-products';
        window.location.href = url.toString();
    }

    materialChips.forEach(chip => {
        chip.addEventListener('click', () => handleMaterialClick(chip.dataset.materialId));
    });

    function openMobileFilter() {
        if (!mobileFilterPanel || !mobileFilterOverlay) return;
        mobileFilterPanel.classList.remove('-translate-x-full');
        mobileFilterOverlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeMobileFilter() {
        if (!mobileFilterPanel || !mobileFilterOverlay) return;
        mobileFilterPanel.classList.add('-translate-x-full');
        mobileFilterOverlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    if (mobileFilterBtn) {
        mobileFilterBtn.addEventListener('click', openMobileFilter);
    }
    if (mobileFilterClose) {
        mobileFilterClose.addEventListener('click', closeMobileFilter);
    }
    if (mobileFilterOverlay) {
        mobileFilterOverlay.addEventListener('click', closeMobileFilter);
    }

})();
</script>
@endpush

@if(isset($brandMaterials) && $brandMaterials->count() > 0)
<div id="brand-material-mobile-overlay" class="fixed inset-0 bg-black/40 z-[70] hidden md:hidden"></div>
<aside id="brand-material-mobile-panel"
       class="fixed inset-y-0 left-0 w-80 max-w-[85vw] bg-white shadow-2xl z-[80] transform -translate-x-full transition-transform duration-300 md:hidden overflow-y-auto">
    <div class="p-4">
        <div class="flex items-center justify-between mb-4 pb-3 border-b">
            <h3 class="text-lg font-bold text-gray-800">Lọc chất liệu</h3>
            <button id="brand-material-mobile-close" class="text-gray-600 hover:text-red-600" type="button">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="flex flex-wrap gap-2">
            <button type="button"
                    class="brand-material-chip px-4 py-2 rounded-full text-sm font-semibold border border-red-200 bg-red-50 text-red-600 shadow-sm transition-all {{ empty($selectedMaterialId) || $selectedMaterialId === 'all' ? 'is-active' : '' }}"
                    data-material-id="all">
                Tất cả
            </button>
            @foreach($brandMaterials as $material)
            @php
                $materialCount = $brandMaterialCounts[$material->id] ?? 0;
            @endphp
            <button type="button"
                    class="brand-material-chip px-4 py-2 rounded-full text-sm font-medium border border-gray-200 bg-white text-gray-700 hover:border-red-200 hover:text-red-600 hover:bg-red-50 transition-all {{ (string) $selectedMaterialId === (string) $material->id ? 'is-active bg-red-50 text-red-600 border-red-200' : '' }}"
                    data-material-id="{{ $material->id }}">
                {{ $material->name }}
                <!-- <span class="ml-1 text-xs text-gray-400">
                    ({{ number_format($materialCount, 0, ',', '.') }})
                </span> -->
            </button>
            @endforeach
        </div>
    </div>
</aside>
@endif

@push('scripts')
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

