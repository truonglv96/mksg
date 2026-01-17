@extends('admin.layouts.master')

@section('title', 'Quản lý chất liệu')

@php
$breadcrumbs = [
    ['label' => 'Chất liệu', 'url' => route('admin.materials.index')]
];
@endphp

@push('styles')
<style>
    .material-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .material-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    /* Modal Styles */
    #materialModal {
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
    
    #materialModal.show {
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
    
    #materialModal.show .modal-content {
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
                <p class="text-blue-100 text-sm font-medium">Tổng chất liệu</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalMaterials ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-layer-group text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Đang sử dụng</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalMaterials ?? 0) }}</p>
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
                <i class="fas fa-layer-group mr-3" style="color: #2563eb;"></i>
                Quản lý chất liệu
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và theo dõi tất cả chất liệu sản phẩm</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="viewToggle" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-th view-icon" data-view="grid"></i>
                <i class="fas fa-list view-icon hidden" data-view="list"></i>
            </button>
            <button onclick="openMaterialModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                <i class="fas fa-plus mr-2"></i>
                <span>Thêm chất liệu</span>
            </button>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <form method="GET" action="{{ route('admin.materials.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search -->
        <div class="md:col-span-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-gray-400"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       name="search"
                       value="{{ request('search', '') }}"
                       placeholder="Tìm theo tên chất liệu..." 
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
            <a href="{{ route('admin.materials.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Materials Grid/Table -->
<div id="materialsContainer">
    @if(isset($materials) && $materials->count() > 0)
        <!-- Grid View (Default) -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($materials as $material)
                <div class="material-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-layer-group text-white text-2xl"></i>
                            </div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                #{{ $material->weight ?? 0 }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $material->name }}</h3>
                        
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                            <button onclick="openMaterialModal({{ $material->id }})" 
                                    class="flex-1 px-3 py-2 text-center text-sm bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                                <i class="fas fa-edit mr-1"></i>Sửa
                            </button>
                            <button onclick="confirmDelete({{ $material->id }}, '{{ addslashes($material->name) }}')" 
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
                                Thứ tự
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tên chất liệu
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
                        @foreach($materials as $material)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-700">
                                        {{ $material->weight ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-layer-group text-white"></i>
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $material->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $material->created_at ? $material->created_at->format('d/m/Y') : '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openMaterialModal({{ $material->id }})" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200" 
                                                title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $material->id }}, '{{ addslashes($material->name) }}')" 
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
        @if(isset($materials) && $materials->hasPages())
            <div class="mt-6">
                {{ $materials->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-layer-group text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có chất liệu nào</h3>
            <p class="text-gray-500 mb-6">Hãy bắt đầu bằng cách tạo chất liệu đầu tiên của bạn</p>
            <button onclick="openMaterialModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-plus mr-2"></i>
                Thêm chất liệu
            </button>
        </div>
    @endif
</div>

<!-- Material Modal -->
<div id="materialModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <i class="fas fa-layer-group"></i>
                <span id="modalTitle">Thêm chất liệu mới</span>
            </h2>
            <button class="modal-close" onclick="closeMaterialModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="materialForm" onsubmit="saveMaterial(event)">
            <div class="modal-body">
                <input type="hidden" id="materialId" name="id">
                
                <div class="form-group">
                    <label class="form-label" for="materialName">
                        <i class="fas fa-tag mr-2 text-gray-400"></i>Tên chất liệu <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="materialName" 
                           name="name" 
                           class="form-input" 
                           placeholder="Nhập tên chất liệu..."
                           required>
                    <div id="nameError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="materialWeight">
                        <i class="fas fa-sort-numeric-down mr-2 text-gray-400"></i>Thứ tự sắp xếp
                    </label>
                    <input type="number" 
                           id="materialWeight" 
                           name="weight" 
                           class="form-input" 
                           placeholder="0"
                           min="0"
                           value="0">
                    <p class="text-xs text-gray-500 mt-2">Số càng nhỏ, hiển thị càng trước</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeMaterialModal()" class="btn btn-secondary">
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
    'id' => 'deleteMaterialModal',
    'title' => 'Xác nhận xóa chất liệu',
    'message' => 'Bạn có chắc chắn muốn xóa chất liệu "{name}"?',
    'confirmText' => 'Xóa chất liệu'
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

// Material Modal Functions
function openMaterialModal(materialId = null) {
    const modal = document.getElementById('materialModal');
    const form = document.getElementById('materialForm');
    const title = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitText');
    
    if (!modal || !form || !title || !submitText) {
        console.error('Modal elements not found');
        return;
    }
    
    // Reset form
    form.reset();
    const materialIdInput = document.getElementById('materialId');
    if (materialIdInput) {
        materialIdInput.value = '';
    }
    clearErrors();
    
    if (materialId) {
        // Edit mode
        title.textContent = 'Sửa chất liệu';
        submitText.textContent = 'Cập nhật';
        
        // Fetch material data
        fetch(`/admin/materials/${materialId}`, {
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
                const idInput = document.getElementById('materialId');
                const nameInput = document.getElementById('materialName');
                const weightInput = document.getElementById('materialWeight');
                
                if (idInput) idInput.value = data.material.id;
                if (nameInput) nameInput.value = data.material.name;
                if (weightInput) weightInput.value = data.material.weight || 0;
            } else {
                alert(data.message || 'Không thể tải dữ liệu chất liệu');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.');
        });
    } else {
        // Create mode
        title.textContent = 'Thêm chất liệu mới';
        submitText.textContent = 'Lưu';
    }
    
    // Show modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeMaterialModal() {
    const modal = document.getElementById('materialModal');
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

function saveMaterial(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    const formData = new FormData(form);
    const materialId = formData.get('id');
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
    
    const url = materialId 
        ? `/admin/materials/${materialId}`
        : '/admin/materials';
    
    // Always POST with _method override to ensure Laravel reads FormData
    const method = 'POST';
    
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
    if (materialId) {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        },
        credentials: 'same-origin'
    })
    .then(async response => {
        if (!response.ok) {
            let errorMessage = `HTTP error! status: ${response.status}`;
            try {
                const err = await response.json();
                errorMessage = err.message || errorMessage;
            } catch (e) {
                // Non-JSON response (e.g., 419 HTML)
            }
            throw new Error(errorMessage);
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
            closeMaterialModal();
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            // Show error
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    showError('material' + field.charAt(0).toUpperCase() + field.slice(1), data.errors[field][0]);
                });
            } else if (typeof showNotification === 'function') {
                showNotification(data.message || 'Có lỗi xảy ra', 'error');
            } else if (typeof showError === 'function') {
                showError(data.message || 'Có lỗi xảy ra');
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
            
            submitBtn.disabled = false;
            submitText.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const message = error.message || 'Có lỗi xảy ra khi lưu chất liệu';
        if (typeof showNotification === 'function') {
            showNotification(message, 'error');
        } else if (typeof showError === 'function') {
            showError(message);
        } else {
            alert(message);
        }
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    });
}

// Delete function
function confirmDelete(id, name) {
    const deleteUrl = `/admin/materials/${id}`;
    window.openDeleteModal('deleteMaterialModal', deleteUrl, name);
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('materialModal');
    if (event.target === modal) {
        closeMaterialModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('materialModal');
        if (modal && modal.classList.contains('show')) {
            closeMaterialModal();
        }
    }
});
</script>
@endpush

