@extends('admin.layouts.master')

@section('title', 'Quản lý tính năng sản phẩm')

@php
$breadcrumbs = [
    ['label' => 'Tính năng sản phẩm', 'url' => route('admin.features-product.index')]
];
@endphp

@push('styles')
<style>
    .features-product-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .features-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    .features-product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f9fafb;
    }
    
    /* Modal Styles */
    #featuresProductModal {
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
    
    #featuresProductModal.show {
        display: flex;
        opacity: 1;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        transform: scale(0.95);
        transition: all 0.3s ease;
    }
    
    #featuresProductModal.show .modal-content {
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
    
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
        resize: vertical;
        min-height: 100px;
    }
    
    .form-textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
        max-height: 300px;
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
    
    .switch-slider {
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
    
    .switch-slider:before {
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
    
    input:checked + .switch-slider {
        background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);
    }
    
    input:checked + .switch-slider:before {
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
                <p class="text-blue-100 text-sm font-medium">Tổng tính năng</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalFeatures ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-star text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Đang sử dụng</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalFeatures ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-sm font-medium">Sắp xếp</p>
                <p class="text-3xl font-bold mt-1">Mới nhất</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-sort-amount-down text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-star mr-3" style="color: #2563eb;"></i>
                Quản lý tính năng sản phẩm
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và theo dõi tất cả tính năng sản phẩm</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="viewToggle" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-th view-icon" data-view="grid"></i>
                <i class="fas fa-list view-icon hidden" data-view="list"></i>
            </button>
            <button onclick="openFeaturesProductModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                <i class="fas fa-plus mr-2"></i>
                <span>Thêm tính năng</span>
            </button>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <form method="GET" action="{{ route('admin.features-product.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search -->
        <div class="md:col-span-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-gray-400"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       name="search"
                       value="{{ request('search', '') }}"
                       placeholder="Tìm theo tên tính năng..." 
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
                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
            </select>
        </div>
        
        <!-- Actions -->
        <div class="md:col-span-2 flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-search mr-2"></i>Tìm
            </button>
            <a href="{{ route('admin.features-product.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Features Products Grid/Table -->
<div id="featuresProductsContainer">
    @if(isset($featuresProducts) && $featuresProducts->count() > 0)
        <!-- Grid View (Default) -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($featuresProducts as $featuresProduct)
                <div class="features-product-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @if($featuresProduct->image)
                        <div class="w-full">
                            <img src="{{ asset('img/features-product/' . $featuresProduct->image) }}" 
                                 alt="{{ $featuresProduct->name }}" 
                                 class="w-full h-48 object-cover"
                                 onerror="this.style.display='none';">
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 flex-1">{{ $featuresProduct->name }}</h3>
                        </div>
                        
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                            <button onclick="openFeaturesProductModal({{ $featuresProduct->id }})" 
                                    class="flex-1 px-3 py-2 text-center text-sm bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                                <i class="fas fa-edit mr-1"></i>Sửa
                            </button>
                            <button onclick="confirmDelete({{ $featuresProduct->id }}, '{{ addslashes($featuresProduct->name) }}')" 
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
                                Tên tính năng
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
                        @foreach($featuresProducts as $featuresProduct)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        @if($featuresProduct->image)
                                            <img src="{{ asset('img/features-product/' . $featuresProduct->image) }}" 
                                                 alt="{{ $featuresProduct->name }}" 
                                                 class="w-full h-full object-contain"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <i class="fas fa-star text-gray-400 text-xl" style="display: none;"></i>
                                        @else
                                            <i class="fas fa-star text-gray-400 text-xl"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ $featuresProduct->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $featuresProduct->created_at ? $featuresProduct->created_at->format('d/m/Y') : '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openFeaturesProductModal({{ $featuresProduct->id }})" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200" 
                                                title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $featuresProduct->id }}, '{{ addslashes($featuresProduct->name) }}')" 
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
        @if(isset($featuresProducts) && $featuresProducts->hasPages())
            <div class="mt-6">
                {{ $featuresProducts->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có tính năng nào</h3>
            <p class="text-gray-500 mb-6">Hãy bắt đầu bằng cách tạo tính năng đầu tiên của bạn</p>
            <button onclick="openFeaturesProductModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-plus mr-2"></i>
                Thêm tính năng
            </button>
        </div>
    @endif
</div>

<!-- Features Product Modal -->
<div id="featuresProductModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <i class="fas fa-star"></i>
                <span id="modalTitle">Thêm tính năng mới</span>
            </h2>
            <button class="modal-close" onclick="closeFeaturesProductModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="featuresProductForm" onsubmit="saveFeaturesProduct(event)" enctype="multipart/form-data" novalidate>
            <div class="modal-body">
                <input type="hidden" id="featuresProductId" name="id">
                
                <div class="form-group">
                    <label class="form-label" for="featuresProductName">
                        <i class="fas fa-tag mr-2 text-gray-400"></i>Tên tính năng <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="featuresProductName" 
                           name="name" 
                           class="form-input" 
                           placeholder="Nhập tên tính năng..."
                           required
                           maxlength="100">
                    <div id="nameError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="featuresProductImage">
                        <i class="fas fa-image mr-2 text-gray-400"></i>Hình ảnh <span class="text-red-500">*</span>
                    </label>
                    <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('featuresProductImageInput').click()">
                        <input type="file" 
                               id="featuresProductImageInput" 
                               name="image" 
                               accept="image/*,.svg" 
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
                <button type="button" onclick="closeFeaturesProductModal()" class="btn btn-secondary">
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
});

