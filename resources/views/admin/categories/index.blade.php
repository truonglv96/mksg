@extends('admin.layouts.master')

@section('title', 'Quản lý danh mục')

@php
$breadcrumbs = [
    ['label' => 'Danh mục', 'url' => route('admin.categories.index')]
];
@endphp

@push('styles')
<style>
    .category-tree {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .category-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
        cursor: move;
    }
    
    .category-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }
    
    .category-item.dragging {
        opacity: 0.5;
        transform: rotate(2deg);
    }
    
    .category-item.drag-over {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .category-children-wrapper {
        overflow: hidden;
        transition: max-height 0.3s ease-out, opacity 0.25s ease-out, margin 0.3s ease-out;
        max-height: 10000px;
        opacity: 1;
    }
    
    .category-children-wrapper.collapsed {
        max-height: 0 !important;
        opacity: 0;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
    
    .category-children {
        margin-left: 32px;
        margin-top: 8px;
        margin-bottom: 8px;
        padding-left: 16px;
        border-left: 2px solid #e5e7eb;
        transition: padding 0.3s ease-out, border 0.3s ease-out;
    }
    
    .category-children-wrapper.collapsed .category-children {
        padding-left: 0;
        padding-top: 0;
        padding-bottom: 0;
        border-left: none;
    }
    
    .category-toggle-btn {
        min-width: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .category-toggle-btn i {
        transition: transform 0.2s ease;
        font-size: 12px;
    }
    
    .category-toggle-btn.collapsed i {
        transform: rotate(-90deg);
    }
    
    .category-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .type-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .type-product {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .type-new {
        background: #fef3c7;
        color: #92400e;
    }
    
    .type-brand {
        background: #fce7f3;
        color: #9f1239;
    }
    
    .type-partner {
        background: #d1fae5;
        color: #065f46;
    }
    
    .sortable-ghost {
        opacity: 0.4;
    }
    
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.3;
    }
    
    /* Modal Styles - Ensure proper display */
    #categoryModal, #deleteModal {
        backdrop-filter: blur(4px);
    }
    
    #categoryModal.show, #deleteModal.show {
        display: flex !important;
    }
    
    #categoryModal.hidden, #deleteModal.hidden {
        display: none !important;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-folder-tree text-primary-600 mr-3"></i>
                Quản lý danh mục
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và sắp xếp danh mục sản phẩm, tin tức, thương hiệu...</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Filter by Type -->
            <select id="filterType" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Tất cả loại</option>
                <option value="product">Sản phẩm</option>
                <option value="new">Tin tức</option>
                <option value="brand">Thương hiệu</option>
            </select>
            
            <!-- Expand/Collapse Buttons -->
            <div class="flex items-center gap-2 border-r border-gray-300 pr-3">
                <button onclick="expandAll()" 
                        class="inline-flex items-center px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        title="Mở tất cả">
                    <i class="fas fa-chevron-down mr-1.5"></i>
                    <span>Mở tất cả</span>
                </button>
                <button onclick="collapseAll()" 
                        class="inline-flex items-center px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        title="Đóng tất cả">
                    <i class="fas fa-chevron-up mr-1.5"></i>
                    <span>Đóng tất cả</span>
                </button>
            </div>
            
            <button onclick="openCreateModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                <i class="fas fa-plus mr-2"></i>
                <span>Thêm danh mục</span>
            </button>
        </div>
    </div>
</div>

<!-- Categories Tree -->
<div class="category-tree p-6 fade-in">
    <div id="categoriesContainer">
        @if($categories->isEmpty())
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p class="text-lg font-medium">Chưa có danh mục nào</p>
                <p class="text-sm mt-2">Nhấn "Thêm danh mục" để bắt đầu</p>
            </div>
        @else
            <div id="categoryList" class="space-y-2">
                @include('admin.categories.partials.category-tree', ['categories' => $categories, 'level' => 0])
            </div>
        @endif
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h2 id="modalTitle" class="text-xl font-bold text-gray-900">Thêm danh mục mới</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="categoryForm" class="p-6">
            @csrf
            <input type="hidden" id="categoryId" name="id">
            <input type="hidden" id="formMethod" name="_method" value="POST">
            
            <!-- Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Loại danh mục <span class="text-red-500">*</span>
                </label>
                <select id="categoryType" name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Chọn loại danh mục</option>
                    <option value="product">Sản phẩm</option>
                    <option value="new">Tin tức</option>
                    <option value="brand">Thương hiệu</option>
                    <option value="partner">Đối Tác</option>
                </select>
            </div>
            
            <!-- Parent Category -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Danh mục cha (tùy chọn)
                </label>
                <select id="parentId" name="parent_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="0">Không có (Danh mục gốc)</option>
                    @foreach($flatCategories as $cat)
                        <option value="{{ $cat->id }}">{{ str_repeat('— ', $cat->level ?? 0) }}{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Name -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tên danh mục <span class="text-red-500">*</span>
                </label>
                <input type="text" id="categoryName" name="name" required 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="Nhập tên danh mục">
            </div>
            
            <!-- Alias/Slug -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Đường dẫn (Alias) <span class="text-red-500">*</span>
                </label>
                <input type="text" id="categoryAlias" name="alias" required 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="duong-dan-danh-muc">
                <p class="mt-1 text-xs text-gray-500">Sẽ tự động tạo từ tên nếu để trống</p>
            </div>
            
            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Mô tả ngắn
                </label>
                <textarea id="categoryDes" name="des" rows="3" 
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                          placeholder="Mô tả về danh mục này..."></textarea>
            </div>
            
            <!-- Keywords -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Từ khóa (SEO)
                </label>
                <input type="text" id="categoryKw" name="kw" 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="từ khóa 1, từ khóa 2, từ khóa 3">
            </div>
            
            <!-- Visibility Options -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <div>
                    <input type="hidden" name="hidden" value="0">
                    <label class="flex items-center">
                        <input type="checkbox" id="categoryHidden" name="hidden" value="1" checked class="mr-2 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm font-medium text-gray-700">Hiển thị</span>
                    </label>
                </div>
                <div>
                    <input type="hidden" name="index_hidden" value="0">
                    <label class="flex items-center">
                        <input type="checkbox" id="categoryIndexHidden" name="index_hidden" value="1" checked class="mr-2 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm font-medium text-gray-700">Hiển thị ở trang chủ</span>
                    </label>
                </div>
            </div>
            
            <!-- Weight/Order -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Thứ tự hiển thị
                </label>
                <input type="number" id="categoryWeight" name="weight" value="0" 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="0">
            </div>
            
            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" 
                        class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Hủy
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                    <span id="submitText">Lưu</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Xác nhận xóa</h3>
            <p class="text-sm text-gray-500 mb-6">
                Bạn có chắc chắn muốn xóa danh mục "<span id="deleteCategoryName" class="font-semibold"></span>"?
                <br><span class="text-red-600 font-medium">Tất cả danh mục con sẽ bị xóa cùng!</span>
            </p>
            <div class="flex items-center justify-center gap-3">
                <button onclick="closeDeleteModal()" 
                        class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Hủy
                </button>
                <button onclick="confirmDelete()" 
                        class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Xóa
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let deleteCategoryId = null;
let sortableInstances = [];

// Initialize Sortable for categories
document.addEventListener('DOMContentLoaded', function() {
    initializeSortable();
    setupFilter();
    restoreCollapseState();
});

function initializeSortable() {
    // Destroy existing instances
    sortableInstances.forEach(instance => instance.destroy());
    sortableInstances = [];
    
    // Get all category lists (including nested ones) - process in reverse order for nested
    const categoryLists = document.querySelectorAll('#categoryList, .category-children-list');
    
    categoryLists.forEach(list => {
        const sortable = Sortable.create(list, {
            animation: 200,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'category-item-drag',
            group: 'nested-categories',
            fallbackOnBody: true,
            swapThreshold: 0.65,
            onEnd: function(evt) {
                updateCategoryOrder();
            }
        });
        sortableInstances.push(sortable);
    });
}

function updateCategoryOrder() {
    const orderData = [];
    
    // Process root level categories
    const rootList = document.getElementById('categoryList');
    if (rootList) {
        processCategoryList(rootList, '0', orderData);
    }
    
    // Send to server
    axios.post('{{ route("admin.categories.reorder") }}', {
        categories: orderData
    })
    .then(response => {
        if (response.data.success) {
            showNotification('Đã cập nhật thứ tự danh mục!', 'success');
            setTimeout(() => location.reload(), 500);
        }
    })
    .catch(error => {
        console.error(error);
        showNotification('Có lỗi xảy ra khi cập nhật thứ tự!', 'error');
    });
}

function processCategoryList(list, parentId, orderData) {
    const items = list.querySelectorAll(':scope > .category-item');
    items.forEach((item, index) => {
        const id = item.getAttribute('data-id');
        orderData.push({
            id: id,
            parent_id: parentId,
            weight: index + 1
        });
        
        // Process children if any
        const childrenList = item.querySelector('.category-children-list');
        if (childrenList) {
            processCategoryList(childrenList, id, orderData);
        }
    });
}

function setupFilter() {
    const filterSelect = document.getElementById('filterType');
    if (filterSelect) {
        const urlParams = new URLSearchParams(window.location.search);
        const type = urlParams.get('type');
        if (type) {
            filterSelect.value = type;
        }
        
        filterSelect.addEventListener('change', function() {
            const type = this.value;
            if (type) {
                window.location.href = '{{ route("admin.categories.index") }}?type=' + type;
            } else {
                window.location.href = '{{ route("admin.categories.index") }}';
            }
        });
    }
}

// Toggle category collapse/expand
function toggleCategory(categoryId) {
    const wrapper = document.querySelector(`.category-children-wrapper[data-category-id="${categoryId}"]`);
    const toggleBtn = document.querySelector(`.category-toggle-btn[data-category-id="${categoryId}"]`);
    
    if (wrapper && toggleBtn) {
        const isCollapsed = wrapper.classList.contains('collapsed');
        
        if (isCollapsed) {
            // Expand
            wrapper.classList.remove('collapsed');
            toggleBtn.classList.remove('collapsed');
            saveCollapseState(categoryId, false);
        } else {
            // Collapse
            wrapper.classList.add('collapsed');
            toggleBtn.classList.add('collapsed');
            saveCollapseState(categoryId, true);
        }
    }
}

// Save collapse state to localStorage
function saveCollapseState(categoryId, isCollapsed) {
    const key = 'category_collapse_state';
    let states = JSON.parse(localStorage.getItem(key) || '{}');
    states[categoryId] = isCollapsed;
    localStorage.setItem(key, JSON.stringify(states));
}

// Restore collapse state from localStorage
function restoreCollapseState() {
    const key = 'category_collapse_state';
    const states = JSON.parse(localStorage.getItem(key) || '{}');
    
    Object.keys(states).forEach(categoryId => {
        if (states[categoryId] === true) {
            const wrapper = document.querySelector(`.category-children-wrapper[data-category-id="${categoryId}"]`);
            const toggleBtn = document.querySelector(`.category-toggle-btn[data-category-id="${categoryId}"]`);
            
            if (wrapper && toggleBtn) {
                wrapper.classList.add('collapsed');
                toggleBtn.classList.add('collapsed');
            }
        }
    });
}

// Expand all categories
function expandAll() {
    document.querySelectorAll('.category-children-wrapper.collapsed').forEach(wrapper => {
        const categoryId = wrapper.getAttribute('data-category-id');
        const toggleBtn = document.querySelector(`.category-toggle-btn[data-category-id="${categoryId}"]`);
        
        wrapper.classList.remove('collapsed');
        if (toggleBtn) {
            toggleBtn.classList.remove('collapsed');
        }
        saveCollapseState(categoryId, false);
    });
}

// Collapse all categories
function collapseAll() {
    document.querySelectorAll('.category-children-wrapper:not(.collapsed)').forEach(wrapper => {
        const categoryId = wrapper.getAttribute('data-category-id');
        const toggleBtn = document.querySelector(`.category-toggle-btn[data-category-id="${categoryId}"]`);
        
        wrapper.classList.add('collapsed');
        if (toggleBtn) {
            toggleBtn.classList.add('collapsed');
        }
        saveCollapseState(categoryId, true);
    });
}

function openCreateModal(parentId = null) {
    const modal = document.getElementById('categoryModal');
    document.getElementById('modalTitle').textContent = 'Thêm danh mục mới';
    document.getElementById('submitText').textContent = 'Tạo mới';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('categoryForm').action = '{{ route("admin.categories.store") }}';
    if (parentId) {
        document.getElementById('parentId').value = parentId;
    }
    modal.classList.remove('hidden');
    modal.classList.add('show');
}

function openEditModal(id, name, alias, type, parentId, des, kw, hidden, indexHidden, weight) {
    const modal = document.getElementById('categoryModal');
    document.getElementById('modalTitle').textContent = 'Sửa danh mục';
    document.getElementById('submitText').textContent = 'Cập nhật';
    document.getElementById('categoryId').value = id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('categoryName').value = name;
    document.getElementById('categoryAlias').value = alias;
    document.getElementById('categoryType').value = type;
    document.getElementById('parentId').value = parentId || '0';
    document.getElementById('categoryDes').value = des || '';
    document.getElementById('categoryKw').value = kw || '';
    document.getElementById('categoryHidden').checked = hidden == 1;
    document.getElementById('categoryIndexHidden').checked = indexHidden == 1;
    document.getElementById('categoryWeight').value = weight || 0;
    document.getElementById('categoryForm').action = '{{ route("admin.categories.update", ":id") }}'.replace(':id', id);
    modal.classList.remove('hidden');
    modal.classList.add('show');
}

function closeModal() {
    const modal = document.getElementById('categoryModal');
    modal.classList.remove('show');
    modal.classList.add('hidden');
}

// Auto generate alias from name
document.getElementById('categoryName')?.addEventListener('input', function() {
    const aliasInput = document.getElementById('categoryAlias');
    if (aliasInput && !aliasInput.dataset.userEdited) {
        const name = this.value;
        const alias = name.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/Đ/g, 'D')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        aliasInput.value = alias;
    }
});

document.getElementById('categoryAlias')?.addEventListener('input', function() {
    this.dataset.userEdited = 'true';
});

// Handle form submission
document.getElementById('categoryForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = this.action;
    
    const config = {
        headers: {
            'Content-Type': 'multipart/form-data',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    };
    
    // Laravel method spoofing: always POST, with _method field in form data
    axios.post(url, formData, config)
        .then(response => {
            showNotification(response.data.message || 'Thao tác thành công!', 'success');
            closeModal();
            setTimeout(() => location.reload(), 500);
        })
        .catch(error => {
            const message = error.response?.data?.message || 'Có lỗi xảy ra!';
            showNotification(message, 'error');
        });
});

function openDeleteModal(id, name) {
    deleteCategoryId = id;
    const modal = document.getElementById('deleteModal');
    document.getElementById('deleteCategoryName').textContent = name;
    modal.classList.remove('hidden');
    modal.classList.add('show');
}

function closeDeleteModal() {
    deleteCategoryId = null;
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('show');
    modal.classList.add('hidden');
}

function confirmDelete() {
    if (!deleteCategoryId) return;
    
    axios.delete('{{ route("admin.categories.destroy", ":id") }}'.replace(':id', deleteCategoryId))
        .then(response => {
            showNotification('Đã xóa danh mục thành công!', 'success');
            closeDeleteModal();
            setTimeout(() => location.reload(), 500);
        })
        .catch(error => {
            const message = error.response?.data?.message || 'Có lỗi xảy ra khi xóa!';
            showNotification(message, 'error');
        });
}

function showNotification(message, type) {
    const bgColor = type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${bgColor} border rounded-lg p-4 shadow-lg z-50 flex items-center gap-3 max-w-md`;
    notification.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-gray-600 hover:text-gray-800">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush

