@extends('admin.layouts.master')

@section('title', 'Quản lý sản phẩm')

@php
$breadcrumbs = [
    ['label' => 'Sản phẩm', 'url' => route('admin.products.index')]
];
@endphp


@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 fade-in">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Tổng sản phẩm</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalProducts) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-box text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Đang bán</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($activeProducts) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Hết hàng</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($outOfStockProducts) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
    <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Đã ẩn</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($hiddenProducts) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-eye-slash text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Page Header with Actions -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-box text-primary-600 mr-3"></i>
                Quản lý sản phẩm
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và theo dõi tất cả sản phẩm của bạn</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="viewToggle" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-th view-icon" data-view="grid"></i>
                <i class="fas fa-list view-icon hidden" data-view="list"></i>
            </button>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5" style="background: linear-gradient(to right, #0284c7, #0369a1); display: inline-flex; align-items: center; padding: 0.625rem 1.25rem; color: white; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); transition: all 0.2s;">
            <i class="fas fa-plus mr-2"></i>
                <span class="font-medium">Thêm sản phẩm</span>
        </a>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search -->
        <div class="md:col-span-5">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-gray-400"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       id="searchInput"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Tìm theo tên, mã sản phẩm, mô tả..." 
                       class="w-full pl-11 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth search-input">
                <i class="fas fa-search absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        
        <!-- Category Filter -->
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-tags mr-2 text-gray-400"></i>Danh mục
            </label>
            <div class="relative category-dropdown-wrapper">
                <select id="categoryFilter" name="category" class="category-select w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white appearance-none cursor-pointer">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat['id'] }}" 
                                data-level="{{ $cat['level'] }}"
                                {{ $category == $cat['id'] ? 'selected' : '' }}>
                            {{ $cat['formatted_name'] }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i class="fas fa-chevron-down text-gray-400"></i>
                </div>
            </div>
        </div>
        
        <!-- Brand Filter -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-tag mr-2 text-gray-400"></i>Thương hiệu
            </label>
            <select id="brandFilter" name="brand" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white">
                <option value="">Tất cả thương hiệu</option>
                @foreach($brands as $brandItem)
                    <option value="{{ $brandItem->id }}" {{ $brand == $brandItem->id ? 'selected' : '' }}>{{ $brandItem->name }}</option>
                @endforeach
            </select>
        </div>
        
        <!-- Sort -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-sort mr-2 text-gray-400"></i>Sắp xếp
            </label>
            <select id="sortFilter" name="sort" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white">
                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
            </select>
        </div>
    </div>
    
    <!-- Active Filters -->
    <div id="activeFilters" class="mt-4 flex flex-wrap gap-2 hidden">
        <span class="text-sm text-gray-600 font-medium">Bộ lọc:</span>
    </div>
</div>

<!-- Products Grid View -->
<div id="gridView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
    <!-- Add New Product Card - First Position -->
    <div class="product-card bg-white rounded-xl shadow-sm border-2 border-dashed border-gray-300 overflow-hidden fade-in flex items-center justify-center min-h-[400px] hover:border-primary-400 transition-smooth cursor-pointer">
        <a href="{{ route('admin.products.create') }}" class="text-center p-6">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-plus text-primary-600 text-2xl"></i>
            </div>
            <p class="text-lg font-semibold text-gray-700">Thêm sản phẩm mới</p>
            <p class="text-sm text-gray-500 mt-2">Tạo sản phẩm mới cho cửa hàng</p>
        </a>
    </div>
    
    @forelse($products as $index => $product)
        @php
            $priceSale = $product->price_sale ?? $product->price ?? 0;
            $priceOriginal = $product->price ?? 0;
            $isActive = $product->hidden == 1;
        @endphp
        <div class="product-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">
            <div class="relative bg-gray-100 overflow-hidden">
                @include('admin.helpers.product-image', ['product' => $product, 'size' => 'medium'])
                <div class="absolute top-3 right-3">
                    @include('admin.helpers.product-status-badge', ['isActive' => $isActive, 'size' => 'md'])
                </div>
                <div class="absolute top-3 left-3">
                    <button class="w-9 h-9 bg-white/90 hover:bg-white rounded-full flex items-center justify-center transition-smooth shadow-md hover:shadow-lg">
                        <i class="fas fa-heart text-gray-400 hover:text-red-500"></i>
                    </button>
                </div>
            </div>
            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-1">{{ $product->name }}</h3>
                <p class="text-sm text-gray-500 mb-3">
                    @include('admin.helpers.product-sku', ['product' => $product])
                </p>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        @include('admin.helpers.product-price', [
                            'priceSale' => $priceSale,
                            'priceOriginal' => $priceOriginal,
                            'size' => 'lg'
                        ])
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-700">Mã sản phẩm</p>
                        <p class="text-lg font-bold text-gray-900">
                            @if($product->code_sp)
                                {{ $product->code_sp }}
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-smooth text-center text-sm font-medium">
                        <i class="fas fa-edit mr-2"></i>Sửa
                    </a>
                    <a href="{{ route('admin.products.show', $product->id) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-smooth">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-smooth">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <div class="text-gray-400">
                <i class="fas fa-box-open text-4xl mb-4"></i>
                <p class="text-lg font-medium">Không có sản phẩm nào</p>
                <p class="text-sm mt-2">Hãy thêm sản phẩm mới để bắt đầu</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Products Table View -->
<div id="listView" class="table-container">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">
        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay">
            <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
                <p class="mt-4 text-gray-600">Đang tải...</p>
            </div>
        </div>
        
        <!-- Table Header Actions -->
        <div class="px-3 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <input type="checkbox" id="selectAll" class="table-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                <label for="selectAll" class="text-sm font-medium text-gray-700 cursor-pointer">Chọn tất cả</label>
                <span id="selectedCount" class="text-sm text-gray-500 hidden">Đã chọn: <span class="font-semibold">0</span></span>
            </div>
            <div class="flex items-center gap-2">
                <button id="bulkActions" class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <i class="fas fa-tasks mr-2"></i>Thao tác
                </button>
            </div>
        </div>
        
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
                <thead class="table-header">
                    <tr>
                        <th class="px-3 py-4 text-left"></th>
                        <th class="px-3 py-4 text-left">
                            <div class="flex items-center gap-2">
                                <span>Sản phẩm</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-4 text-left">
                            <div class="flex items-center gap-2">
                                <span>Danh mục</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-4 text-left">
                            <div class="flex items-center gap-2">
                                <span>Giá</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-4 text-left">
                            <div class="flex items-center gap-2">
                                <span>Mã sản phẩm</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-4 text-left">
                            <div class="flex items-center gap-2">
                                <span>Trạng thái</span>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-sort text-xs"></i>
                                </button>
                            </div>
                        </th>
                        <th class="px-3 py-4 text-right" style="min-width: 120px;">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $index => $product)
                        @php
                            $priceSale = $product->price_sale ?? $product->price ?? 0;
                            $priceOriginal = $product->price ?? 0;
                            $isActive = $product->hidden == 1;
                            $viewCount = $product->view_count ?? 0;
                        @endphp
                        <tr class="table-row" style="animation-delay: {{ $index * 0.05 }}s">
            <td class="px-3 py-4 whitespace-nowrap">
                                <input type="checkbox" class="row-checkbox table-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500" data-id="{{ $product->id }}">
                            </td>
                            <td class="px-3 py-4">
                                <div class="flex items-center">
                                    <div class="relative group w-14 h-14 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100 border-2 border-gray-200 mr-4">
                                        @include('admin.helpers.product-image', ['product' => $product, 'size' => 'small', 'class' => 'product-image'])
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 rounded-lg transition-smooth flex items-center justify-center pointer-events-none">
                                            <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-smooth text-sm"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 hover:text-primary-600 transition-smooth cursor-pointer">{{ $product->name }}</div>
                                        <!-- <div class="text-xs text-gray-500 mt-0.5">
                                            @include('admin.helpers.product-sku', ['product' => $product])
                                        </div> -->
                                        @if($viewCount > 0)
                                            <div class="text-xs text-gray-400 mt-1">
                                                <i class="fas fa-eye mr-1"></i>{{ number_format($viewCount) }} lượt xem
                                            </div>
                                        @endif
                                    </div>
                                </div>
            </td>
            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @forelse($product->categories ?? [] as $cat)
                                        <span class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition-smooth cursor-pointer">{{ $cat->name }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">Chưa phân loại</span>
                                    @endforelse
                                </div>
            </td>
            <td class="px-3 py-4 whitespace-nowrap">
                                @include('admin.helpers.product-price', [
                                    'priceSale' => $priceSale,
                                    'priceOriginal' => $priceOriginal,
                                    'size' => 'md'
                                ])
            </td>
            <td class="px-3 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($product->code_sp)
                                        <span class="text-sm font-semibold text-gray-900">{{ $product->code_sp }}</span>
                                    @else
                                        <span class="text-sm font-medium text-gray-400">—</span>
                                    @endif
                                </div>
            </td>
            <td class="px-3 py-4 whitespace-nowrap">
                                <div style="visibility: visible !important; opacity: 1 !important; display: inline-block !important;">
                                    @include('admin.helpers.product-status-badge', ['isActive' => $isActive, 'size' => 'md'])
                                </div>
            </td>
                            <td class="px-3 py-4 whitespace-nowrap text-right" style="min-width: 120px;">
                                <div class="flex items-center justify-end gap-2" style="visibility: visible !important; opacity: 1 !important; display: flex !important;">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="inline-flex items-center justify-center p-2.5 text-primary-600 hover:bg-primary-50 rounded-lg transition-smooth border border-transparent hover:border-primary-200" 
                                       title="Sửa"
                                       style="visibility: visible !important; opacity: 1 !important; display: inline-flex !important;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.products.show', $product->id) }}" 
                                       class="inline-flex items-center justify-center p-2.5 text-green-600 hover:bg-green-50 rounded-lg transition-smooth border border-transparent hover:border-green-200" 
                                       title="Xem"
                                       style="visibility: visible !important; opacity: 1 !important; display: inline-flex !important;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" 
                                            onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')" 
                                            class="inline-flex items-center justify-center p-2.5 text-red-600 hover:bg-red-50 rounded-lg transition-smooth border border-transparent hover:border-red-200" 
                                            title="Xóa"
                                            style="visibility: visible !important; opacity: 1 !important; display: inline-flex !important;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-12 text-center">
                                <div class="text-gray-400">
                                    <i class="fas fa-box-open text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">Không có sản phẩm nào</p>
                                    <p class="text-sm mt-2">Hãy thêm sản phẩm mới để bắt đầu</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
            </tbody>
        </table>
        </div>
    </div>
    </div>
    
    <!-- Pagination -->
    @include('admin.helpers.pagination', ['paginator' => $products])

