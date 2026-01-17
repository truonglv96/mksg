@extends('admin.layouts.master')

@section('title', 'Quản lý danh mục nổi bật')

@php
$breadcrumbs = [
    ['label' => 'Danh mục nổi bật', 'url' => route('admin.featured-categories.index')]
];
@endphp

@push('styles')
<style>
    .featured-category-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .featured-category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    /* Modal Styles */
    #featuredCategoryModal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: none;
        align-items: center;
        justify-content: center;
    }
    
    #featuredCategoryModal.show {
        display: flex;
        opacity: 1;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        max-width: 700px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        transform: scale(0.95);
        transition: all 0.3s ease;
    }
    
    #featuredCategoryModal.show .modal-content {
        transform: scale(1);
    }
    
    .modal-header {
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);
        border-radius: 16px 16px 0 0;
    }
    
    .modal-header h2 {
        color: white;
        font-size: 24px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .modal-close:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
    .form-input.error {
        border-color: #ef4444;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }
    
    .image-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        background: #f9fafb;
        cursor: pointer;
    }
    
    .image-upload-area:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }
    
    .image-upload-area.dragover {
        border-color: #2563eb;
        background: #dbeafe;
    }
    
    .preview-image {
        width: 100%;
        max-height: 200px;
        object-fit: contain;
        background: #f9fafb;
        border-radius: 8px;
        padding: 16px;
        margin-top: 12px;
    }
    
    .image-preview-container {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .remove-image-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .remove-image-btn:hover {
        background: rgba(220, 38, 38, 1);
        transform: scale(1.1);
    }
    
    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: #f9fafb;
        border-radius: 0 0 16px 16px;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        background: linear-gradient(135deg, #dc2626 0%, #1d4ed8 100%);
    }
    
    .btn-secondary {
        background: white;
        color: #6b7280;
        border: 2px solid #e5e7eb;
    }
    
    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }
    
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
</style>
@endpush

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 fade-in">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Tổng danh mục</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalCategories ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-star-of-life text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Đang hoạt động</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($activeCategories ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-sm font-medium">Thứ tự sắp xếp</p>
                <p class="text-3xl font-bold mt-1">Theo Weight</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-sort-numeric-down text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-star-of-life mr-3" style="color: #2563eb;"></i>
                Quản lý danh mục nổi bật
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và theo dõi các danh mục nổi bật trên website</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="viewToggle" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-th view-icon" data-view="grid"></i>
                <i class="fas fa-list view-icon hidden" data-view="list"></i>
            </button>
            <button onclick="openFeaturedCategoryModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                <i class="fas fa-plus mr-2"></i>
                <span>Thêm danh mục</span>
            </button>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <form method="GET" action="{{ route('admin.featured-categories.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search -->
        <div class="md:col-span-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-gray-400"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       name="search"
                       value="{{ request('search', '') }}"
                       placeholder="Tìm theo tên hoặc link..." 
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        
        <!-- Sort -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-sort mr-2 text-gray-400"></i>Sắp xếp
            </label>
            <select name="sort" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="weight" {{ request('sort') == 'weight' ? 'selected' : '' }}>Thứ tự</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Trạng thái</option>
                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
            </select>
        </div>
        
        <!-- Actions -->
        <div class="md:col-span-2 flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-search mr-2"></i>Tìm
            </button>
            <a href="{{ route('admin.featured-categories.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Featured Categories Grid/Table -->
