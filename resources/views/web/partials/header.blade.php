<header class="shadow-md sticky top-0 bg-white z-50">
    <div class="container mx-auto">
    <marquee style="">
        <div class="float-left">
            <p>
                {!! $settings->branch_address !!}
            </p>
        </div>
    </marquee>
    </div>
    <div class="container mx-auto px-4 py-3 flex justify-between items-center lg:hidden">
    
        <button id="mobile-menu-btn" class="text-3xl text-gray-700">☰</button>
        <a href="{{ route('home') }}" class="flex flex-col items-center flex-shrink-0">
            <img src="https://matkinhsaigon.com.vn/img/setting/1751185753-Logo_mksg_2025.png" alt="{{ config('texts.alt_logo_premium') }}"
                class="h-12 md:h-14">
        </a>
        <div class="flex items-center space-x-4">
            <button id="search-btn-mobile" class="text-gray-700 hover:text-red-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
            <button id="cart-btn"
                class="relative w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span id="cart-count"
                    class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
            </button>
        </div>
    </div>

    <nav class="hidden lg:block bg-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center py-3">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="https://matkinhsaigon.com.vn/img/setting/1751185753-Logo_mksg_2025.png" alt="{{ config('texts.alt_logo') }}"
                    class="h-8 mr-3">
            </a>
            <ul class="flex space-x-6 text-sm font-medium">
                @php
                    $currentUrl = request()->url();
                    $currentPath = request()->path();
                    $isHome = request()->routeIs('home');
                @endphp
                <li>
                    <a href="{{ route('home') }}" 
                       class="font-bold hover:text-[#11b3f1] transition-colors {{ $isHome ? 'text-[#11b3f1]' : 'text-gray-600' }}">
                        {{ config('texts.nav_home') }}
                    </a>
                </li>

                @if(isset($categories) && $categories->count() > 0)
                    @foreach($categories as $category)
                        @php
                            $hasChildren = isset($category->chillParent) && $category->chillParent->count() > 0;
                            $childCount = $hasChildren ? $category->chillParent->count() : 0;
                            // Tính col-span dựa trên số lượng children (tối đa 6 cột)
                            $colClass = 'col-span-2'; // Mặc định 2 cột
                            if ($childCount == 1)
                                $colClass = 'col-span-12';
                            elseif ($childCount == 2)
                                $colClass = 'col-span-6';
                            elseif ($childCount == 3)
                                $colClass = 'col-span-4';
                            elseif ($childCount == 4)
                                $colClass = 'col-span-3';
                            elseif ($childCount == 5)
                                $colClass = 'col-span-2';
                            elseif ($childCount >= 6)
                                $colClass = 'col-span-2';

                            // Xác định base path theo type của category
                            $basePath = '/san-pham/';
                            // Tin tức
                            if (isset($category->type) && $category->type === 'new') {
                                $basePath = '/';
                            }
                            // Thương hiệu
                            if (isset($category->type) && $category->type === 'brand') {
                                $basePath = '/';
                            }
                            // Đối tác
                            if (isset($category->type) && $category->type === 'partner') {
                                $basePath = '/';
                            }
                            
                            $categoryUrl = $category->alias ? url($basePath . $category->alias) : '#';
                            $categoryPath = $category->alias ? trim($basePath . $category->alias, '/') : '';
                            
                            // Kiểm tra active state - so sánh path hiện tại với path của category
                            $isActive = false;
                            if ($categoryPath) {
                                // Kiểm tra nếu path hiện tại bắt đầu bằng category path
                                $isActive = str_starts_with($currentPath, $categoryPath);
                            }
                        @endphp
                        <li class="{{ $hasChildren ? 'has-mega-menu relative group' : '' }}">
                            <a href="{{ $categoryUrl }}"
                                class="font-bold hover:text-[#11b3f1] transition-colors text-base {{ $isActive ? 'text-[#11b3f1]' : 'text-gray-600' }}">
                                {{ strtoupper($category->name ?? $category->title ?? 'Category') }}
                            </a>
                            @if($hasChildren)
                                <div class="mega-menu-content bg-white shadow-xl py-6 group-hover:block">
                                    <div class="container mx-auto grid grid-cols-12 gap-8 text-gray-700">
                                        @foreach($category->chillParent as $child)
                                            <div class="{{ $colClass }}">
                                                <a href="{{ $child->alias ? url($basePath . $category->alias . '/' . $child->alias) : '#' }}"
                                                    class="font-bold mb-3 text-red-600 hover:text-red-700 transition-colors block">
                                                    {{ strtoupper($child->name ?? $child->title ?? 'Sub Category') }}
                                                </a>
                                                @if(isset($child->childLevelParent) && $child->childLevelParent->count() > 0)
                                                    @php
                                                        $itemsWithIcon = $child->childLevelParent->filter(function ($item) {
                                                            return isset($item->show_icon) && $item->show_icon == 1 && !empty($item->icon);
                                                        });
                                                        $itemsWithoutIcon = $child->childLevelParent->filter(function ($item) {
                                                            return !isset($item->show_icon) || $item->show_icon != 1 || empty($item->icon);
                                                        });
                                                    @endphp

                                                    @if($itemsWithIcon->count() > 0)
                                                        <div class="grid grid-cols-10 gap-0 mb-3 w-full overflow-hidden">
                                                            @foreach($itemsWithIcon as $grandChild)
                                                                @php
                                                                    $fullPath = $category->alias . '/' . $child->alias . '/' . $grandChild->alias;
                                                                @endphp
                                                                <button type="button"
                                                                    aria-label="{{ $grandChild->name ?? $grandChild->title ?? 'Icon' }}"
                                                                    onclick="window.location.href='{{ $grandChild->alias ? url($basePath . $fullPath) : '#' }}'"
                                                                    class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md flex-shrink-0"
                                                                    style="background-image: url('{{ $grandChild->getIconImages() }}'); background-size: cover; background-position: center; background-repeat: no-repeat; max-width: 40px; max-height: 40px;">
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if($itemsWithoutIcon->count() > 0)
                                                        <ul class="space-y-2 text-sm">
                                                            @foreach($itemsWithoutIcon as $grandChild)
                                                                @php
                                                                    $fullPath = $category->alias . '/' . $child->alias . '/' . $grandChild->alias;
                                                                @endphp
                                                                <li>
                                                                    <a href="{{ $grandChild->alias ? url($basePath . $fullPath) : '#' }}"
                                                                        class="hover:text-red-600">
                                                                        {{ $grandChild->name ?? $grandChild->title ?? 'Item' }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                @else
                                                    <ul class="space-y-2 text-sm">
                                                        <li>
                                                            @php
                                                                $childPath = $category->alias . '/' . $child->alias;
                                                            @endphp
                                                            <a href="{{ $child->alias ? url($basePath . $childPath) : '#' }}"
                                                                class="hover:text-red-600">
                                                                {{ config('texts.view_all') }}
                                                            </a>
                                                        </li>
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="flex items-center space-x-4">
                <button id="search-btn-desktop" class="text-xl text-gray-600 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                <button id="cart-btn-desktop"
                    class="relative w-8 h-8 flex items-center justify-center border border-gray-300 rounded-full hover:border-red-600 transition-colors">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span id="cart-count-desktop"
                        class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                </button>
            </div>
        </div>
    </nav>
</header>

{{-- Search Modal --}}
<div id="search-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-start justify-center pt-20 md:pt-32">
    <div class="bg-white rounded-lg shadow-2xl w-full max-w-2xl mx-4 max-h-[80vh] flex flex-col">
        {{-- Search Header --}}
        <div class="p-4 border-b border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800">{{ config('texts.search_title') }}</h2>
                <button id="search-close-btn" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            {{-- Search Input --}}
            <div class="relative">
                <input type="text" 
                       id="search-input" 
                       placeholder="{{ config('texts.search_placeholder') }}" 
                       class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <button id="search-submit-btn" class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    {{ config('texts.search_button') }}
                </button>
            </div>
            
            {{-- Search Tabs --}}
            <div class="flex space-x-2 mt-4 flex-wrap">
                <button data-search-type="all" class="search-tab px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-red-600 text-white">
                    {{ config('texts.search_all') }}
                </button>
                <button data-search-type="product" class="search-tab px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                    {{ config('texts.search_product') }}
                </button>
                <button data-search-type="news" class="search-tab px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                    {{ config('texts.search_news') }}
                </button>
                <button data-search-type="brand" class="search-tab px-4 py-2 rounded-lg text-sm font-medium transition-colors bg-gray-100 text-gray-700 hover:bg-gray-200">
                    {{ config('texts.search_brand') }}
                </button>
            </div>
        </div>
        
        {{-- Search Results --}}
        <div id="search-results" class="flex-1 overflow-y-auto p-4">
            <div class="text-center text-gray-500 py-8">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-sm">{{ config('texts.search_enter_keyword') }}</p>
            </div>
        </div>
        
        {{-- Loading Indicator --}}
        <div id="search-loading" class="hidden p-4 border-t border-gray-200">
            <div class="flex items-center justify-center space-x-2 text-gray-600">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-sm">{{ config('texts.search_loading') }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const searchModal = document.getElementById('search-modal');
    const searchInput = document.getElementById('search-input');
    const searchSubmitBtn = document.getElementById('search-submit-btn');
    const searchCloseBtn = document.getElementById('search-close-btn');
    const searchResults = document.getElementById('search-results');
    const searchLoading = document.getElementById('search-loading');
    const searchTabs = document.querySelectorAll('.search-tab');
    
    let currentSearchType = 'all';
    let searchTimeout = null;
    
    // Mở modal
    function openSearchModal() {
        searchModal.classList.remove('hidden');
        searchModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        setTimeout(() => searchInput.focus(), 100);
    }
    
    // Đóng modal
    function closeSearchModal() {
        searchModal.classList.add('hidden');
        searchModal.classList.remove('flex');
        document.body.style.overflow = '';
        searchInput.value = '';
            searchResults.innerHTML = '<div class="text-center text-gray-500 py-8"><svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg><p class="text-sm">{{ config('texts.search_enter_keyword') }}</p></div>';
    }
    
    // Event listeners cho nút mở
    document.getElementById('search-btn-mobile')?.addEventListener('click', openSearchModal);
    document.getElementById('search-btn-desktop')?.addEventListener('click', openSearchModal);
    
    // Event listener cho nút đóng
    searchCloseBtn.addEventListener('click', closeSearchModal);
    
    // Đóng khi click outside
    searchModal.addEventListener('click', function(e) {
        if (e.target === searchModal) {
            closeSearchModal();
        }
    });
    
    // Đóng bằng ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !searchModal.classList.contains('hidden')) {
            closeSearchModal();
        }
    });
    
    // Tab switching
    searchTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            currentSearchType = this.dataset.searchType;
            searchTabs.forEach(t => {
                t.classList.remove('bg-red-600', 'text-white');
                t.classList.add('bg-gray-100', 'text-gray-700');
            });
            this.classList.remove('bg-gray-100', 'text-gray-700');
            this.classList.add('bg-red-600', 'text-white');
            
            // Tìm kiếm lại nếu đã có từ khóa
            if (searchInput.value.trim()) {
                performSearch();
            }
        });
    });
    
    // Tìm kiếm
    function performSearch() {
        const keyword = searchInput.value.trim();
        if (!keyword) {
            searchResults.innerHTML = '<div class="text-center text-gray-500 py-8"><p class="text-sm">{{ config('texts.search_please_enter') }}</p></div>';
            return;
        }
        
        searchLoading.classList.remove('hidden');
        searchResults.innerHTML = '';
        
        fetch('{{ route("search") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                keyword: keyword,
                type: currentSearchType
            })
        })
        .then(response => response.json())
        .then(data => {
            searchLoading.classList.add('hidden');
            if (data.success) {
                displayResults(data.results, keyword);
            } else {
                searchResults.innerHTML = `<div class="text-center text-red-500 py-8"><p class="text-sm">${data.message || '{{ config('texts.search_error') }}'}</p></div>`;
            }
        })
        .catch(error => {
            searchLoading.classList.add('hidden');
            searchResults.innerHTML = '<div class="text-center text-red-500 py-8"><p class="text-sm">{{ config('texts.search_error_message') }}</p></div>';
            console.error('Search error:', error);
        });
    }
    
    // Hiển thị kết quả
    function displayResults(results, keyword) {
        let html = '';
        const hasProducts = results.products && results.products.length > 0;
        const hasNews = results.news && results.news.length > 0;
        const hasBrands = results.brands && results.brands.length > 0;
        
        if (!hasProducts && !hasNews && !hasBrands) {
            html = `
                <div class="text-center text-gray-500 py-8">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-medium mb-1">{{ config('texts.search_no_results') }}</p>
                    <p class="text-xs text-gray-400">{{ config('texts.search_try_other') }}</p>
                </div>
            `;
        } else {
            // Sản phẩm
            if (hasProducts && (currentSearchType === 'all' || currentSearchType === 'product')) {
                html += '<div class="mb-6"><h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center"><svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>{{ config('texts.search_product') }} (' + results.products.length + ')</h3><div class="space-y-3">';
                results.products.forEach(product => {
                    html += `
                        <a href="${product.url}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
                            <img src="${product.image}" alt="${product.name}" class="w-16 h-16 object-cover rounded" loading="lazy">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-2">${product.name}</h4>
                                <p class="text-sm font-bold text-red-600 mt-1">${formatPrice(product.priceSale)}</p>
                            </div>
                        </a>
                    `;
                });
                html += '</div></div>';
            }
            
            // Tin tức
            if (hasNews && (currentSearchType === 'all' || currentSearchType === 'news')) {
                html += '<div><h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center"><svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>{{ config('texts.search_news') }} (' + results.news.length + ')</h3><div class="space-y-3">';
                results.news.forEach(news => {
                    html += `
                        <a href="${news.url}" class="flex items-start space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100">
                            ${news.image ? `<img src="${news.image}" alt="${news.title}" class="w-20 h-20 object-cover rounded flex-shrink-0" loading="lazy">` : '<div class="w-20 h-20 bg-gray-200 rounded flex-shrink-0"></div>'}
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 mb-1">${news.title}</h4>
                                ${news.description ? `<p class="text-xs text-gray-500 line-clamp-2 mb-1">${news.description}</p>` : ''}
                                ${news.date ? `<p class="text-xs text-gray-400">${news.date}</p>` : ''}
                            </div>
                        </a>
                    `;
                });
                html += '</div></div>';
            }
            
            // Thương hiệu
            if (hasBrands && (currentSearchType === 'all' || currentSearchType === 'brand')) {
                html += '<div class="mb-6"><h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center"><svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>{{ config('texts.search_brand') }} (' + results.brands.length + ')</h3><div class="grid grid-cols-2 md:grid-cols-4 gap-3">';
                results.brands.forEach(brand => {
                    html += `
                        <a href="${brand.url}" class="flex flex-col items-center justify-center p-4 rounded-lg hover:bg-gray-50 transition-colors border border-gray-100 group">
                            ${brand.logo ? `<img src="${brand.logo}" alt="${brand.name}" class="w-16 h-16 object-contain mb-2 group-hover:scale-110 transition-transform" loading="lazy">` : '<div class="w-16 h-16 bg-gray-200 rounded mb-2 flex items-center justify-center"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg></div>'}
                            <h4 class="text-xs font-semibold text-gray-900 text-center line-clamp-2 group-hover:text-red-600 transition-colors">${brand.name}</h4>
                        </a>
                    `;
                });
                html += '</div></div>';
            }
        }
        
        searchResults.innerHTML = html;
    }
    
    // Format price
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price) + ' đ';
    }
    
    // Event listeners
    searchSubmitBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });
    
    // Debounce search khi gõ
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const keyword = this.value.trim();
        if (keyword.length >= 2) {
            searchTimeout = setTimeout(performSearch, 500);
        } else if (keyword.length === 0) {
            searchResults.innerHTML = '<div class="text-center text-gray-500 py-8"><svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg><p class="text-sm">{{ config('texts.search_enter_keyword') }}</p></div>';
        }
    });
})();
</script>
@endpush