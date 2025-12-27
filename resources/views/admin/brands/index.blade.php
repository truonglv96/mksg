@extends('admin.layouts.master')

@section('title', 'Quản lý thương hiệu')

@php
$breadcrumbs = [
    ['label' => 'Thương hiệu', 'url' => route('admin.brands.index')]
];
@endphp

@push('styles')
<style>
    .brand-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .brand-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    .brand-logo {
        width: 100%;
        height: 200px;
        object-fit: contain;
        background: #f9fafb;
        border-radius: 8px;
        padding: 16px;
    }
    
    .view-grid .brand-card {
        display: block;
    }
    
    .view-list .brand-card {
        display: flex;
        flex-direction: row;
    }
    
    .view-list .brand-logo-wrapper {
        width: 150px;
        flex-shrink: 0;
    }
    
    .view-list .brand-logo {
        height: 120px;
    }
    
    .view-list .brand-info {
        flex: 1;
        padding-left: 20px;
    }
</style>
@endpush

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 fade-in">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Tổng thương hiệu</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalBrands ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-star text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Đang hiển thị</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($activeBrands ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Đã ẩn</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($hiddenBrands ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-eye-slash text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Tổng ảnh</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalImages ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-images text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-star text-primary-600 mr-3"></i>
                Quản lý thương hiệu
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và theo dõi tất cả thương hiệu</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="viewToggle" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-th view-icon" data-view="grid"></i>
                <i class="fas fa-list view-icon hidden" data-view="list"></i>
            </button>
            <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5" style="background: linear-gradient(to right, #0284c7, #0369a1); display: inline-flex; align-items: center; padding: 0.625rem 1.25rem; color: white; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); transition: all 0.2s;">
                <i class="fas fa-plus mr-2"></i>
                <span class="font-medium">Thêm thương hiệu</span>
            </a>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <form method="GET" action="{{ route('admin.brands.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search -->
        <div class="md:col-span-5">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-gray-400"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       name="search"
                       value="{{ request('search', '') }}"
                       placeholder="Tìm theo tên, alias..." 
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        
        <!-- Status Filter -->
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-filter mr-2 text-gray-400"></i>Trạng thái
            </label>
            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Tất cả</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang hiển thị</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Đã ẩn</option>
            </select>
        </div>
        
        <!-- Sort -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-sort mr-2 text-gray-400"></i>Sắp xếp
            </label>
            <select name="sort" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="weight" {{ request('sort') == 'weight' ? 'selected' : '' }}>Thứ tự</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
            </select>
        </div>
        
        <!-- Actions -->
        <div class="md:col-span-2 flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-search mr-2"></i>Tìm
            </button>
            <a href="{{ route('admin.brands.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Brands Grid/Table -->
<div id="brandsContainer">
    @if(isset($brands) && $brands->count() > 0)
        <!-- Grid View (Default) -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($brands as $brand)
                <div class="brand-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="brand-logo-wrapper">
                        <img src="{{ $brand->logo ? asset('img/brand/' . $brand->logo) : asset('img/placeholder.png') }}" 
                             alt="{{ $brand->name }}" 
                             class="brand-logo"
                             onerror="this.src='{{ asset('img/placeholder.png') }}'">
                    </div>
                    
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $brand->name }}</h3>
                        
                        @if($brand->alias)
                            <p class="text-sm text-gray-500 mb-3">
                                <i class="fas fa-link mr-1"></i>{{ $brand->alias }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $brand->hidden == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $brand->hidden == 1 ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-sort-numeric-down mr-1"></i>{{ $brand->weight ?? 0 }}
                            </span>
                        </div>
                        
                        @if($brand->url_imgs)
                            @php
                                $images = is_string($brand->url_imgs) ? json_decode($brand->url_imgs, true) : $brand->url_imgs;
                                $imageCount = is_array($images) ? count($images) : 1;
                            @endphp
                            <p class="text-xs text-gray-500 mb-4">
                                <i class="fas fa-images mr-1"></i>{{ $imageCount }} ảnh
                            </p>
                        @endif
                        
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                               class="flex-1 px-3 py-2 text-center text-sm text-green-600 hover:bg-green-50 rounded-lg transition-colors border border-green-200 hover:border-green-300">
                                <i class="fas fa-edit mr-1"></i>Sửa
                            </a>
                            <a href="{{ route('admin.brands.show', $brand->id) }}" 
                               class="flex-1 px-3 py-2 text-center text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-blue-200 hover:border-blue-300">
                                <i class="fas fa-eye mr-1"></i>Xem
                            </a>
                            <button onclick="confirmDelete({{ $brand->id }}, '{{ addslashes($brand->name) }}')" 
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
                                Logo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tên thương hiệu
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Alias
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hình ảnh
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thứ tự
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($brands as $brand)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        @if($brand->logo)
                                            <img src="{{ asset('img/brand/' . $brand->logo) }}" 
                                                 alt="{{ $brand->name }}" 
                                                 class="w-full h-full object-contain"
                                                 onerror="this.src='{{ asset('img/placeholder.png') }}'">
                                        @else
                                            <i class="fas fa-image text-gray-400 text-xl"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $brand->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500">
                                        <i class="fas fa-link mr-1"></i>{{ $brand->alias ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $imageCount = 0;
                                        if ($brand->url_imgs) {
                                            $images = is_string($brand->url_imgs) ? json_decode($brand->url_imgs, true) : $brand->url_imgs;
                                            $imageCount = is_array($images) ? count($images) : ($images ? 1 : 0);
                                        }
                                        if (isset($brand->images)) {
                                            $imageCount += $brand->images->count();
                                        }
                                    @endphp
                                    <span class="text-sm text-gray-500">
                                        <i class="fas fa-images mr-1"></i>{{ $imageCount }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500">{{ $brand->weight ?? 0 }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $brand->hidden == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $brand->hidden == 1 ? 'Hiển thị' : 'Ẩn' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                           class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" 
                                           title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.brands.show', $brand->id) }}" 
                                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                           title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="confirmDelete({{ $brand->id }}, '{{ addslashes($brand->name) }}')" 
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
        @if(isset($brands) && $brands->hasPages())
            <div class="mt-6">
                {{ $brands->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có thương hiệu nào</h3>
            <p class="text-gray-500 mb-6">Hãy bắt đầu bằng cách tạo thương hiệu đầu tiên của bạn</p>
            <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Thêm thương hiệu
            </a>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
@include('admin.helpers.delete-modal', [
    'id' => 'deleteBrandModal',
    'title' => 'Xác nhận xóa thương hiệu',
    'message' => 'Bạn có chắc chắn muốn xóa thương hiệu "{name}"?',
    'confirmText' => 'Xóa thương hiệu'
])
@endsection

@push('scripts')
<script>
let deleteBrandId = null;
let deleteBrandUrl = null;

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
                // Switch to Table
                gridView.classList.add('hidden');
                tableView.classList.remove('hidden');
            } else {
                // Switch to Grid
                tableView.classList.add('hidden');
                gridView.classList.remove('hidden');
            }
            
            viewIcons.forEach(icon => {
                icon.classList.toggle('hidden');
            });
        });
    }
});

function confirmDelete(id, name) {
    deleteBrandId = id;
    deleteBrandUrl = '{{ route("admin.brands.destroy", ":id") }}'.replace(':id', id);
    openDeleteModal('deleteBrandModal', deleteBrandUrl, name);
}
</script>
@endpush

