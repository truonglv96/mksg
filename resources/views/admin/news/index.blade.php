@extends('admin.layouts.master')

@section('title', 'Quản lý tin tức')

@php
$breadcrumbs = [
    ['label' => 'Tin tức', 'url' => route('admin.news.index')]
];
@endphp

@push('styles')
<style>
    .news-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    
    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
</style>
@endpush

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 fade-in">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium">Tổng tin tức</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalNews ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-newspaper text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Đã xuất bản</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($publishedNews ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Bản nháp</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($draftNews ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-file-alt text-2xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Tổng lượt xem</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($totalViews ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                <i class="fas fa-eye text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-newspaper mr-3" style="color: #2563eb;"></i>
                Quản lý tin tức
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và theo dõi tất cả tin tức của bạn</p>
        </div>
        <div class="flex items-center gap-3">
            <button id="viewToggle" class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-th view-icon" data-view="grid"></i>
                <i class="fas fa-list view-icon hidden" data-view="list"></i>
            </button>
            <a href="{{ route('admin.news.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 font-semibold">
                <i class="fas fa-plus mr-2"></i>
                <span>Thêm tin tức</span>
            </a>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <form method="GET" action="{{ route('admin.news.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search -->
        <div class="md:col-span-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-gray-400"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       name="search"
                       value="{{ request('search', '') }}"
                       placeholder="Tìm theo tên, alias, mô tả..." 
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        
        <!-- Status Filter -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-filter mr-2 text-gray-400"></i>Trạng thái
            </label>
            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Tất cả</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đã xuất bản</option>
                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Bản nháp</option>
            </select>
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
                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Lượt xem</option>
                <option value="weight" {{ request('sort') == 'weight' ? 'selected' : '' }}>Thứ tự</option>
            </select>
        </div>
        
        <!-- Actions -->
        <div class="md:col-span-2 flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 text-white rounded-lg hover:from-red-700 hover:to-blue-700 transition-colors" style="background: linear-gradient(to right, #dc2626, #2563eb);">
                <i class="fas fa-search mr-2"></i>Tìm
            </button>
            <a href="{{ route('admin.news.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- News Grid/Table -->
<div id="newsContainer">
    @if(isset($news) && $news->count() > 0)
        <!-- Grid View (Default) -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($news as $item)
                <div class="news-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @if($item->url_img)
                        <div class="w-full">
                            <img src="{{ asset('img/news/' . $item->url_img) }}" 
                                 alt="{{ $item->name }}" 
                                 class="w-full h-48 object-cover"
                                 onerror="this.style.display='none';">
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $item->hidden == 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $item->hidden == 0 ? 'Đã xuất bản' : 'Bản nháp' }}
                            </span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">
                                #{{ $item->weight ?? 0 }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $item->name }}</h3>
                        
                        @if($item->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit(strip_tags($item->description), 80) }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-eye"></i>
                                <span>{{ number_format($item->views ?? 0) }}</span>
                            </div>
                            <div>
                                {{ $item->created_at ? $item->created_at->format('d/m/Y') : '—' }}
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.news.edit', $item->id) }}" 
                               class="flex-1 px-3 py-2 text-center text-sm bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                                <i class="fas fa-edit mr-1"></i>Sửa
                            </a>
                            <form action="{{ route('admin.news.destroy', $item->id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin tức này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-red-200 hover:border-red-300">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
                                Tên tin tức
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lượt xem
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thứ tự
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
                        @foreach($news as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-16 h-16 flex items-center justify-center bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                        @if($item->url_img)
                                            <img src="{{ asset('img/news/' . $item->url_img) }}" 
                                                 alt="{{ $item->name }}" 
                                                 class="w-full h-full object-cover"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <i class="fas fa-image text-gray-400 text-xl" style="display: none;"></i>
                                        @else
                                            <i class="fas fa-image text-gray-400 text-xl"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->name }}</div>
                                    @if($item->alias)
                                        <div class="text-xs text-gray-500 mt-1">{{ $item->alias }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $item->hidden == 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ $item->hidden == 0 ? 'Đã xuất bản' : 'Bản nháp' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <i class="fas fa-eye"></i>
                                        <span>{{ number_format($item->views ?? 0) }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-700">
                                        {{ $item->weight ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        {{ $item->created_at ? $item->created_at->format('d/m/Y') : '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.news.edit', $item->id) }}" 
                                           class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-transparent hover:border-red-200" 
                                           title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.news.destroy', $item->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin tức này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                                    title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if(isset($news) && $news->hasPages())
            <div class="mt-6">
                {{ $news->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Chưa có tin tức nào</h3>
            <p class="text-gray-500 mb-6">Hãy bắt đầu bằng cách tạo tin tức đầu tiên của bạn</p>
            <a href="{{ route('admin.news.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 text-white rounded-lg hover:from-red-700 hover:to-blue-700 transition-colors" style="background: linear-gradient(to right, #dc2626, #2563eb); color: white; display: inline-flex; align-items: center; padding: 0.625rem 1.25rem; border-radius: 0.5rem; transition: all 0.2s;">
                <i class="fas fa-plus mr-2"></i>
                Thêm tin tức
            </a>
        </div>
    @endif
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
</script>
@endpush