<div data-products-route="{{ route('admin.products.index') }}"></div>

<!-- Delete Confirmation Modal -->
@include('admin.helpers.delete-modal', [
    'id' => 'deleteModal',
    'title' => 'Xác nhận xóa sản phẩm',
    'message' => 'Bạn có chắc chắn muốn xóa sản phẩm "{name}"? Hành động này không thể hoàn tác.',
    'confirmText' => 'Xóa sản phẩm',
    'cancelText' => 'Hủy'
])

<script>
// Define openDeleteModal function immediately (before confirmDelete)
window.openDeleteModal = function(modalId, deleteUrl, itemName = null) {
    const modal = document.getElementById(modalId);
    const form = document.getElementById('deleteForm_' + modalId);
    const messageElement = document.getElementById('deleteModalMessage_' + modalId);
    
    if (!modal) {
        console.error('Modal not found with ID:', modalId);
        return;
    }
    
    if (!form) {
        console.error('Form not found with ID:', 'deleteForm_' + modalId);
        return;
    }
    
    // Set the form action
    form.action = deleteUrl;
    
    // Update message if item name is provided
    if (itemName && messageElement) {
        const originalMessage = messageElement.getAttribute('data-original-message') || messageElement.textContent.trim();
        if (!messageElement.getAttribute('data-original-message')) {
            messageElement.setAttribute('data-original-message', originalMessage);
        }
        messageElement.textContent = originalMessage.replace(/{name}/g, itemName);
    }
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Add animation
    setTimeout(() => {
        const overlay = modal.querySelector('.modal-overlay');
        const content = modal.querySelector('.modal-content');
        if (overlay) overlay.style.opacity = '1';
        if (content) {
            content.style.opacity = '1';
            content.style.transform = 'scale(1)';
        }
    }, 10);
};

