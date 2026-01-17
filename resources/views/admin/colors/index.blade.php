@extends('admin.layouts.master')

@section('title', 'Quản lý màu sắc')

@php
$breadcrumbs = [
    ['label' => 'Màu sắc', 'url' => route('admin.colors.index')]
];
@endphp

@push('styles')
<style>
    .color-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .color-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    .color-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f9fafb;
    }
    
    /* Modal Styles */
    #colorModal {
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
    
    #colorModal.show {
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
    
    #colorModal.show .modal-content {
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
</style>
@endpush

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 fade-in">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Tổng màu sắc</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalColors ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-palette text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Đang sử dụng</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalColors ?? 0) }}</p>
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
                <i class="fas fa-palette mr-3" style="color: #2563eb;"></i>
                Quản lý màu sắc
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và theo dõi tất cả màu sắc sản phẩm</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="viewToggle" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-th view-icon" data-view="grid"></i>
                <i class="fas fa-list view-icon hidden" data-view="list"></i>
            </button>
            <button onclick="openColorModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                <i class="fas fa-plus mr-2"></i>
                <span>Thêm màu sắc</span>
            </button>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <form method="GET" action="{{ route('admin.colors.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search -->
        <div class="md:col-span-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-gray-400"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       name="search"
                       value="{{ request('search', '') }}"
                       placeholder="Tìm theo tên màu sắc..." 
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
                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
            </select>
        </div>
        
        <!-- Actions -->
        <div class="md:col-span-2 flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-search mr-2"></i>Tìm
            </button>
            <a href="{{ route('admin.colors.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Colors Grid/Table -->
