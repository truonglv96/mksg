@extends('admin.layouts.master')

@section('title', 'Quản lý chính sách')

@php
$breadcrumbs = [
    ['label' => 'Chính sách', 'url' => route('admin.policies.index')]
];
@endphp

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 fade-in">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Tổng chính sách</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalPolicies ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-file-alt text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Đang hiển thị</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($activePolicies ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-100 text-sm font-medium">Đang ẩn</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($inactivePolicies ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-eye-slash text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-file-signature mr-3" style="color: #2563eb;"></i>
                Quản lý chính sách
            </h1>
            <p class="mt-1 text-sm text-gray-500">Tạo và quản lý các trang chính sách hiển thị ở footer</p>
        </div>
        <a href="{{ route('admin.policies.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
            <i class="fas fa-plus mr-2"></i>
            <span>Thêm chính sách</span>
        </a>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <form method="GET" action="{{ route('admin.policies.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <div class="md:col-span-7">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-gray-400"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       name="search"
                       value="{{ request('search', '') }}"
                       placeholder="Tìm theo tên..." 
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-filter mr-2 text-gray-400"></i>Trạng thái
            </label>
            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Tất cả</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-sort mr-2 text-gray-400"></i>Sắp xếp
            </label>
            <select name="sort" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="weight" {{ request('sort') == 'weight' ? 'selected' : '' }}>Thứ tự</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>Mới cập nhật</option>
                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Mới tạo</option>
            </select>
        </div>
        <div class="md:col-span-1 flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 text-white rounded-lg hover:from-red-700 hover:to-blue-700 transition-colors" style="background: linear-gradient(to right, #dc2626, #2563eb);">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('admin.policies.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Policies Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden fade-in">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thứ tự</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($policies as $policy)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $policy->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">{{ $policy->link }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                #{{ $policy->weight ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $policy->status == \App\Models\Page::IS_ACTIVE ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $policy->status == \App\Models\Page::IS_ACTIVE ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.policies.edit', $policy->id) }}" 
                                   class="px-3 py-2 text-sm bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        onclick="confirmDelete({{ $policy->id }}, '{{ addslashes($policy->name) }}')"
                                        class="px-3 py-2 text-sm bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                            Chưa có chính sách nào. Hãy tạo mới để hiển thị ở footer.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($policies->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $policies->links() }}
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
@include('admin.helpers.delete-modal', [
    'id' => 'deleteModal',
    'title' => 'Xác nhận xóa chính sách',
    'message' => 'Bạn có chắc chắn muốn xóa chính sách "{name}"?',
    'confirmText' => 'Xóa chính sách'
])
@endsection
