@extends('admin.layouts.master')

@section('title', 'Quản lý thông tin cửa hàng')

@php
$breadcrumbs = [
    ['label' => 'Thông tin cửa hàng', 'url' => route('admin.store-information.index')]
];
@endphp

@push('styles')
<style>
    .contact-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .contact-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    /* Modal Styles */
    #contactModal {
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
    
    #contactModal.show {
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
    
    #contactModal.show .modal-content {
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
        min-height: 80px;
    }
    
    .form-textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
                <p class="text-blue-100 text-sm font-medium">Tổng cửa hàng</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalContacts ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-store text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Đang hiển thị</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($activeContacts ?? 0) }}</p>
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
                <i class="fas fa-store mr-3" style="color: #2563eb;"></i>
                Quản lý thông tin cửa hàng
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và theo dõi tất cả thông tin cửa hàng</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="viewToggle" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-th view-icon" data-view="grid"></i>
                <i class="fas fa-list view-icon hidden" data-view="list"></i>
            </button>
            <button onclick="openContactModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 text-white rounded-lg hover:from-red-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5" style="background: linear-gradient(to right, #dc2626, #2563eb); display: inline-flex; align-items: center; padding: 0.625rem 1.25rem; color: white; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); transition: all 0.2s;">
                <i class="fas fa-plus mr-2"></i>
                <span class="font-medium">Thêm thông tin cửa hàng</span>
            </button>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <form method="GET" action="{{ route('admin.store-information.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search -->
        <div class="md:col-span-8">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-gray-400"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       name="search"
                       value="{{ request('search', '') }}"
                       placeholder="Tìm theo tên, địa chỉ, số điện thoại..." 
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
            <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 text-white rounded-lg hover:from-red-700 hover:to-blue-700 transition-colors" style="background: linear-gradient(to right, #dc2626, #2563eb);">
                <i class="fas fa-search mr-2"></i>Tìm
            </button>
            <a href="{{ route('admin.store-information.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Contacts Grid/Table -->
<div id="contactsContainer">
    @if(isset($contacts) && $contacts->count() > 0)
        <!-- Grid View (Default) -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($contacts as $contact)
                <div class="contact-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-store text-white text-2xl"></i>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $contact->status == 1 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $contact->status == 1 ? 'Hiển thị' : 'Ẩn' }}
                                </span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                    #{{ $contact->weight ?? 0 }}
                                </span>
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $contact->name }}</h3>
                        
                        @if($contact->phone)
                            <div class="mb-2 text-sm text-gray-600">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>{{ $contact->phone }}
                            </div>
                        @endif
                        
                        @if($contact->address)
                            <div class="mb-2 text-sm text-gray-600 line-clamp-2">
                                <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>{{ $contact->address }}
                            </div>
                        @endif
                        
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                            <button onclick="openContactModal({{ $contact->id }})" 
                                    class="flex-1 px-3 py-2 text-center text-sm bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                                <i class="fas fa-edit mr-1"></i>Sửa
                            </button>
                            <button onclick="confirmDelete({{ $contact->id }}, '{{ addslashes($contact->name) }}')" 
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
                                Tên cửa hàng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Điện thoại
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
                        @foreach($contacts as $contact)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-700">
                                        {{ $contact->weight ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-store text-white"></i>
                                        </div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $contact->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $contact->phone ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $contact->status == 1 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $contact->status == 1 ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $contact->created_at ? $contact->created_at->format('d/m/Y') : '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openContactModal({{ $contact->id }})" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200" 
                                                title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirmDelete({{ $contact->id }}, '{{ addslashes($contact->name) }}')" 
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
        @if(isset($contacts) && $contacts->hasPages())
            <div class="mt-6">
                {{ $contacts->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-store text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có thông tin cửa hàng nào</h3>
            <p class="text-gray-500 mb-6">Hãy bắt đầu bằng cách tạo thông tin cửa hàng đầu tiên của bạn</p>
            <button onclick="openContactModal()" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 text-white rounded-lg hover:from-red-700 hover:to-blue-700 transition-colors" style="background: linear-gradient(to right, #dc2626, #2563eb); color: white; display: inline-flex; align-items: center; padding: 0.625rem 1.25rem; border-radius: 0.5rem; transition: all 0.2s;">
                <i class="fas fa-plus mr-2"></i>
                Thêm thông tin cửa hàng
            </button>
        </div>
    @endif
</div>

<!-- Contact Modal -->
<div id="contactModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <i class="fas fa-store"></i>
                <span id="modalTitle">Thêm thông tin cửa hàng mới</span>
            </h2>
            <button class="modal-close" onclick="closeContactModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="contactForm" onsubmit="saveContact(event)">
            <div class="modal-body">
                <input type="hidden" id="contactId" name="id">
                
                <div class="form-group">
                    <label class="form-label" for="contactName">
                        <i class="fas fa-store mr-2 text-gray-400"></i>Tên cửa hàng <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="contactName" 
                           name="name" 
                           class="form-input" 
                           placeholder="Nhập tên cửa hàng..."
                           required>
                    <div id="nameError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="contactPhone">
                        <i class="fas fa-phone mr-2 text-gray-400"></i>Số điện thoại
                    </label>
                    <input type="text" 
                           id="contactPhone" 
                           name="phone" 
                           class="form-input" 
                           placeholder="Nhập số điện thoại...">
                    <div id="phoneError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="contactAddress">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>Địa chỉ
                    </label>
                    <textarea id="contactAddress" 
                              name="address" 
                              class="form-textarea" 
                              placeholder="Nhập địa chỉ cửa hàng..."
                              rows="3"></textarea>
                    <div id="addressError" class="error-message hidden"></div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label" for="contactStartTime">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>Giờ mở cửa
                        </label>
                        <input type="text" 
                               id="contactStartTime" 
                               name="strart_time" 
                               class="form-input" 
                               placeholder="VD: 08:00">
                        <div id="strart_timeError" class="error-message hidden"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="contactEndTime">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>Giờ đóng cửa
                        </label>
                        <input type="text" 
                               id="contactEndTime" 
                               name="end_time" 
                               class="form-input" 
                               placeholder="VD: 22:00">
                        <div id="end_timeError" class="error-message hidden"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="contactWeight">
                        <i class="fas fa-sort-numeric-down mr-2 text-gray-400"></i>Thứ tự sắp xếp
                    </label>
                    <input type="number" 
                           id="contactWeight" 
                           name="weight" 
                           class="form-input" 
                           placeholder="0"
                           min="0"
                           value="0">
                    <p class="text-xs text-gray-500 mt-2">Số càng nhỏ, hiển thị càng trước</p>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="contactStatus">
                        <i class="fas fa-eye mr-2 text-gray-400"></i>Trạng thái hiển thị
                    </label>
                    <label class="switch">
                        <input type="checkbox" id="contactStatus" name="status" value="1">
                        <span class="switch-slider"></span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Bật để hiển thị thông tin cửa hàng trên website</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeContactModal()" class="btn btn-secondary">
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
    'id' => 'deleteContactModal',
    'title' => 'Xác nhận xóa thông tin cửa hàng',
    'message' => 'Bạn có chắc chắn muốn xóa thông tin cửa hàng "{name}"?',
    'confirmText' => 'Xóa thông tin cửa hàng'
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

// Contact Modal Functions
function openContactModal(contactId = null) {
    const modal = document.getElementById('contactModal');
    const form = document.getElementById('contactForm');
    const title = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitText');
    
    if (!modal || !form || !title || !submitText) {
        console.error('Modal elements not found');
        return;
    }
    
    // Reset form
    form.reset();
    const contactIdInput = document.getElementById('contactId');
    if (contactIdInput) {
        contactIdInput.value = '';
    }
    clearErrors();
    
    if (contactId) {
        // Edit mode
        title.textContent = 'Sửa thông tin cửa hàng';
        submitText.textContent = 'Cập nhật';
        
        // Fetch contact data
        fetch(`/admin/store-information/${contactId}`, {
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
                const idInput = document.getElementById('contactId');
                const nameInput = document.getElementById('contactName');
                const phoneInput = document.getElementById('contactPhone');
                const addressInput = document.getElementById('contactAddress');
                const startTimeInput = document.getElementById('contactStartTime');
                const endTimeInput = document.getElementById('contactEndTime');
                const weightInput = document.getElementById('contactWeight');
                const statusInput = document.getElementById('contactStatus');
                
                if (idInput) idInput.value = data.data.id;
                if (nameInput) nameInput.value = data.data.name || '';
                if (phoneInput) phoneInput.value = data.data.phone || '';
                if (addressInput) addressInput.value = data.data.address || '';
                if (startTimeInput) startTimeInput.value = data.data.strart_time || '';
                if (endTimeInput) endTimeInput.value = data.data.end_time || '';
                if (weightInput) weightInput.value = data.data.weight || 0;
                if (statusInput) statusInput.checked = data.data.status == 1;
            } else {
                alert(data.message || 'Không thể tải dữ liệu thông tin cửa hàng');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải dữ liệu. Vui lòng thử lại.');
        });
    } else {
        // Create mode
        title.textContent = 'Thêm thông tin cửa hàng mới';
        submitText.textContent = 'Lưu';
    }
    
    // Show modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeContactModal() {
    const modal = document.getElementById('contactModal');
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

function saveContact(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    const formData = new FormData(form);
    const contactId = formData.get('id');
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
    const statusInput = document.getElementById('contactStatus');
    if (statusInput && !statusInput.checked) {
        formData.set('status', '0');
    }
    
    // Disable submit button
    submitBtn.disabled = true;
    submitText.innerHTML = '<span class="loading-spinner"></span> Đang lưu...';
    
    const url = contactId 
        ? `/admin/store-information/${contactId}`
        : '/admin/store-information';
    
    const method = contactId ? 'PUT' : 'POST';
    
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
        method: method,
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
            closeContactModal();
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            // Show error
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const fieldName = 'contact' + field.charAt(0).toUpperCase() + field.slice(1);
                    showError(fieldName, data.errors[field][0]);
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
        alert('Có lỗi xảy ra khi lưu thông tin cửa hàng');
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    });
}

// Delete function
function confirmDelete(id, name) {
    if (!confirm(`Bạn có chắc chắn muốn xóa thông tin cửa hàng "${name}"?`)) {
        return;
    }
    
    const deleteUrl = `/admin/store-information/${id}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    if (!csrfToken) {
        alert('Lỗi bảo mật: Không tìm thấy CSRF token');
        return;
    }
    
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
                throw new Error(err.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (typeof showNotification === 'function') {
                showNotification(data.message, 'success');
            } else {
                alert(data.message);
            }
            
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else {
            alert(data.message || 'Có lỗi xảy ra khi xóa thông tin cửa hàng');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi xóa thông tin cửa hàng: ' + error.message);
    });
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('contactModal');
    if (event.target === modal) {
        closeContactModal();
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('contactModal');
        if (modal && modal.classList.contains('show')) {
            closeContactModal();
        }
    }
});
</script>
@endpush