<div id="colorsContainer">
    @if(isset($colors) && $colors->count() > 0)
        <!-- Grid View (Default) -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($colors as $color)
                <div class="color-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @if($color->url_img)
                        <div class="w-full">
                            <img src="{{ asset('img/color/' . $color->url_img) }}" 
                                 alt="{{ $color->name }}" 
                                 class="w-full h-48 object-cover"
                                 onerror="this.style.display='none';">
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 flex-1">{{ $color->name }}</h3>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700 ml-2 flex-shrink-0">
                                #{{ $color->weight ?? 0 }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                            <button onclick="openColorModal({{ $color->id }})" 
                                    class="flex-1 px-3 py-2 text-center text-sm bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                                <i class="fas fa-edit mr-1"></i>Sửa
                            </button>
                            <button onclick="confirmDelete({{ $color->id }}, '{{ addslashes($color->name) }}')" 
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
                                Tên màu sắc
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
                        @foreach($colors as $color)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        @if($color->url_img)
                                            <img src="{{ asset('img/color/' . $color->url_img) }}" 
                                                 alt="{{ $color->name }}" 
                                                 class="w-full h-full object-contain"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <i class="fas fa-palette text-gray-400 text-xl" style="display: none;"></i>
                                        @else
                                            <i class="fas fa-palette text-gray-400 text-xl"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-700">
                                        {{ $color->weight ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="text-sm font-semibold text-gray-900">{{ $color->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $color->created_at ? $color->created_at->format('d/m/Y') : '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openColorModal({{ $color->id }})" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200" 
                                                title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $color->id }}, '{{ addslashes($color->name) }}')" 
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
        @if(isset($colors) && $colors->hasPages())
            <div class="mt-6">
                {{ $colors->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-palette text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có màu sắc nào</h3>
            <p class="text-gray-500 mb-6">Hãy bắt đầu bằng cách tạo màu sắc đầu tiên của bạn</p>
            <button onclick="openColorModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-plus mr-2"></i>
                Thêm màu sắc
            </button>
        </div>
    @endif
</div>

<!-- Color Modal -->
<div id="colorModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <i class="fas fa-palette"></i>
                <span id="modalTitle">Thêm màu sắc mới</span>
            </h2>
            <button class="modal-close" onclick="closeColorModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="colorForm" onsubmit="saveColor(event)" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" id="colorId" name="id">
                
                <div class="form-group">
                    <label class="form-label" for="colorName">
                        <i class="fas fa-tag mr-2 text-gray-400"></i>Tên màu sắc <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="colorName" 
                           name="name" 
                           class="form-input" 
                           placeholder="Nhập tên màu sắc..."
                           required>
                    <div id="nameError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="colorWeight">
                        <i class="fas fa-sort-numeric-down mr-2 text-gray-400"></i>Thứ tự sắp xếp
                    </label>
                    <input type="number" 
                           id="colorWeight" 
                           name="weight" 
                           class="form-input" 
                           placeholder="0"
                           min="0"
                           value="0">
                    <p class="text-xs text-gray-500 mt-2">Số càng nhỏ, hiển thị càng trước</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="colorImage">
                        <i class="fas fa-image mr-2 text-gray-400"></i>Hình ảnh màu sắc
                    </label>
                    <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('colorImageInput').click()">
                        <input type="file" 
                               id="colorImageInput" 
                               name="url_img" 
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
                <button type="button" onclick="closeColorModal()" class="btn btn-secondary">
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
    'id' => 'deleteColorModal',
    'title' => 'Xác nhận xóa màu sắc',
    'message' => 'Bạn có chắc chắn muốn xóa màu sắc "{name}"?',
    'confirmText' => 'Xóa màu sắc'
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
});

// Color Modal Functions
function openColorModal(colorId = null) {
    const modal = document.getElementById('colorModal');
    const form = document.getElementById('colorForm');
    const title = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitText');
    
    if (!modal || !form || !title || !submitText) {
        console.error('Modal elements not found');
        return;
    }
    
    // Reset form
    form.reset();
    const colorIdInput = document.getElementById('colorId');
    if (colorIdInput) {
        colorIdInput.value = '';
    }
    clearErrors();
    resetImagePreview();
    
    if (colorId) {
        // Edit mode
        title.textContent = 'Sửa màu sắc';
        submitText.textContent = 'Cập nhật';
        
        // Fetch color data
        fetch(`/admin/colors/${colorId}`, {
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
                const idInput = document.getElementById('colorId');
                const nameInput = document.getElementById('colorName');
                const weightInput = document.getElementById('colorWeight');
                
                if (idInput) idInput.value = data.color.id;
                if (nameInput) nameInput.value = data.color.name;
                if (weightInput) weightInput.value = data.color.weight || 0;
                
                // Show existing image if available
                if (data.color.url_img) {
                    showImagePreview(`{{ asset('img/color') }}/${data.color.url_img}`);
                }
            } else {
                alert(data.message || 'Không thể tải dữ liệu màu sắc');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.');
        });
    } else {
        // Create mode
        title.textContent = 'Thêm màu sắc mới';
        submitText.textContent = 'Lưu';
    }
    
    // Show modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeColorModal() {
    const modal = document.getElementById('colorModal');
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
    } else {
        console.warn('Error div not found for field:', field);
    }
}

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2048 * 1024) {
            showError('colorImage', 'Kích thước ảnh không được vượt quá 2MB');
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
    const imageInput = document.getElementById('colorImageInput');
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

function saveColor(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    const formData = new FormData(form);
    const colorId = formData.get('id');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    if (!submitBtn || !submitText) {
        console.error('Submit button elements not found');
        return;
    }
    
    const originalText = submitText.textContent;
    
    // Clear previous errors
    clearErrors();
    
    // Disable submit button
    submitBtn.disabled = true;
    submitText.innerHTML = '<span class="loading-spinner"></span> Đang lưu...';
    
    const url = colorId 
        ? `/admin/colors/${colorId}`
        : '/admin/colors';
    
    const method = colorId ? 'PUT' : 'POST';
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        alert('Lỗi bảo mật: Không tìm thấy CSRF token');
        submitBtn.disabled = false;
        submitText.textContent = originalText;
        return;
    }
    
    formData.append('_token', csrfToken.content);
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        // Laravel method spoofing for multipart form data
        method: method === 'PUT' ? 'POST' : method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            if (typeof showNotification === 'function') {
                showNotification(data.message, 'success');
            } else {
                alert(data.message);
            }
            
            // Close modal
            closeColorModal();
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            // Show error
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    showError('color' + field.charAt(0).toUpperCase() + field.slice(1), data.errors[field][0]);
                });
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
            
            submitBtn.disabled = false;
            submitText.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi lưu màu sắc');
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    });
}

// Delete function
function confirmDelete(id, name) {
    const deleteUrl = `/admin/colors/${id}`;
    window.openDeleteModal('deleteColorModal', deleteUrl, name);
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('colorModal');
    if (event.target === modal) {
        closeColorModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('colorModal');
        if (modal && modal.classList.contains('show')) {
            closeColorModal();
        }
    }
});

// Drag and drop for image upload
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('colorImageInput');
    
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

