<div id="mobile-sidebar" class="fixed top-0 left-0 w-[80%] h-full bg-white shadow-2xl z-[60] 
        transform -translate-x-full transition-transform duration-300 lg:hidden">
    <div class="p-4 flex justify-end items-center border-b">
        <button id="close-sidebar-btn" class="text-3xl text-gray-700">&times;</button>
    </div>
    <div class="overflow-y-auto h-full pb-20">
        <div class="bg-red-600 text-white text-center py-2 font-bold text-sm">
            <span class="inline-block mr-2 text-lg">🏷️</span> {{ config('texts.mobile_sidebar_sale') }}
        </div>
        <ul class="text-gray-700">
            @php
                $currentPath = request()->path();
                $isHome = request()->routeIs('home');
            @endphp
            <li class="p-3 border-b hover:bg-gray-100">
                <a href="{{ route('home') }}" 
                   class="font-bold hover:text-[#11b3f1] transition-colors {{ $isHome ? 'text-[#11b3f1]' : 'text-gray-600' }}">
                    {{ config('texts.nav_home') }}
                </a>
            </li>
            
            @if(isset($categories) && $categories->count() > 0)
                @foreach($categories as $category)
                    @php
                        $hasChildren = isset($category->chillParent) && $category->chillParent->count() > 0;
                        $toggleId = 'toggle-category-' . $category->id;
                        $submenuId = 'submenu-category-' . $category->id;
                        
                        // Xác định base path theo type của category (giống desktop)
                        $basePath = '/bai-viet-san-pham/';
                        if (isset($category->type) && $category->type === 'new') {
                            $basePath = '/';
                        }
                        if (isset($category->type) && $category->type === 'brand') {
                            $basePath = '/';
                        }

                        if (isset($category->type) && $category->type === 'partner') {
                            $basePath = '/';
                        }
                        
                        $categoryUrl = $category->alias ? url($basePath . $category->alias) : '#';
                        $categoryPath = $category->alias ? trim($basePath . $category->alias, '/') : '';
                        
                        // Kiểm tra active state
                        $isActive = false;
                        if ($categoryPath) {
                            $isActive = str_starts_with($currentPath, $categoryPath);
                        }
                    @endphp
                    @if($hasChildren)
                        <li class="border-b">
                            <div class="p-3 flex justify-between items-center font-bold">
                                <a href="{{ $categoryUrl }}" 
                                   class="category-text flex-1 hover:text-[#11b3f1] transition-colors {{ $isActive ? 'text-[#11b3f1]' : 'text-gray-600' }}">
                                    {{ mb_strtoupper($category->name ?? $category->title ?? 'Category', 'UTF-8') }}
                                </a>
                                <button class="toggle-category-btn p-1 hover:text-red-600 transition-colors" 
                                        data-submenu-id="{{ $submenuId }}">
                                    <svg class="w-5 h-5 toggle-arrow transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                            <ul id="{{ $submenuId }}" class="bg-gray-50 text-sm hidden pl-4">
                                @foreach($category->chillParent as $child)
                                    @php
                                        $hasGrandChildren = isset($child->childLevelParent) && $child->childLevelParent->count() > 0;
                                        $childToggleId = 'toggle-child-' . $child->id;
                                        $childSubmenuId = 'submenu-child-' . $child->id;
                                        $childPath = $category->alias . '/' . $child->alias;
                                    @endphp
                                    @if($hasGrandChildren)
                                        <li class="border-b border-gray-200">
                                            <div class="p-2 flex justify-between items-center">
                                                <a href="{{ $child->alias ? url($basePath . $childPath) : '#' }}" 
                                                   class="child-text flex-1 font-bold text-red-600 hover:text-red-700 transition-colors">
                                                    {{ mb_strtoupper($child->name ?? $child->title ?? 'Sub Category', 'UTF-8') }}
                                                </a>
                                                <button class="toggle-child-btn p-1 hover:text-red-600 transition-colors" 
                                                        data-submenu-id="{{ $childSubmenuId }}">
                                                    <svg class="w-5 h-5 toggle-arrow transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <ul id="{{ $childSubmenuId }}" class="bg-gray-100 pl-6 hidden">
                                                @php
                                                    $itemsWithIcon = $child->childLevelParent->filter(function($item) {
                                                        return isset($item->show_icon) && $item->show_icon == 1 && !empty($item->icon);
                                                    });
                                                    $itemsWithoutIcon = $child->childLevelParent->filter(function($item) {
                                                        return !isset($item->show_icon) || $item->show_icon != 1 || empty($item->icon);
                                                    });
                                                @endphp
                                                
                                                @if($itemsWithIcon->count() > 0)
                                                    <li class="py-2 border-b border-gray-200">
                                                        <div class="grid grid-cols-5 gap-0 mb-2">
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
                                                    </li>
                                                @endif
                                                
                                                @if($itemsWithoutIcon->count() > 0)
                                                    @foreach($itemsWithoutIcon as $grandChild)
                                                        @php
                                                            $fullPath = $category->alias . '/' . $child->alias . '/' . $grandChild->alias;
                                                        @endphp
                                                        <li class="py-2 border-b border-gray-200 last:border-b-0">
                                                            <a href="{{ $grandChild->alias ? url($basePath . $fullPath) : '#' }}" 
                                                               class="block hover:text-red-600">
                                                                {{ $grandChild->name ?? $grandChild->title ?? 'Item' }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </li>
                                    @else
                                        <li class="py-2 px-2 border-b border-gray-200 hover:bg-gray-200">
                                            <a href="{{ $child->alias ? url($basePath . $childPath) : '#' }}" 
                                               class="block font-bold text-red-600 hover:text-red-700 transition-colors">
                                                {{ mb_strtoupper($child->name ?? $child->title ?? 'Sub Category', 'UTF-8') }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="p-3 border-b hover:bg-gray-100">
                            <a href="{{ $categoryUrl }}" 
                               class="font-bold hover:text-[#11b3f1] transition-colors {{ $isActive ? 'text-[#11b3f1]' : 'text-gray-600' }}">
                                {{ mb_strtoupper($category->name ?? $category->title ?? 'Category', 'UTF-8') }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endif
                
    </div>
</div>