<div id="featuredCategoriesContainer">
    @if(isset($featuredCategories) && $featuredCategories->count() > 0)
        <!-- Grid View (Default) -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($featuredCategories as $category)
                <div class="featured-category-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @if($category->image)
                        <div class="w-full">
                            <img src="{{ asset('img/featured-category/' . $category->image) }}" 
                                 alt="{{ $category->name }}" 
                                 class="w-full h-48 object-cover"
                                 onerror="this.style.display='none';">
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 flex-1">{{ $category->name }}</h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $category->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} ml-2 flex-shrink-0">
                                {{ $category->status ? 'Hoạt động' : 'Tắt' }}
                            </span>
                        </div>
                        
                        @if($category->link)
                            <div class="mb-2">
                                <a href="{{ $category->link }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                    <i class="fas fa-link text-xs"></i>
                                    <span class="truncate">{{ $category->link }}</span>
                                </a>
                            </div>
                        @endif
                        
                        @if($category->color)
                            <div class="mb-3 flex items-center gap-2">
                                <span class="text-xs text-gray-500">Màu:</span>
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300" style="background-color: {{ $category->color }};"></div>
                                <span class="text-xs text-gray-600">{{ $category->color }}</span>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                #{{ $category->weight ?? 0 }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                            <button onclick="openFeaturedCategoryModal({{ $category->id }})" 
                                    class="flex-1 px-3 py-2 text-center text-sm bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                                <i class="fas fa-edit mr-1"></i>Sửa
                            </button>
                            <button onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')" 
                                    class="px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-red-200 hover:border-red-300">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Table View (Hidden by default) -->
        <div id="tableView" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hình ảnh
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thứ tự
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tên danh mục
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Link
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tạo
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($featuredCategories as $category)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        @if($category->image)
                                            <img src="{{ asset('img/featured-category/' . $category->image) }}" 
                                                 alt="{{ $category->name }}" 
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <i class="fas fa-image text-gray-400 text-xl" style="display: none;"></i>
                                        @else
                                            <i class="fas fa-image text-gray-400 text-xl"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-700">
                                        {{ $category->weight ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ $category->name }}</div>
                                        @if($category->color)
                                            <div class="w-4 h-4 rounded-full border border-gray-300" style="background-color: {{ $category->color }};"></div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($category->link)
                                        <a href="{{ $category->link }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 truncate block max-w-xs">
                                            {{ $category->link }}
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $category->status ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $category->status ? 'Hoạt động' : 'Tắt' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $category->created_at ? $category->created_at->format('d/m/Y') : '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openFeaturedCategoryModal({{ $category->id }})" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200" 
                                                title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}')" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if(isset($featuredCategories) && $featuredCategories->hasPages())
            <div class="mt-6">
                {{ $featuredCategories->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-star-of-life text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có danh mục nổi bật nào</h3>
            <p class="text-gray-500 mb-6">Hãy bắt đầu bằng cách tạo danh mục nổi bật đầu tiên của bạn</p>
            <button onclick="openFeaturedCategoryModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-plus mr-2"></i>
                Thêm danh mục
            </button>
        </div>
    @endif
</div>

<!-- Featured Category Modal -->
<div id="featuredCategoryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <i class="fas fa-star-of-life"></i>
                <span id="modalTitle">Thêm danh mục nổi bật mới</span>
            </h2>
            <button class="modal-close" onclick="closeFeaturedCategoryModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="featuredCategoryForm" onsubmit="saveFeaturedCategory(event)" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" id="featuredCategoryId" name="id">
                
                <div class="form-group">
                    <label class="form-label" for="categoryName">
                        <i class="fas fa-tag mr-2 text-gray-400"></i>Tên danh mục <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="categoryName" 
                           name="name" 
                           class="form-input" 
                           placeholder="Nhập tên danh mục..."
                           required>
                    <div id="nameError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="categoryLink">
                        <i class="fas fa-link mr-2 text-gray-400"></i>Link URL
                    </label>
                    <input type="text" 
                           id="categoryLink" 
                           name="link" 
                           class="form-input" 
                           placeholder="https://example.com">
                    <div id="linkError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="categoryColor">
                        <i class="fas fa-palette mr-2 text-gray-400"></i>Màu sắc
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" 
                               id="categoryColor" 
                               name="color" 
                               class="w-16 h-10 border-2 border-gray-300 rounded-lg cursor-pointer"
                               value="#3b82f6">
                        <input type="text" 
                               id="categoryColorText" 
                               class="form-input flex-1" 
                               placeholder="#3b82f6"
                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$">
                    </div>
                    <div id="colorError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="categoryWeight">
                        <i class="fas fa-sort-numeric-down mr-2 text-gray-400"></i>Thứ tự sắp xếp
                    </label>
                    <input type="number" 
                           id="categoryWeight" 
                           name="weight" 
                           class="form-input" 
                           placeholder="0"
                           min="0"
                           value="0">
                    <p class="text-xs text-gray-500 mt-2">Số càng nhỏ, hiển thị càng trước</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="categoryStatus">
                        <i class="fas fa-toggle-on mr-2 text-gray-400"></i>Trạng thái
                    </label>
                    <label class="switch">
                        <input type="checkbox" id="categoryStatus" name="status" value="1" checked>
                        <span class="slider"></span>
                    </label>
                    <span class="ml-3 text-sm text-gray-600" id="statusText">Hoạt động</span>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="categoryImage">
                        <i class="fas fa-image mr-2 text-gray-400"></i>Hình ảnh
                    </label>
                    <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('categoryImageInput').click()">
                        <input type="file" 
                               id="categoryImageInput" 
                               name="image" 
                               accept="image/*" 
                               class="hidden"
                               onchange="handleImageUpload(event)">
                        <div id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-600 font-medium">Nhấp để tải ảnh lên</p>
                            <p class="text-xs text-gray-500 mt-1">hoặc kéo thả ảnh vào đây</p>
                        </div>
                        <div id="imagePreview" class="hidden">
                            <div class="image-preview-container">
                                <img id="previewImage" src="" alt="Preview" class="preview-image">
                                <button type="button" class="remove-image-btn" onclick="removeImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <input type="hidden" id="deleteImageFlag" name="delete_image" value="0">
                        </div>
                    </div>
                    <div id="imageError" class="error-message hidden"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeFeaturedCategoryModal()" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Hủy
                </button>
                <button type="submit" id="submitBtn" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    <span id="submitText">Lưu</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
@include('admin.helpers.delete-modal', [
    'id' => 'deleteFeaturedCategoryModal',
    'title' => 'Xác nhận xóa danh mục nổi bật',
    'message' => 'Bạn có chắc chắn muốn xóa danh mục nổi bật "{name}"?',
    'confirmText' => 'Xóa danh mục'
])
@endsection

@push('scripts')
<script>
// View Toggle
document.addEventListener('DOMContentLoaded', function() {
    const viewToggle = document.getElementById('viewToggle');
    const gridView = document.getElementById('gridView');
    const tableView = document.getElementById('tableView');
    const viewIcons = document.querySelectorAll('.view-icon');
    
    if (viewToggle && gridView && tableView) {
        viewToggle.addEventListener('click', function() {
            const isGridVisible = !gridView.classList.contains('hidden');
            
            if (isGridVisible) {
                gridView.classList.add('hidden');
                tableView.classList.remove('hidden');
            } else {
                tableView.classList.add('hidden');
                gridView.classList.remove('hidden');
            }
            
            viewIcons.forEach(icon => {
                icon.classList.toggle('hidden');
            });
        });
    }
    
    // Color picker sync
    const colorPicker = document.getElementById('categoryColor');
    const colorText = document.getElementById('categoryColorText');
    const statusToggle = document.getElementById('categoryStatus');
    const statusText = document.getElementById('statusText');
    
    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', function() {
            colorText.value = this.value;
        });
        
        colorText.addEventListener('input', function() {
            if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(this.value)) {
                colorPicker.value = this.value;
            }
        });
    }
    
    if (statusToggle && statusText) {
        statusToggle.addEventListener('change', function() {
            statusText.textContent = this.checked ? 'Hoạt động' : 'Tắt';
        });
    }
});