// Features Product Modal Functions
function openFeaturesProductModal(featuresProductId = null) {
    const modal = document.getElementById('featuresProductModal');
    const form = document.getElementById('featuresProductForm');
    const title = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitText');
    
    if (!modal || !form || !title || !submitText) {
        console.error('Modal elements not found');
        return;
    }
    
    // Reset form
    form.reset();
    const featuresProductIdInput = document.getElementById('featuresProductId');
    if (featuresProductIdInput) {
        featuresProductIdInput.value = '';
    }
    clearErrors();
    resetImagePreview();
    
    if (featuresProductId) {
        // Edit mode
        title.textContent = 'Sửa tính năng';
        submitText.textContent = 'Cập nhật';
        
        // Fetch features product data
        fetch(`/admin/features-product/${featuresProductId}`, {
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
                const idInput = document.getElementById('featuresProductId');
                const nameInput = document.getElementById('featuresProductName');
                
                if (idInput) idInput.value = data.data.id;
                if (nameInput) nameInput.value = data.data.name || '';
                
                // Show existing image if available
                if (data.data.image_url) {
                    showImagePreview(data.data.image_url);
                }
            } else {
                if (typeof showError === 'function') {
                    showError(data.message || 'Không thể tải dữ liệu tính năng');
                } else {
                    alert(data.message || 'Không thể tải dữ liệu tính năng');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showError === 'function') {
                showError('Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.');
            } else {
                alert('Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.');
            }
        });
    } else {
        // Create mode
        title.textContent = 'Thêm tính năng mới';
        submitText.textContent = 'Lưu';
    }
    
    // Show modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeFeaturesProductModal() {
    const modal = document.getElementById('featuresProductModal');
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
    document.querySelectorAll('.form-input, .form-textarea').forEach(el => {
        el.classList.remove('error');
        el.removeAttribute('data-scrolled');
    });
    
    // Clear image upload area error styling
    const imageUploadArea = document.getElementById('imageUploadArea');
    if (imageUploadArea) {
        imageUploadArea.style.borderColor = '';
        imageUploadArea.style.borderWidth = '';
    }
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
    } else {
        console.warn('Error div not found for field:', field);
    }
}

