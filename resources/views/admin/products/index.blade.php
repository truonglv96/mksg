@extends('admin.layouts.master')

@section('title', 'Quản lý sản phẩm')

@php
$breadcrumbs = [
    ['label' => 'Sản phẩm', 'url' => route('admin.products.index')]
];
@endphp

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quản lý sản phẩm</h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý danh sách sản phẩm của bạn</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Thêm sản phẩm
        </a>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
            <div class="relative">
                <input type="text" 
                       placeholder="Tìm kiếm sản phẩm..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <div>
            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                <option>Tất cả danh mục</option>
                <option>Đồ chơi</option>
                <option>Quần áo</option>
                <option>Phụ kiện</option>
            </select>
        </div>
        <div>
            <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                <option>Tất cả trạng thái</option>
                <option>Đang bán</option>
                <option>Hết hàng</option>
                <option>Ẩn</option>
            </select>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Danh sách sản phẩm</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên sản phẩm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn kho</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <img src="https://via.placeholder.com/60" alt="Product" class="w-15 h-15 object-cover rounded">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">Sản phẩm mẫu 1</div>
                <div class="text-sm text-gray-500">SKU: SP001</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Đồ chơi</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₫250,000</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">50</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Đang bán</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end space-x-2">
                    <a href="#" class="text-primary-600 hover:text-primary-900">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="#" class="text-green-600 hover:text-green-900">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button onclick="confirmDelete(1)" class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <img src="https://via.placeholder.com/60" alt="Product" class="w-15 h-15 object-cover rounded">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">Sản phẩm mẫu 2</div>
                <div class="text-sm text-gray-500">SKU: SP002</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Quần áo</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₫150,000</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">0</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Hết hàng</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end space-x-2">
                    <a href="#" class="text-primary-600 hover:text-primary-900">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="#" class="text-green-600 hover:text-green-900">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button onclick="confirmDelete(2)" class="text-red-600 hover:text-red-900">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-500">
            Hiển thị <span class="font-medium">1</span> đến <span class="font-medium">10</span> của <span class="font-medium">100</span> kết quả
        </div>
        <div class="flex space-x-2">
            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50" disabled>
                Trước
            </button>
            <button class="px-3 py-1 bg-primary-600 text-white rounded-lg text-sm">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">3</button>
            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                Sau
            </button>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        // Handle delete
        console.log('Delete product:', id);
    }
}
</script>
@endsection

