@extends('web.master')

@section('title', $title ?? config('texts.default_title'))

@section('content')
<main class="container mx-auto px-4 py-8">

    {{-- Breadcrumb Component --}}
    @include('web.partials.breadcrumb')

    <!-- Frame Styles Filter Section -->
    <section class="mb-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">{{ config('texts.frame_style_title') }}</h2>

        <!-- Brand Filter Grid -->
        @if(isset($brands) && $brands->count() > 0)
        <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-12 gap-3 mb-4">
            @foreach($brands as $brand)
            <button type="button"
                data-brand-id="{{ $brand->id }}"
                class="brand-filter-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center {{ request('brand_id') == $brand->id ? 'border-red-600 shadow-md' : '' }}">
                <div class="flex justify-center mb-2">
                    @if($brand->logo)
                    <img src="{{ asset('img/brand/' . $brand->logo) }}" alt="{{ $brand->name }}" class="h-8 object-contain">
                    @else
                    <span class="text-xs text-gray-500">{{ $brand->name }}</span>
                    @endif
                </div>
            </button>
            @endforeach
        </div>
        @endif

        <!-- Mobile Filter Button -->
        <div class="md:hidden mb-4">
            <button id="mobile-filter-btn"
                class="w-full px-4 py-3 bg-red-600 text-white rounded-lg font-medium flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                {{ config('texts.mobile_filter_button') }}
            </button>
        </div>

        <!-- Sort & Results Info -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div class="text-sm text-gray-600">
                @if(isset($products) && $products->count() > 0)
                    {{ config('texts.results_showing') }} <span class="font-semibold">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span> {{ config('texts.results_of') }} <span class="font-semibold">{{ $products->total() }}</span> {{ config('texts.results') }}
                @else
                    {{ config('texts.results_zero') }}
                @endif
            </div>
            <select
                id="sort-select"
                name="sort"
                class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium text-gray-700 border-none focus:ring-2 focus:ring-red-600"
                onchange="handleSortChange(this.value)">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ config('texts.sort_newest') }}</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ config('texts.sort_price_asc') }}</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ config('texts.sort_price_desc') }}</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ config('texts.sort_name_asc') }}</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ config('texts.sort_name_desc') }}</option>
            </select>
        </div>
    </section>

    <!-- Main Content: Sidebar + Products -->
    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Desktop Sidebar Filter -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-4">

                <!-- Tìm Theo Giá -->
                @if(isset($priceRanges) && array_sum($priceRanges) > 0)
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">{{ config('texts.filter_by_price') }}</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @if(isset($priceRanges['under_500k']) && $priceRanges['under_500k'] > 0)
                        <button type="button" 
                            data-price-min="0" 
                            data-price-max="500000"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all {{ request('price_min') == '0' && request('price_max') == '500000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_under_500k') }}</button>
                        @endif
                        @if(isset($priceRanges['500k_1m']) && $priceRanges['500k_1m'] > 0)
                        <button type="button" 
                            data-price-min="500000" 
                            data-price-max="1000000"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all {{ request('price_min') == '500000' && request('price_max') == '1000000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_500k_1m') }}</button>
                        @endif
                        @if(isset($priceRanges['1m_3m']) && $priceRanges['1m_3m'] > 0)
                        <button type="button" 
                            data-price-min="1000000" 
                            data-price-max="3000000"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all {{ request('price_min') == '1000000' && request('price_max') == '3000000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_1m_3m') }}</button>
                        @endif
                        @if(isset($priceRanges['3m_5m']) && $priceRanges['3m_5m'] > 0)
                        <button type="button" 
                            data-price-min="3000000" 
                            data-price-max="5000000"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all {{ request('price_min') == '3000000' && request('price_max') == '5000000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_3m_5m') }}</button>
                        @endif
                        @if(isset($priceRanges['over_5m']) && $priceRanges['over_5m'] > 0)
                        <button type="button" 
                            data-price-min="5000000" 
                            data-price-max="999999999"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all col-span-2 {{ request('price_min') == '5000000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_over_5m') }}</button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Màu Sắc -->
                @if(isset($colors) && $colors->count() > 0)
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">{{ config('texts.color') }}</h3>
                    <div class="grid grid-cols-4 sm:grid-cols-6 xl:grid-cols-8 gap-1 sm:gap-1.5">
                        @foreach($colors as $color)
                        <button type="button" 
                            data-color-id="{{ $color->id }}"
                            aria-label="{{ $color->name ?? config('texts.color_fallback') }}"
                            class="color-filter-btn w-10 h-10 rounded-md border-2 shadow-sm bg-white bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center {{ request('color_id') == $color->id ? 'border-red-600 ring-2 ring-red-600 ring-offset-1 scale-110' : 'border-gray-200' }}"
                            @if($color->url_img)
                            style="background-image: url('{{ asset('img/color/' . $color->url_img) }}'); background-color: #ffffff; background-size: contain; background-repeat: no-repeat;"
                            @endif
                            title="{{ $color->name ?? config('texts.color_fallback') }}"
                            >
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Chất Liệu Gọng -->
                @if(isset($materials) && $materials->count() > 0)
                <div>
                    <h3 class="font-bold text-gray-800 mb-3">{{ config('texts.material') }}</h3>
                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @foreach($materials as $material)
                        <button type="button"
                            data-material-id="{{ $material->id }}"
                            class="material-filter-btn w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left {{ request('material_id') == $material->id ? 'bg-red-50 text-red-600 border-red-600' : '' }}">
                            {{ $material->name }}
                            <span class="text-gray-500">({{ $material->product_count ?? 0 }})</span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </aside>

        <!-- Mobile Filter Sidebar -->
        <div id="mobile-filter-sidebar"
            class="fixed inset-y-0 left-0 w-80 bg-white shadow-2xl z-[70] transform -translate-x-full transition-transform duration-300 lg:hidden overflow-y-auto">
            <div class="p-4">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6 pb-4 border-b">
                    <h2 class="text-lg font-bold text-gray-800">{{ config('texts.filter_title') }}</h2>
                    <div class="flex gap-3">
                        <button class="text-gray-600 hover:text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        <button id="close-mobile-filter" class="text-gray-600 hover:text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tìm Theo Giá -->
                @if(isset($priceRanges) && array_sum($priceRanges) > 0)
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">{{ config('texts.filter_by_price') }}</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @if(isset($priceRanges['under_500k']) && $priceRanges['under_500k'] > 0)
                        <button type="button" 
                            data-price-min="0" 
                            data-price-max="500000"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all {{ request('price_min') == '0' && request('price_max') == '500000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_under_500k') }}</button>
                        @endif
                        @if(isset($priceRanges['500k_1m']) && $priceRanges['500k_1m'] > 0)
                        <button type="button" 
                            data-price-min="500000" 
                            data-price-max="1000000"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all {{ request('price_min') == '500000' && request('price_max') == '1000000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_500k_1m') }}</button>
                        @endif
                        @if(isset($priceRanges['1m_3m']) && $priceRanges['1m_3m'] > 0)
                        <button type="button" 
                            data-price-min="1000000" 
                            data-price-max="3000000"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all {{ request('price_min') == '1000000' && request('price_max') == '3000000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_1m_3m') }}</button>
                        @endif
                        @if(isset($priceRanges['3m_5m']) && $priceRanges['3m_5m'] > 0)
                        <button type="button" 
                            data-price-min="3000000" 
                            data-price-max="5000000"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all {{ request('price_min') == '3000000' && request('price_max') == '5000000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_3m_5m') }}</button>
                        @endif
                        @if(isset($priceRanges['over_5m']) && $priceRanges['over_5m'] > 0)
                        <button type="button" 
                            data-price-min="5000000" 
                            data-price-max="999999999"
                            class="price-filter-btn px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all col-span-2 {{ request('price_min') == '5000000' ? 'bg-red-50 text-red-600 border-red-600' : '' }}">{{ config('texts.price_over_5m') }}</button>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Màu Sắc -->
                @if(isset($colors) && $colors->count() > 0)
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">{{ config('texts.color') }}</h3>
                    <div class="grid grid-cols-4 sm:grid-cols-6 xl:grid-cols-8 gap-1 sm:gap-1.5">
                        @foreach($colors as $color)
                        <button type="button" 
                            data-color-id="{{ $color->id }}"
                            aria-label="{{ $color->name ?? config('texts.color_fallback') }}"
                            class="color-filter-btn w-10 h-10 rounded-md border-2 shadow-sm bg-white bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md flex items-center justify-center {{ request('color_id') == $color->id ? 'border-red-600 ring-2 ring-red-600 ring-offset-1 scale-110' : 'border-gray-200' }}"
                            @if($color->url_img)
                            style="background-image: url('{{ asset('img/color/' . $color->url_img) }}'); background-color: #ffffff; background-size: contain; background-repeat: no-repeat;"
                            @endif
                            >
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Chất Liệu Gọng -->
                @if(isset($materials) && $materials->count() > 0)
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">{{ config('texts.material') }}</h3>
                    <div class="space-y-2">
                        @foreach($materials as $material)
                        <button type="button"
                            data-material-id="{{ $material->id }}"
                            class="material-filter-btn w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left {{ request('material_id') == $material->id ? 'bg-red-50 text-red-600 border-red-600' : '' }}">
                            {{ $material->name }}
                            <span class="text-gray-500">({{ $material->product_count ?? 0 }})</span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Overlay for Mobile Filter -->
        <div id="mobile-filter-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-[65] hidden lg:hidden"></div>

        <!-- Products Grid -->
        <div class="flex-1">
            @if(isset($products) && $products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 2xl:grid-cols-4 gap-4">
                @foreach($products as $product)
                @php
                    $productImages = \App\Models\ProductImage::getTwoImageCategoryProduct($product->id);
                    $mainImage = $productImages->count() > 0 ? asset('img/product/' . $productImages->first()->image) : asset('img/product/no-image.jpg');
                    $hoverImage = $productImages->count() > 1 ? asset('img/product/' . $productImages->get(1)->image) : $mainImage;
                    $priceSale = $product->price_sale ?? $product->price ?? 0;
                    $price = $product->price ?? 0;
                    $discount = $price > 0 && $priceSale < $price ? round((($price - $priceSale) / $price) * 100) : 0;
                    $brandName = $product->brand_name ?? ($product->brand ? $product->brand->name : '');
                @endphp
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <a href="{{ $product->alias ? route('product.detail', ['categoryPath' => $product->getCategoryPath(), 'productAlias' => $product->alias]) : '#' }}" class="relative overflow-hidden block">
                        @if($discount > 0)
                        <span class="discount-badge absolute top-2 right-2 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-10" style="background-color: #ed1c24;">-{{ $discount }}%</span>
                        @endif
                        <img src="{{ $mainImage }}"
                            alt="{{ $product->name }}"
                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                        <img src="{{ $hoverImage }}"
                            alt="{{ $product->name }} - Hover"
                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                    </a>
                    <div class="p-3">
                        <a href="{{ $product->alias ? route('product.detail', ['categoryPath' => $product->getCategoryPath(), 'productAlias' => $product->alias]) : '#' }}">
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
            @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">{{ config('texts.no_products') }}</p>
                        </div>
            @endif

            <!-- Pagination -->
            @if(isset($products) && $products->hasPages())
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

                    @php
                        $products->appends(request()->query());
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
        </div>

    </div>

</main>

<script>
function handleSortChange(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', value);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}

// Filter by price
document.querySelectorAll('.price-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('price_min', this.dataset.priceMin);
        url.searchParams.set('price_max', this.dataset.priceMax);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });
});

// Filter by color
document.querySelectorAll('.color-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const url = new URL(window.location.href);
        const colorId = this.dataset.colorId;
        if (url.searchParams.get('color_id') == colorId) {
            url.searchParams.delete('color_id');
        } else {
            url.searchParams.set('color_id', colorId);
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });
});

// Filter by material
document.querySelectorAll('.material-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const url = new URL(window.location.href);
        const materialId = this.dataset.materialId;
        if (url.searchParams.get('material_id') == materialId) {
            url.searchParams.delete('material_id');
        } else {
            url.searchParams.set('material_id', materialId);
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });
});

// Filter by brand
document.querySelectorAll('.brand-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const url = new URL(window.location.href);
        const brandId = this.dataset.brandId;
        if (url.searchParams.get('brand_id') == brandId) {
            url.searchParams.delete('brand_id');
        } else {
            url.searchParams.set('brand_id', brandId);
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });
});
</script>
@endsection
