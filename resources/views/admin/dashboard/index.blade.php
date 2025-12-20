@extends('admin.layouts.master')

@section('title', 'Dashboard')

@php
$breadcrumbs = [
    ['label' => 'Dashboard']
];
@endphp

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Tổng đơn hàng</p>
                <p class="text-3xl font-bold text-gray-900">1,234</p>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> 12% so với tháng trước
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Doanh thu</p>
                <p class="text-3xl font-bold text-gray-900">₫250M</p>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> 8% so với tháng trước
                </p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Products -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Sản phẩm</p>
                <p class="text-3xl font-bold text-gray-900">5,678</p>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-minus"></i> Không đổi
                </p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-box text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Customers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Khách hàng</p>
                <p class="text-3xl font-bold text-gray-900">9,876</p>
                <p class="text-xs text-green-600 mt-2">
                    <i class="fas fa-arrow-up"></i> 15% so với tháng trước
                </p>
            </div>
            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Revenue Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Doanh thu theo tháng</h2>
            <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                <option>12 tháng</option>
                <option>6 tháng</option>
                <option>3 tháng</option>
            </select>
        </div>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <p class="text-gray-400">Biểu đồ doanh thu sẽ được hiển thị ở đây</p>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Hoạt động gần đây</h2>
        <div class="space-y-4">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-shopping-cart text-blue-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">Đơn hàng mới</p>
                    <p class="text-xs text-gray-500">#12345 vừa được đặt</p>
                    <p class="text-xs text-gray-400 mt-1">5 phút trước</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-green-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">Khách hàng mới</p>
                    <p class="text-xs text-gray-500">Nguyễn Văn A đã đăng ký</p>
                    <p class="text-xs text-gray-400 mt-1">15 phút trước</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-box text-purple-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">Sản phẩm mới</p>
                    <p class="text-xs text-gray-500">Sản phẩm đã được thêm</p>
                    <p class="text-xs text-gray-400 mt-1">1 giờ trước</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Đơn hàng gần đây</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
            Xem tất cả
            <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#12345</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Nguyễn Văn A</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₫1,250,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Đang xử lý</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">25/12/2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-primary-600 hover:text-primary-900">Xem</a>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#12344</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Trần Thị B</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₫850,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Hoàn thành</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">24/12/2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-primary-600 hover:text-primary-900">Xem</a>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#12343</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Lê Văn C</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₫2,100,000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Đang giao</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">24/12/2024</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="#" class="text-primary-600 hover:text-primary-900">Xem</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