// Featured Category Modal Functions
function openFeaturedCategoryModal(categoryId = null) {
    const modal = document.getElementById('featuredCategoryModal');
    const form = document.getElementById('featuredCategoryForm');
    const title = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitText');
    
    if (!modal || !form || !title || !submitText) {
        console.error('Modal elements not found');
        return;
    }
    
    // Reset form
    form.reset();
    const categoryIdInput = document.getElementById('featuredCategoryId');
    if (categoryIdInput) {
        categoryIdInput.value = '';
    }
    clearErrors();
    resetImagePreview();
    
    // Reset color picker
    const colorPicker = document.getElementById('categoryColor');
    const colorText = document.getElementById('categoryColorText');
    if (colorPicker) colorPicker.value = '#3b82f6';
    if (colorText) colorText.value = '#3b82f6';
    
    // Reset status
    const statusToggle = document.getElementById('categoryStatus');
    const statusText = document.getElementById('statusText');
    if (statusToggle) statusToggle.checked = true;
    if (statusText) statusText.textContent = 'Hoạt động';
    
    if (categoryId) {
        // Edit mode
        title.textContent = 'Sửa danh mục nổi bật';
        submitText.textContent = 'Cập nhật';
        
        // Fetch category data
        fetch(`/admin/featured-categories/${categoryId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const idInput = document.getElementById('featuredCategoryId');
                const nameInput = document.getElementById('categoryName');
                const linkInput = document.getElementById('categoryLink');
                const colorPicker = document.getElementById('categoryColor');
                const colorText = document.getElementById('categoryColorText');
                const weightInput = document.getElementById('categoryWeight');
                const statusToggle = document.getElementById('categoryStatus');
                const statusText = document.getElementById('statusText');
                
                if (idInput) idInput.value = data.featuredCategory.id;
                if (nameInput) nameInput.value = data.featuredCategory.name || '';
                if (linkInput) linkInput.value = data.featuredCategory.link || '';
                if (colorPicker) colorPicker.value = data.featuredCategory.color || '#3b82f6';
                if (colorText) colorText.value = data.featuredCategory.color || '#3b82f6';
                if (weightInput) weightInput.value = data.featuredCategory.weight || 0;
                if (statusToggle) statusToggle.checked = data.featuredCategory.status == 1;
                if (statusText) statusText.textContent = data.featuredCategory.status == 1 ? 'Hoạt động' : 'Tắt';
                
                // Show existing image if available
                if (data.featuredCategory.image) {
                    showImagePreview(`{{ asset('img/featured-category') }}/${data.featuredCategory.image}`);
                }
            } else {
                if (typeof showError === 'function') {
                    showError(data.message || 'Không thể tải dữ liệu danh mục nổi bật');
                } else if (typeof showNotification === 'function') {
                    showNotification(data.message || 'Không thể tải dữ liệu danh mục nổi bật', 'error');
                } else {
                    alert(data.message || 'Không thể tải dữ liệu danh mục nổi bật');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const errorMsg = 'Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.';
            if (typeof showError === 'function') {
                showError(errorMsg);
            } else if (typeof showNotification === 'function') {
                showNotification(errorMsg, 'error');
            } else {
                alert(errorMsg);
            }
        });
    } else {
        // Create mode
        title.textContent = 'Thêm danh mục nổi bật mới';
        submitText.textContent = 'Lưu';
    }
    
    // Show modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeFeaturedCategoryModal() {
    const modal = document.getElementById('featuredCategoryModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

function clearErrors() {
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.querySelectorAll('.form-input').forEach(el => {
        el.classList.remove('error');
    });
}

function showError(field, message) {
    const input = document.getElementById(field);
    const errorDiv = document.getElementById(field + 'Error');
    
    if (input) {
        input.classList.add('error');
    }
    
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
}

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2048 * 1024) {
            showError('categoryImage', 'Kích thước ảnh không được vượt quá 2MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            showImagePreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function showImagePreview(imageSrc) {
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const deleteFlag = document.getElementById('deleteImageFlag');
    
    if (placeholder) placeholder.classList.add('hidden');
    if (preview) {
        preview.classList.remove('hidden');
        if (previewImage) previewImage.src = imageSrc;
    }
    if (deleteFlag) deleteFlag.value = '0';
}

function resetImagePreview() {
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const imageInput = document.getElementById('categoryImageInput');
    const deleteFlag = document.getElementById('deleteImageFlag');
    
    if (placeholder) placeholder.classList.remove('hidden');
    if (preview) preview.classList.add('hidden');
    if (previewImage) previewImage.src = '';
    if (imageInput) imageInput.value = '';
    if (deleteFlag) deleteFlag.value = '0';
}

function removeImage() {
    const deleteFlag = document.getElementById('deleteImageFlag');
    if (deleteFlag) deleteFlag.value = '1';
    resetImagePreview();
}

function saveFeaturedCategory(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    const formData = new FormData(form);
    const categoryId = formData.get('id');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    if (!submitBtn || !submitText) {
        console.error('Submit button elements not found');
        return;
    }
    
    const originalText = submitText.textContent;
    
    // Clear previous errors
    clearErrors();
    
    // Handle status checkbox
    const statusToggle = document.getElementById('categoryStatus');
    if (statusToggle) {
        formData.set('status', statusToggle.checked ? '1' : '0');
    }
    
    // Sync color picker and text
    const colorText = document.getElementById('categoryColorText');
    const colorPicker = document.getElementById('categoryColor');
    if (colorText && colorText.value) {
        formData.set('color', colorText.value);
    } else if (colorPicker) {
        formData.set('color', colorPicker.value);
    }
    
    // Disable submit button
    submitBtn.disabled = true;
    submitText.innerHTML = '<span class="loading-spinner"></span> Đang lưu...';
    
    const url = categoryId 
        ? `/admin/featured-categories/${categoryId}`
        : '/admin/featured-categories';
    
    const method = categoryId ? 'PUT' : 'POST';
    
    // CSRF token will be handled by apiFetch helper
    
    // Use API helper (method spoofing handled inside apiFetch)
    const fetchPromise = apiFetch(url, {
        method,
        body: formData
    });
    
    handleApiResponse(fetchPromise, {
        onSuccess: (data) => {
            closeFeaturedCategoryModal();
        },
        reloadOnSuccess: true,
        reloadDelay: 500,
        errorFieldMapper: (field) => {
            // Map field names to input IDs and error div IDs
            const mapping = {
                'name': { inputId: 'categoryName', errorDivId: 'nameError' },
                'link': { inputId: 'categoryLink', errorDivId: 'linkError' },
                'color': { inputId: 'categoryColorText', errorDivId: 'colorError' },
                'weight': { inputId: 'categoryWeight', errorDivId: 'weightError' },
                'image': { inputId: 'categoryImageInput', errorDivId: 'imageError' }
            };
            return mapping[field] || {
                inputId: 'category' + field.charAt(0).toUpperCase() + field.slice(1),
                errorDivId: field + 'Error'
            };
        },
        onError: () => {
            submitBtn.disabled = false;
            submitText.textContent = originalText;
        }
    });
}

// Delete function
function confirmDelete(id, name) {
    showConfirmModal({
        title: 'Xác nhận xóa',
        message: `Bạn có chắc chắn muốn xóa danh mục nổi bật "${name}"? Hành động này không thể hoàn tác.`,
        type: 'danger',
        confirmText: 'Xóa',
        cancelText: 'Hủy',
        onConfirm: () => {
            performDeleteFeaturedCategory(id);
        }
    });
}

function performDeleteFeaturedCategory(id) {
    handleApiResponse(
        apiFetch(`/admin/featured-categories/${id}`, { method: 'DELETE' }),
        {
            reloadOnSuccess: true,
            reloadDelay: 500
        }
    );
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('featuredCategoryModal');
    if (event.target === modal) {
        closeFeaturedCategoryModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('featuredCategoryModal');
        if (modal && modal.classList.contains('show')) {
            closeFeaturedCategoryModal();
        }
    }
});

// Drag and drop for image upload
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('categoryImageInput');
    
    if (uploadArea && imageInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.add('dragover');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.remove('dragover');
            }, false);
        });
        
        uploadArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                imageInput.files = files;
                handleImageUpload({ target: imageInput });
            }
        }, false);
    }
});
</script>
@endpush

