@foreach($categories as $category)
    @php
        $hasChildren = isset($category->children) && $category->children->count() > 0;
        $categoryId = $category->id;
    @endphp
    <div class="category-item" data-id="{{ $categoryId }}" data-parent-id="{{ $category->parent_id ?? 0 }}">
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center gap-4 flex-1">
                <!-- Drag Handle -->
                <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-grip-vertical"></i>
                </div>
                
                <!-- Toggle Button (only show if has children) -->
                @if($hasChildren)
                    <button onclick="toggleCategory({{ $categoryId }})" 
                            class="category-toggle-btn p-1.5 text-gray-500 hover:text-primary-600 hover:bg-gray-100 rounded transition-colors"
                            data-category-id="{{ $categoryId }}"
                            title="Đóng/Mở danh mục con">
                        <i class="fas fa-chevron-down transition-transform duration-200"></i>
                    </button>
                @else
                    <div class="w-6"></div>
                @endif
                
                <!-- Icon -->
                <div class="category-icon">
                    <i class="fas fa-folder"></i>
                </div>
                
                <!-- Category Info -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-base font-semibold text-gray-900">{{ $category->name }}</h3>
                        <span class="type-badge type-{{ $category->type ?? 'product' }}">
                            @if($category->type == 'product')
                                Sản phẩm
                            @elseif($category->type == 'new')
                                Tin tức
                            @elseif($category->type == 'brand')
                                Thương hiệu
                            @else
                                {{ $category->type ?? 'N/A' }}
                            @endif
                        </span>
                        @if($category->hidden == 0)
                            <span class="px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-700">
                                <i class="fas fa-eye-slash mr-1"></i>Ẩn
                            </span>
                        @endif
                    </div>
                    
                    @if($category->alias)
                        <p class="text-sm text-gray-500 mt-1">
                            <i class="fas fa-link mr-1"></i>{{ $category->alias }}
                        </p>
                    @endif
                    
                    @if($category->des)
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $category->des }}</p>
                    @endif
                </div>
                
                <!-- Stats -->
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    @if($category->total)
                        <span title="Tổng số mục">
                            <i class="fas fa-box mr-1"></i>{{ $category->total }}
                        </span>
                    @endif
                    <span title="Thứ tự">
                        <i class="fas fa-sort-numeric-down mr-1"></i>{{ $category->weight ?? 0 }}
                    </span>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-2 ml-4">
                @if($category->parent_id == 0 || $level < 2)
                    <button onclick="openCreateModal({{ $category->id }})" 
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                            title="Thêm danh mục con">
                        <i class="fas fa-plus"></i>
                    </button>
                @endif
                <button onclick="openEditModal(
                    {{ $category->id }},
                    '{{ addslashes($category->name) }}',
                    '{{ addslashes($category->alias ?? '') }}',
                    '{{ $category->type ?? '' }}',
                    {{ $category->parent_id ?? 0 }},
                    '{{ addslashes($category->des ?? '') }}',
                    '{{ addslashes($category->kw ?? '') }}',
                    {{ $category->hidden ?? 1 }},
                    {{ $category->index_hidden ?? 1 }},
                    {{ $category->weight ?? 0 }}
                )" 
                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                        title="Sửa">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="openDeleteModal({{ $category->id }}, '{{ addslashes($category->name) }}')" 
                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                        title="Xóa">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        
        @if($hasChildren)
            <div class="category-children-wrapper" data-category-id="{{ $categoryId }}" data-parent-id="{{ $category->id }}">
                <div class="category-children" data-parent-id="{{ $category->id }}">
                    <div class="category-children-list space-y-2">
                        @include('admin.categories.partials.category-tree', ['categories' => $category->children, 'level' => $level + 1])
                    </div>
                </div>
            </div>
        @endif
    </div>
@endforeach

