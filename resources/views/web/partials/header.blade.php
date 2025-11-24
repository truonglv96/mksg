<header class="shadow-md sticky top-0 bg-white z-50">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center lg:hidden">
        <button id="mobile-menu-btn" class="text-3xl text-gray-700">☰</button>
        <div class="flex flex-col items-center flex-shrink-0">
            <img src="https://matkinhsaigon.com.vn/img/setting/1751185753-Logo_mksg_2025.png"
                alt="Mắt Kính Hàng Hiệu" class="h-6">
            <span class="text-xs font-serif font-medium mt-0.5 text-gray-600">Mắt Kính Sài Gòn</span>
        </div>
        <div class="flex items-center space-x-4">
            <button class="text-gray-700">
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
            <div class="flex items-center">
                <img src="https://matkinhsaigon.com.vn/img/setting/1751185753-Logo_mksg_2025.png"
                    alt="Mắt Kính Sài Gòn" class="h-8 mr-3">
            </div>
            <ul class="flex space-x-6 text-sm font-medium">
                <li><a href="{{ route('home') }}" class="text-red-600 font-bold hover:text-red-600">TRANG CHỦ</a></li>
                @if(isset($brands) && $brands->count() > 0)
                <li><a href="#" class="text-red-600 font-bold hover:text-red-600">THƯƠNG HIỆU</a></li>
                @endif
                
                @if(isset($categories) && $categories->count() > 0)
                    @foreach($categories as $category)
                        @php
                            $hasChildren = isset($category->chillParent) && $category->chillParent->count() > 0;
                            $childCount = $hasChildren ? $category->chillParent->count() : 0;
                            // Tính col-span dựa trên số lượng children (tối đa 6 cột)
                            $colClass = 'col-span-2'; // Mặc định 2 cột
                            if ($childCount == 1) $colClass = 'col-span-12';
                            elseif ($childCount == 2) $colClass = 'col-span-6';
                            elseif ($childCount == 3) $colClass = 'col-span-4';
                            elseif ($childCount == 4) $colClass = 'col-span-3';
                            elseif ($childCount == 5) $colClass = 'col-span-2';
                            elseif ($childCount >= 6) $colClass = 'col-span-2';
                        @endphp
                        <li class="{{ $hasChildren ? 'has-mega-menu relative group' : '' }}">
                            <a href="{{ $category->alias ? route('product.category', $category->alias) : '#' }}" 
                               class="{{ $hasChildren ? 'text-red-600 font-bold' : 'text-gray-600 font-bold category-link' }}">
                                {{ strtoupper($category->name ?? $category->title ?? 'Category') }}
                            </a>
                            @if($hasChildren)
                                <div class="mega-menu-content bg-white shadow-xl py-6 group-hover:block">
                                    <div class="container mx-auto grid grid-cols-12 gap-8 text-gray-700">
                                        @foreach($category->chillParent as $child)
                                            <div class="{{ $colClass }}">
                                                <h4 class="font-bold mb-3 text-red-600">
                                                    {{ strtoupper($child->name ?? $child->title ?? 'Sub Category') }}
                                                </h4>
                                                @if(isset($child->childLevelParent) && $child->childLevelParent->count() > 0)
                                                    @php
                                                        $itemsWithIcon = $child->childLevelParent->filter(function($item) {
                                                            return isset($item->show_icon) && $item->show_icon == 1 && !empty($item->icon);
                                                        });
                                                        $itemsWithoutIcon = $child->childLevelParent->filter(function($item) {
                                                            return !isset($item->show_icon) || $item->show_icon != 1 || empty($item->icon);
                                                        });
                                                    @endphp
                                                    
                                                    @if($itemsWithIcon->count() > 0)
                                                        <div class="grid grid-cols-10 gap-0 mb-3 w-full overflow-hidden">
                                                            @foreach($itemsWithIcon as $grandChild)
                                                                <button type="button" 
                                                                        aria-label="{{ $grandChild->name ?? $grandChild->title ?? 'Icon' }}"
                                                                        onclick="window.location.href='{{ $grandChild->alias ? route('product.category', $grandChild->alias) : '#' }}'"
                                                                        class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md flex-shrink-0"
                                                                        style="background-image: url('{{ $grandChild->getIconImages() }}'); background-size: cover; background-position: center; background-repeat: no-repeat; max-width: 40px; max-height: 40px;">
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    
                                                    @if($itemsWithoutIcon->count() > 0)
                                                        <ul class="space-y-2 text-sm">
                                                            @foreach($itemsWithoutIcon as $grandChild)
                                                                <li>
                                                                    <a href="{{ $grandChild->alias ? route('product.category', $grandChild->alias) : '#' }}" 
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
                                                            <a href="{{ $child->alias ? route('product.category', $child->alias) : '#' }}" 
                                                               class="hover:text-red-600">
                                                                Xem tất cả
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
                <button class="text-xl text-gray-600">
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