function showErrorForField(inputId, errorDivId, message) {
    const input = document.getElementById(inputId);
    const errorDiv = document.getElementById(errorDivId);
    
    if (input) {
        input.classList.add('error');
        
        // Special handling for image upload area
        if (inputId === 'featuresProductImageInput') {
            const imageUploadArea = document.getElementById('imageUploadArea');
            if (imageUploadArea) {
                imageUploadArea.style.borderColor = '#ef4444';
                imageUploadArea.style.borderWidth = '2px';
            }
        }
        
        // Scroll to the first error field
        const firstError = document.querySelector('.form-input.error, .image-upload-area[style*="border-color: rgb(239, 68, 68)"]');
        if (firstError && !document.querySelector('.form-input.error[data-scrolled]')) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (input.classList.contains('form-input')) {
                input.setAttribute('data-scrolled', 'true');
            }
        }
    }
    
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    } else {
        console.warn('Error div not found:', errorDivId, 'for input:', inputId);
    }
}

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml'];
        const allowedExtensions = ['.jpeg', '.jpg', '.png', '.gif', '.svg'];
        const fileName = file.name.toLowerCase();
        const fileExtension = fileName.substring(fileName.lastIndexOf('.'));
        
        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
            showError('featuresProductImage', 'Chỉ chấp nhận file ảnh định dạng: JPEG, JPG, PNG, GIF, SVG');
            event.target.value = ''; // Clear invalid file
            return;
        }
        
        // Validate file size
        if (file.size > 2048 * 1024) {
            showError('featuresProductImage', 'Kích thước ảnh không được vượt quá 2MB');
            event.target.value = ''; // Clear file that's too large
            return;
        }
        
        // Clear any previous errors
        const imageError = document.getElementById('imageError');
        if (imageError) {
            imageError.classList.add('hidden');
            imageError.textContent = '';
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            showImagePreview(e.target.result);
        };
        reader.onerror = function() {
            showError('featuresProductImage', 'Không thể đọc file ảnh. Vui lòng chọn file khác.');
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
    const imageInput = document.getElementById('featuresProductImageInput');
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

function saveFeaturesProduct(event) {
    event.preventDefault();
    event.stopPropagation();
    
    console.log('saveFeaturesProduct called');
    
    const form = event.target;
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    if (!submitBtn || !submitText) {
        console.error('Submit button elements not found');
        return;
    }
    
    // Validate form manually
    const nameInput = document.getElementById('featuresProductName');
    const imageInput = document.getElementById('featuresProductImageInput');
    const featuresProductIdInput = document.getElementById('featuresProductId');
    const featuresProductId = featuresProductIdInput ? featuresProductIdInput.value : null;
    
    // Clear previous errors
    clearErrors();
    
    let hasError = false;
    
    // Validate name
    if (!nameInput || !nameInput.value.trim()) {
        showError('featuresProductName', 'Vui lòng nhập tên tính năng');
        hasError = true;
    }
    
    // Validate image - required for new, optional for update
    const hasImage = imageInput && imageInput.files && imageInput.files.length > 0;
    const hasExistingImage = document.getElementById('imagePreview') && !document.getElementById('imagePreview').classList.contains('hidden');
    
    if (!featuresProductId && !hasImage) {
        showError('featuresProductImage', 'Vui lòng chọn hình ảnh');
        hasError = true;
    }
    
    if (hasError) {
        console.error('Validation failed');
        if (typeof window.showError === 'function' && window.showError.length === 1) {
            window.showError('Vui lòng điền đầy đủ thông tin bắt buộc');
        } else if (typeof showNotification === 'function') {
            showNotification('Vui lòng điền đầy đủ thông tin bắt buộc', 'error');
        }
        return;
    }
    
    const formData = new FormData(form);
    const originalText = submitText.textContent;
    
    // Validate and ensure image file is included in FormData
    const imageFile = imageInput && imageInput.files && imageInput.files.length > 0 ? imageInput.files[0] : null;
    
    // For new items, image is required
    if (!featuresProductId && !imageFile) {
        console.error('Image file is required for new item');
        showError('featuresProductImage', 'Vui lòng chọn hình ảnh');
        if (typeof window.showError === 'function' && window.showError.length === 1) {
            window.showError('Vui lòng chọn hình ảnh để tiếp tục');
        }
        return;
    }
    
    // If we have a file, ensure it's in FormData
    if (imageFile) {
        // Verify file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml'];
        const allowedExtensions = ['.jpeg', '.jpg', '.png', '.gif', '.svg'];
        const fileName = imageFile.name.toLowerCase();
        const fileExtension = fileName.substring(fileName.lastIndexOf('.'));
        
        if (!allowedTypes.includes(imageFile.type) && !allowedExtensions.includes(fileExtension)) {
            showError('featuresProductImage', 'Chỉ chấp nhận file ảnh định dạng: JPEG, JPG, PNG, GIF, SVG');
            if (typeof window.showError === 'function' && window.showError.length === 1) {
                window.showError('File ảnh không đúng định dạng. Vui lòng chọn file JPEG, JPG, PNG, GIF hoặc SVG.');
            }
            return;
        }
        
        // Verify file size
        if (imageFile.size > 2048 * 1024) {
            showError('featuresProductImage', 'Kích thước ảnh không được vượt quá 2MB');
            if (typeof window.showError === 'function' && window.showError.length === 1) {
                window.showError('Kích thước ảnh quá lớn. Vui lòng chọn ảnh nhỏ hơn 2MB.');
            }
            return;
        }
        
        // Ensure file is in FormData (it should be already if input name="image")
        formData.set('image', imageFile);
        console.log('Image file added to FormData:', imageFile.name, imageFile.type, imageFile.size, 'bytes');
    } else if (!featuresProductId) {
        // This shouldn't happen due to validation above, but double-check
        console.error('Image file is missing for new item');
        showError('featuresProductImage', 'Vui lòng chọn hình ảnh');
        if (typeof window.showError === 'function' && window.showError.length === 1) {
            window.showError('Vui lòng chọn hình ảnh để tiếp tục');
        }
        return;
    }
    
    // Disable submit button
    submitBtn.disabled = true;
    submitText.innerHTML = '<span class="loading-spinner"></span> Đang lưu...';
    
    // Get ID from formData again to ensure we have the latest value
    const idValue = formData.get('id') || featuresProductId;
    const url = idValue 
        ? `/admin/features-product/${idValue}`
        : '/admin/features-product';
    
    const method = idValue ? 'PUT' : 'POST';
    
    // Debug: Log FormData entries
    console.log('Form validation passed. ID:', idValue, 'Method:', method, 'URL:', url);
    console.log('FormData entries:');
    for (let pair of formData.entries()) {
        if (pair[0] === 'image' && pair[1] instanceof File) {
            console.log(pair[0] + ': File -', pair[1].name, pair[1].size, 'bytes', pair[1].type);
        } else {
            console.log(pair[0] + ':', pair[1]);
        }
    }
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        if (typeof showError === 'function') {
            showError('Lỗi bảo mật: Không tìm thấy CSRF token');
        } else {
            alert('Lỗi bảo mật: Không tìm thấy CSRF token');
        }
        submitBtn.disabled = false;
        submitText.textContent = originalText;
        return;
    }
    
    formData.append('_token', csrfToken.content);
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    console.log('Sending request to:', url, 'Method:', method);
    console.log('FormData entries:', Array.from(formData.entries()));
    
    fetch(url, {
        method: method === 'PUT' ? 'POST' : method, // Laravel method spoofing
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        }
    })
    .then(response => {
        console.log('Response status:', response.status, response.statusText);
        return response.json().then(data => {
            if (!response.ok) {
                console.error('Error response:', data);
                throw data;
            }
            console.log('Success response:', data);
            return data;
        });
    })
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof showSuccess === 'function') {
                showSuccess(data.message || 'Lưu thành công!');
            } else if (typeof showNotification === 'function') {
                showNotification(data.message || 'Lưu thành công!', 'success');
            } else {
                alert(data.message || 'Lưu thành công!');
            }
            
            // Close modal
            closeFeaturesProductModal();
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            // Re-enable submit button first
            submitBtn.disabled = false;
            submitText.textContent = originalText;
            
            // Show validation errors for each field
            if (data.errors && typeof data.errors === 'object') {
                Object.keys(data.errors).forEach(field => {
                    // Map field names to input IDs and error div IDs
                    let inputId, errorDivId;
                    if (field === 'name') {
                        inputId = 'featuresProductName';
                        errorDivId = 'nameError';
                    } else if (field === 'image') {
                        inputId = 'featuresProductImageInput';
                        errorDivId = 'imageError';
                    } else {
                        // Fallback for other fields
                        inputId = 'featuresProduct' + field.charAt(0).toUpperCase() + field.slice(1);
                        errorDivId = field + 'Error';
                    }
                    
                    const errorMessages = Array.isArray(data.errors[field]) 
                        ? data.errors[field] 
                        : [data.errors[field]];
                    if (errorMessages.length > 0) {
                        showErrorForField(inputId, errorDivId, errorMessages[0]);
                    }
                });
            }
            
            // Show general error toast with message
            const errorMessage = data.message || 'Có lỗi xảy ra khi lưu dữ liệu';
            if (typeof showError === 'function') {
                showError(errorMessage);
            } else if (typeof showNotification === 'function') {
                showNotification(errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Re-enable submit button first
        submitBtn.disabled = false;
        submitText.textContent = originalText;
        
        // Check if error is a server response with errors object
        if (error && typeof error === 'object') {
            // Show validation errors for each field
            if (error.errors && typeof error.errors === 'object') {
                Object.keys(error.errors).forEach(field => {
                    // Map field names to input IDs and error div IDs
                    let inputId, errorDivId;
                    if (field === 'name') {
                        inputId = 'featuresProductName';
                        errorDivId = 'nameError';
                    } else if (field === 'image') {
                        inputId = 'featuresProductImageInput';
                        errorDivId = 'imageError';
                    } else {
                        // Fallback for other fields
                        inputId = 'featuresProduct' + field.charAt(0).toUpperCase() + field.slice(1);
                        errorDivId = field + 'Error';
                    }
                    
                    const errorMessages = Array.isArray(error.errors[field]) 
                        ? error.errors[field] 
                        : [error.errors[field]];
                    if (errorMessages.length > 0) {
                        showErrorForField(inputId, errorDivId, errorMessages[0]);
                    }
                });
            }
            
            // Show general error toast with message
            const errorMessage = error.message || error.error || 'Có lỗi xảy ra khi lưu tính năng';
            if (typeof showError === 'function') {
                showError(errorMessage);
            } else if (typeof showNotification === 'function') {
                showNotification(errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        } else {
            // Handle string errors or unexpected error format
            const errorMessage = error.message || error.error || String(error) || 'Có lỗi xảy ra khi lưu tính năng';
            if (typeof showError === 'function') {
                showError(errorMessage);
            } else if (typeof showNotification === 'function') {
                showNotification(errorMessage, 'error');
            } else {
                alert(errorMessage);
            }
        }
    });
}

// Delete function
function confirmDelete(id, name) {
    showConfirmModal({
        title: 'Xác nhận xóa',
        message: `Bạn có chắc chắn muốn xóa tính năng "${name}"? Hành động này không thể hoàn tác.`,
        type: 'danger',
        confirmText: 'Xóa',
        cancelText: 'Hủy',
        onConfirm: () => {
            performDelete(id);
        }
    });
}

function performDelete(id) {
    
    const deleteUrl = `/admin/features-product/${id}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        if (typeof showError === 'function') {
            showError('Lỗi bảo mật: Không tìm thấy CSRF token');
        } else {
            alert('Lỗi bảo mật: Không tìm thấy CSRF token');
        }
        return;
    }
    
    // Send DELETE request
    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw err;
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof showSuccess === 'function') {
                showSuccess(data.message || 'Xóa thành công!');
            } else if (typeof showNotification === 'function') {
                showNotification(data.message || 'Xóa thành công!', 'success');
            } else {
                alert(data.message || 'Xóa thành công!');
            }
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            if (typeof showError === 'function') {
                showError(data.message || 'Có lỗi xảy ra khi xóa tính năng');
            } else if (typeof showNotification === 'function') {
                showNotification(data.message || 'Có lỗi xảy ra khi xóa tính năng', 'error');
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa tính năng');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorMessage = error.message || 'Có lỗi xảy ra khi xóa tính năng';
        if (typeof showError === 'function') {
            showError(errorMessage);
        } else if (typeof showNotification === 'function') {
            showNotification(errorMessage, 'error');
        } else {
            alert(errorMessage);
        }
    });
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('featuresProductModal');
    if (event.target === modal) {
        closeFeaturesProductModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('featuresProductModal');
        if (modal && modal.classList.contains('show')) {
            closeFeaturesProductModal();
        }
    }
});

// Ensure form submit works
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('featuresProductForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Add click handler to submit button as backup
    if (submitBtn && form) {
        submitBtn.addEventListener('click', function(e) {
            // Only prevent default if form validation fails
            // Let the form's onsubmit handle it
            console.log('Submit button clicked');
        });
    }
});

// Drag and drop for image upload
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('featuresProductImageInput');
    
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