// Define closeDeleteModal function
window.closeDeleteModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    // Hide modal with animation
    const overlay = modal.querySelector('.modal-overlay');
    const content = modal.querySelector('.modal-content');
    if (overlay) overlay.style.opacity = '0';
    if (content) {
        content.style.opacity = '0';
        content.style.transform = 'scale(0.95)';
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 200);
};

// Define confirmDelete function for products page
window.confirmDelete = function(productId, productName = null) {
    const deleteUrl = `/admin/products/${productId}`;
    window.openDeleteModal('deleteModal', deleteUrl, productName);
};

// Close modal when clicking outside or pressing Escape
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('modal-overlay')) {
                window.closeDeleteModal('deleteModal');
            }
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('deleteModal');
            if (modal && !modal.classList.contains('hidden')) {
                window.closeDeleteModal('deleteModal');
            }
        }
    });
    
    // Ensure action buttons and status badges are always visible
    function ensureElementsVisible() {
        // Action buttons
        const actionButtons = document.querySelectorAll('.action-btn, [title="Xóa"], [title="Sửa"], [title="Xem"]');
        actionButtons.forEach(btn => {
            if (btn) {
                btn.style.visibility = 'visible';
                btn.style.opacity = '1';
                btn.style.display = btn.tagName === 'BUTTON' ? 'inline-flex' : 'inline-flex';
            }
        });
        
        // Status badges
        const statusBadges = document.querySelectorAll('.status-badge');
        statusBadges.forEach(badge => {
            if (badge) {
                badge.style.visibility = 'visible';
                badge.style.opacity = '1';
                badge.style.display = 'inline-block';
            }
        });
        
        // Action buttons container
        const actionContainers = document.querySelectorAll('td.text-right div.flex.items-center.justify-end');
        actionContainers.forEach(container => {
            if (container) {
                container.style.visibility = 'visible';
                container.style.opacity = '1';
                container.style.display = 'flex';
            }
        });
    }
    
    // Run immediately
    ensureElementsVisible();
    
    // Run after a short delay to override any other scripts
    setTimeout(ensureElementsVisible, 100);
    setTimeout(ensureElementsVisible, 500);
    setTimeout(ensureElementsVisible, 1000);
    setTimeout(ensureElementsVisible, 3000);
    
    // Also run on any DOM mutations
    const observer = new MutationObserver(function(mutations) {
        ensureElementsVisible();
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['style', 'class']
    });
});
</script>


@endsection
