@extends('admin.layouts.master')

@section('title', 'Chi tiết thương hiệu')

@php
$breadcrumbs = [
    ['label' => 'Thương hiệu', 'url' => route('admin.brands.index')],
    ['label' => $brand->name, 'url' => route('admin.brands.show', $brand->id)]
];
@endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-star text-primary-600 mr-3"></i>
                {{ $brand->name }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">Chi tiết thương hiệu</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                <i class="fas fa-edit mr-2"></i>Sửa
            </a>
            <a href="{{ route('admin.brands.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Thông tin cơ bản</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tên thương hiệu</label>
                        <p class="text-base text-gray-900 font-medium">{{ $brand->name }}</p>
                    </div>
                    
                    @if($brand->alias)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alias (URL)</label>
                        <p class="text-base text-gray-900">
                            <i class="fas fa-link mr-1 text-gray-400"></i>{{ $brand->alias }}
                        </p>
                    </div>
                    @endif
                    
                    @if($brand->content)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Mô tả / Nội dung</label>
                        <div class="text-base text-gray-900 prose max-w-none">
                            {!! $brand->content !!}
                        </div>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Thứ tự</label>
                            <p class="text-base text-gray-900">{{ $brand->weight ?? 0 }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Trạng thái</label>
                            <span class="inline-block px-3 py-1 text-xs font-medium rounded-full {{ $brand->hidden == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $brand->hidden == 1 ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Images -->
            @php
                $images = [];
                if ($brand->url_imgs) {
                    $images = is_string($brand->url_imgs) ? json_decode($brand->url_imgs, true) : $brand->url_imgs;
                    if (!is_array($images)) {
                        $images = [];
                    }
                }
                $brandImages = $brand->images ?? collect();
            @endphp
            
            @if(count($images) > 0 || $brandImages->count() > 0)
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Hình ảnh thương hiệu</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($images as $image)
                        <div class="aspect-square border border-gray-200 rounded-lg overflow-hidden bg-gray-100">
                            <img src="{{ asset('img/brand/' . $image) }}" 
                                 alt="Brand image" 
                                 class="w-full h-full object-contain">
                        </div>
                    @endforeach
                    @foreach($brandImages as $brandImage)
                        <div class="aspect-square border border-gray-200 rounded-lg overflow-hidden bg-gray-100">
                            <img src="{{ $brandImage->getImage() }}" 
                                 alt="Brand image" 
                                 class="w-full h-full object-contain">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Right Column - Logo -->
        <div>
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 sticky top-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Logo</h2>
                
                @if($brand->logo)
                    <div class="aspect-square border-2 border-gray-200 rounded-lg overflow-hidden bg-white p-4">
                        <img src="{{ asset('img/brand/' . $brand->logo) }}" 
                             alt="{{ $brand->name }} logo" 
                             class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="aspect-square border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                        <div class="text-center text-gray-400">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <p class="text-sm">Chưa có logo</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

