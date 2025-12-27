@extends('admin.layouts.master')

@section('title', 'Thêm sản phẩm mới')

@php
$breadcrumbs = [
    ['label' => 'Sản phẩm', 'url' => route('admin.products.index')],
    ['label' => 'Thêm mới', 'url' => route('admin.products.create')]
];
@endphp

@push('styles')
@include('admin.helpers.product-styles')
@endpush

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle text-primary-600 mr-3"></i>
                Thêm sản phẩm mới
            </h1>
            <p class="mt-1 text-sm text-gray-500">Điền thông tin để tạo sản phẩm mới</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm" novalidate>
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                        Thông tin cơ bản
                    </h2>
                    
                    <div class="space-y-4">
                        @include('admin.helpers.form-input', [
                            'name' => 'name',
                            'label' => 'Tên sản phẩm',
                            'type' => 'text',
                            'value' => old('name'),
                            'placeholder' => 'Nhập tên sản phẩm',
                            'required' => true
                        ])

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @include('admin.helpers.form-input', [
                                'name' => 'alias',
                                'label' => 'Alias (URL)',
                                'type' => 'text',
                                'value' => old('alias'),
                                'placeholder' => 'alias-san-pham (tự động nếu để trống)'
                            ])
                            @include('admin.helpers.form-input', [
                                'name' => 'code_sp',
                                'label' => 'Mã sản phẩm',
                                'type' => 'text',
                                'value' => old('code_sp'),
                                'placeholder' => 'Nhập mã sản phẩm'
                            ])
                        </div>

                        <!-- Description -->
                        @include('admin.helpers.content-section', [
                            'name' => 'description',
                            'label' => 'Mô tả sản phẩm',
                            'defaultName' => 'Mô tả sản phẩm',
                            'value' => old('description'),
                            'textareaId' => 'description'
                        ])
                    </div>
                </div>

                <!-- Content Sections -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-file-alt text-primary-600 mr-2"></i>
                        Nội dung chi tiết
                    </h2>
                    
                    <div class="space-y-6">
                        <!-- Content -->
                        @include('admin.helpers.content-section', [
                            'name' => 'content',
                            'label' => 'Nội dung',
                            'defaultName' => 'Nội dung',
                            'value' => old('content'),
                            'textareaId' => 'content'
                        ])

                        <!-- Tech -->
                        @include('admin.helpers.content-section', [
                            'name' => 'tech',
                            'label' => 'Thông số kỹ thuật',
                            'defaultName' => 'Thông số kỹ thuật',
                            'value' => old('tech'),
                            'textareaId' => 'tech',
                            'hasTemplate' => true,
                            'templateFunction' => 'insertDefaultTechContent'
                        ])

                        <!-- Service -->
                        @include('admin.helpers.content-section', [
                            'name' => 'service',
                            'label' => 'Tư vấn sử dụng',
                            'defaultName' => 'Tư vấn sử dụng',
                            'value' => old('service'),
                            'textareaId' => 'service',
                            'hasTemplate' => true,
                            'templateFunction' => 'insertDefaultServiceContent'
                        ])

                        <!-- Tutorial -->
                        @include('admin.helpers.content-section', [
                            'name' => 'tutorial',
                            'label' => 'Hướng dẫn mua hàng',
                            'defaultName' => 'Hướng dẫn mua hàng',
                            'value' => old('tutorial'),
                            'textareaId' => 'tutorial',
                            'hasTemplate' => true,
                            'templateFunction' => 'insertDefaultTutorialContent'
                        ])

                        <!-- Address -->
                        @include('admin.helpers.content-section', [
                            'name' => 'address',
                            'fieldName' => 'address_sale',
                            'label' => 'Địa chỉ mua hàng',
                            'defaultName' => 'Địa chỉ mua hàng',
                            'value' => old('address'),
                            'textareaId' => 'address',
                            'hasTemplate' => true,
                            'templateFunction' => 'insertDefaultAddressContent'
                        ])

                        <!-- Opening Hours -->
                        @include('admin.helpers.content-section', [
                            'name' => 'opening_hours',
                            'fieldName' => 'opening_hours',
                            'label' => 'Thời gian mở cửa',
                            'defaultName' => 'Thời gian mở cửa',
                            'value' => old('opening_hours'),
                            'textareaId' => 'opening_hours',
                            'hasTemplate' => true,
                            'templateFunction' => 'insertDefaultOpeningHoursContent'
                        ])

                        <!-- Keywords (kw) -->
                        @include('admin.helpers.form-input', [
                            'name' => 'kw',
                            'label' => 'Từ khóa (Keywords)',
                            'type' => 'text',
                            'value' => old('kw'),
                            'placeholder' => 'Nhập từ khóa, phân cách bằng dấu phẩy',
                            'helpText' => 'Ví dụ: kính mắt, gọng kính, mắt kính'
                        ])

                        <!-- Meta Description -->
                        @include('admin.helpers.form-textarea', [
                            'name' => 'meta_des',
                            'label' => 'Meta Description',
                            'value' => old('meta_des'),
                            'placeholder' => 'Nhập mô tả ngắn cho SEO (tối đa 160 ký tự)',
                            'rows' => 3,
                            'helpText' => 'Mô tả ngắn gọn về sản phẩm để tối ưu SEO'
                        ])
                    </div>
                </div>

            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Categories -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tags text-primary-600 mr-2"></i>
                        Danh mục
                    </h2>
                    
                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-white">
                        @foreach($categories as $cat)
                            <div class="category-checkbox-item level-{{ $cat['level'] }}">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="categories[]" 
                                           value="{{ $cat['id'] }}"
                                           {{ in_array($cat['id'], old('categories', [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                    <span class="ml-2">{{ $cat['name'] }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('categories')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color Type -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Loại Màu Sắc
                                </label>
                        <div class="relative">
                            <select name="type_color" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-smooth bg-white appearance-none cursor-pointer @error('type_color') border-red-500 @enderror">
                                <option value="0" {{ old('type_color', 0) == 0 ? 'selected' : '' }}>Màu Sắc</option>
                                <option value="1" {{ old('type_color') == 1 ? 'selected' : '' }}>Chiết Suất</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                    </div>
                        @error('type_color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

                <!-- Pricing & Details -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-dollar-sign text-primary-600 mr-2"></i>
                        Giá bán & Thông tin chi tiết
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Giá bán (₫)
                            </label>
                            <input type="number" 
                                   name="price_sale" 
                                   value="{{ old('price_sale') }}"
                                   min="0"
                                   step="1000"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth @error('price_sale') border-red-500 @enderror"
                                   placeholder="0">
                            @error('price_sale')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Giá gốc (₫)
                            </label>
                            <input type="number" 
                                   name="price" 
                                   value="{{ old('price') }}"
                                   min="0"
                                   step="1000"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth @error('price') border-red-500 @enderror"
                                   placeholder="0">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hình thức bán
                            </label>
                            <select name="type_sale" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white @error('type_sale') border-red-500 @enderror">
                                <option value="0" {{ old('type_sale', 0) == 0 ? 'selected' : '' }}>Tại Shop & Online</option>
                                <option value="1" {{ old('type_sale') == 1 ? 'selected' : '' }}>Tại Shop</option>
                                <option value="2" {{ old('type_sale') == 2 ? 'selected' : '' }}>Online</option>
                            </select>
                            @error('type_sale')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Giới tính
                            </label>
                            <select name="gender" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white @error('gender') border-red-500 @enderror">
                                <option value="all" {{ old('gender', 'all') == 'all' ? 'selected' : '' }}>Tất cả</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="children" {{ old('gender') == 'children' ? 'selected' : '' }}>Trẻ em</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Đơn vị tính
                            </label>
                            <select name="unit" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white @error('unit') border-red-500 @enderror">
                                <option value="">{{ old('unit') ? '' : 'Chọn đơn vị tính' }}</option>
                                <option value="Cặp" {{ old('unit') == 'Cặp' ? 'selected' : '' }}>Cặp</option>
                                <option value="Chiếc" {{ old('unit') == 'Chiếc' ? 'selected' : '' }}>Chiếc</option>
                                <option value="Bộ" {{ old('unit') == 'Bộ' ? 'selected' : '' }}>Bộ</option>
                                <option value="Cái" {{ old('unit') == 'Cái' ? 'selected' : '' }}>Cái</option>
                                <option value="Lọ" {{ old('unit') == 'Lọ' ? 'selected' : '' }}>Lọ</option>
                                <option value="Chai" {{ old('unit') == 'Chai' ? 'selected' : '' }}>Chai</option>
                                <option value="Hộp" {{ old('unit') == 'Hộp' ? 'selected' : '' }}>Hộp</option>
                            </select>
                            @error('unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Thương hiệu
                            </label>
                            <select name="brand_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth @error('brand_id') border-red-500 @enderror">
                                <option value="">Chọn thương hiệu</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Chất liệu
                            </label>
                            <select name="material_id" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth @error('material_id') border-red-500 @enderror">
                                <option value="">Chọn chất liệu</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('material_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-images text-primary-600 mr-2"></i>
                        Hình ảnh sản phẩm
                    </h2>
                    
                    <div>
                        <!-- Drag & Drop Zone -->
                        <div class="relative">
                            <div class="flex flex-col items-center justify-center w-full min-h-[160px] py-8 px-4 border-2 border-dashed border-gray-300 rounded-xl hover:border-primary-500 transition-all duration-300 cursor-pointer bg-white group" 
                                 id="multipleImagesContainer"
                                 onclick="document.getElementById('multipleImagesInput').click()"
                                 ondrop="handleDrop(event)" 
                                 ondragover="handleDragOver(event)" 
                                 ondragleave="handleDragLeave(event)">
                                <div class="text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-50 rounded-full mb-4 group-hover:bg-primary-100 group-hover:scale-110 transition-all duration-300">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-primary-600"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-700 mb-2">
                                        Kéo thả hình ảnh vào đây hoặc 
                                        <span class="text-primary-600 hover:text-primary-700 underline">click để chọn</span>
                                    </p>
                                    <p class="text-xs text-gray-500 mb-1">
                                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                        Hỗ trợ JPG, PNG, GIF (tối đa 2MB mỗi hình)
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        Có thể chọn nhiều hình cùng lúc
                                    </p>
                                </div>
                            </div>

                            <input type="file" 
                                   name="images[]" 
                                   id="multipleImagesInput"
                                   accept="image/*"
                                   multiple
                                   class="hidden"
                                   onchange="handleMultipleImages(this)">
                        </div>
                        
                        <!-- Preview Selected Images -->
                        <div id="selectedImagesPreview" class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4">
                            <!-- Images will be added here dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cog text-primary-600 mr-2"></i>
                        Cài đặt
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="hidden" 
                                       value="0"
                                       {{ old('hidden', 1) == 0 ? 'checked' : '' }}
                                       class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Ẩn sản phẩm</span>
                            </label>
                            <input type="hidden" name="hidden" value="1" id="hidden_default">
                            <p class="mt-1 text-xs text-gray-500">Sản phẩm sẽ không hiển thị trên website</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Thứ tự hiển thị
                            </label>
                            <input type="number" 
                                   name="weight" 
                                   value="{{ old('weight', 0) }}"
                                   min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth"
                                   placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Số nhỏ hơn sẽ hiển thị trước</p>
                        </div>
                    </div>
                </div>

                <!-- Product Sale Prices -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tag text-primary-600 mr-2"></i>
                        Giá Sale Sản Phẩm
                    </h2>
                    
                    <div id="salePricesContainer" class="space-y-3">
                        <!-- Default row -->
                        <div class="sale-price-row grid grid-cols-1 gap-4 bg-white p-4 rounded-lg border border-gray-200">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Danh Mục
                                </label>
                                <select name="sale_prices[0][category1]" 
                                        class="category1-select w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white">
                                    <option value="">Tất cả</option>
                                    @foreach($categories as $cat)
                                        @if($cat['level'] == 0)
                                            <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Danh Mục
                                </label>
                                <select name="sale_prices[0][category2]" 
                                        class="category2-select w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white">
                                    <option value="">Tất cả</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Giá Giảm (VNĐ):
                                </label>
                                <div class="flex items-center gap-2">
                                    <input type="number" 
                                           name="sale_prices[0][discount_price]" 
                                           value=""
                                           min="0"
                                           step="1000"
                                           placeholder="0"
                                           class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth">
                                    <button type="button" 
                                            onclick="removeSalePriceRow(this)"
                                            class="hidden remove-row-btn p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" 
                            onclick="addSalePriceRow()"
                            class="mt-4 w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-plus"></i>
                        <span>Thêm dòng mới</span>
                    </button>
                </div>

                <!-- Combo Selection -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-layer-group text-primary-600 mr-2"></i>
                        Chọn Combo
                    </h2>
                    
                    <div id="comboContainer" class="space-y-3">
                        <!-- Default row -->
                        <div class="combo-row bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tên Combo <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-start gap-2">
                                        <input type="text" 
                                               name="combos[0][name]" 
                                               value=""
                                               placeholder="Nhập tên combo"
                                               class="flex-1 combo-name w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth">
                                        <button type="button" 
                                                onclick="removeComboRow(this)"
                                                class="hidden remove-combo-btn p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                            <i class="fas fa-times text-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Giá (VNĐ)
                                        </label>
                                        <input type="number" 
                                               name="combos[0][price]" 
                                               value=""
                                               min="0"
                                               step="1000"
                                               placeholder="0"
                                               class="combo-price w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Thứ tự
                                        </label>
                                        <input type="number" 
                                               name="combos[0][weight]" 
                                               value="0"
                                               min="0"
                                               placeholder="0"
                                               class="combo-weight w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Mô tả
                                    </label>
                                    <textarea name="combos[0][description]" 
                                              rows="2"
                                              placeholder="Nhập mô tả combo (tùy chọn)"
                                              class="combo-description w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" 
                            onclick="addComboRow()"
                            class="mt-4 w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-plus-circle"></i>
                        <span>Thêm Combo</span>
                    </button>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-medium"
                                style="background: linear-gradient(to right, #0284c7, #0369a1); color: white; padding: 0.75rem 1rem; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); transition: all 0.2s; font-weight: 500;">
                            <i class="fas fa-save mr-2"></i>Lưu sản phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" 
                           class="block w-full px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-smooth text-center font-medium">
                            <i class="fas fa-times mr-2"></i>Hủy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
{{-- Include CKEditor Component --}}
@include('admin.components.ckeditor', [
    'editorIds' => ['description', 'content', 'tech', 'service', 'tutorial', 'address', 'opening_hours']
])

<!-- Color Selector Modal -->
<div id="colorSelectorModal" class="color-selector-modal" onclick="closeColorSelectorOnBackdrop(event)">
    <div class="color-selector-content" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Chọn màu cho hình ảnh</h3>
            <button type="button" onclick="closeColorSelector()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="color-grid" id="colorGrid">
            <!-- Colors will be populated here -->
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <button type="button" onclick="clearColorSelection()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                Bỏ chọn
            </button>
            <button type="button" onclick="confirmColorSelection()" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 rounded-lg transition-all transform hover:scale-105">
                Xác nhận
            </button>
        </div>
    </div>
</div>

<script>
// Categories data for filtering
const allCategories = @json($categories);
// Colors data from PHP
const colorsData = @json($colors);
const colorImageBasePath = '{{ asset("img/color") }}/';

// Store selected images
let selectedImages = [];

// Function to insert default tech content into CKEditor
function insertDefaultTechContent() {
    try {
        // Check if CKEditor is loaded
        if (typeof CKEDITOR === 'undefined') {
            alert('CKEditor chưa được tải. Vui lòng đợi vài giây rồi thử lại.');
            return;
        }
        
        // Try to get editor instance directly from CKEDITOR
        let techEditor = null;
        
        // Method 1: Try window.ckEditors first
        if (typeof window.ckEditors !== 'undefined' && window.ckEditors['tech']) {
            techEditor = window.ckEditors['tech'];
        } 
        // Method 2: Try CKEDITOR.instances
        else if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['tech']) {
            techEditor = CKEDITOR.instances['tech'];
        }
        // Method 3: Wait and retry
        else {
            // Wait a bit and retry
            setTimeout(function() {
                if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['tech']) {
                    techEditor = CKEDITOR.instances['tech'];
                    insertContent(techEditor);
                } else {
                    alert('CKEditor chưa sẵn sàng. Vui lòng đợi vài giây rồi thử lại.');
                }
            }, 500);
            return;
        }
        
        insertContent(techEditor);
    } catch (error) {
        console.error('Error inserting default tech content:', error);
        alert('Có lỗi xảy ra khi thêm nội dung mặc định: ' + error.message);
    }
}

// Helper function to insert content
function insertContent(techEditor) {
    if (!techEditor || typeof techEditor.getData !== 'function') {
        alert('CKEditor instance không hợp lệ.');
        return;
    }
    
    // Default HTML content
    const defaultContent = '<p><img alt="" src="https://matkinhsaigon.com.vn/public/upload/images/Size_Gọng/Size-gong-kinh.png" /></p>\n\n<p><span style="font-size:12pt"><span style="color:#000000"><span style="background-color:#ffffff">1. Chiều d&agrave;i tr&ograve;ng k&iacute;nh : 56mm&nbsp;</span></span></span></p>\n\n<p><span style="font-size:12pt"><span><span style="color:#000000"><span style="background-color:#ffffff">2. Chiều d&agrave;i cầu k&iacute;nh : 17mm</span></span></span></span></p>\n\n<p><span style="font-size:12pt"><span><span style="color:#000000"><span style="background-color:#ffffff">3. Chiều d&agrave;i c&agrave;ng k&iacute;nh : 145mm</span></span></span></span></p>\n\n<p><span style="font-size:12pt"><span><span style="color:#000000"><span style="background-color:#ffffff">4. Chiều cao tr&ograve;ng k&iacute;nh&nbsp; : 38mm</span></span></span></span></p>\n\n<p><span style="font-size:12pt"><span><span style="color:#000000"><span style="background-color:#ffffff">5. Chiều d&agrave;i gọng k&iacute;nh : 140mm</span></span></span></span></p>\n\n<p>&nbsp;</p>';
    
    // Get current content
    const currentContent = techEditor.getData();
    
    // If editor already has content, append at the end, otherwise replace
    if (currentContent && currentContent.trim() !== '') {
        // Append with a line break
        techEditor.setData(currentContent + '<p>&nbsp;</p>' + defaultContent);
    } else {
        // Replace empty content
        techEditor.setData(defaultContent);
    }
    
    // Focus the editor
    techEditor.focus();
}

// Function to insert default service content into CKEditor
function insertDefaultServiceContent() {
    try {
        // Check if CKEditor is loaded
        if (typeof CKEDITOR === 'undefined') {
            alert('CKEditor chưa được tải. Vui lòng đợi vài giây rồi thử lại.');
            return;
        }
        
        // Try to get editor instance directly from CKEDITOR
        let serviceEditor = null;
        
        // Method 1: Try window.ckEditors first
        if (typeof window.ckEditors !== 'undefined' && window.ckEditors['service']) {
            serviceEditor = window.ckEditors['service'];
        } 
        // Method 2: Try CKEDITOR.instances
        else if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['service']) {
            serviceEditor = CKEDITOR.instances['service'];
        }
        // Method 3: Wait and retry
        else {
            // Wait a bit and retry
            setTimeout(function() {
                if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['service']) {
                    serviceEditor = CKEDITOR.instances['service'];
                    insertServiceContent(serviceEditor);
                } else {
                    alert('CKEditor chưa sẵn sàng. Vui lòng đợi vài giây rồi thử lại.');
                }
            }, 500);
            return;
        }
        
        insertServiceContent(serviceEditor);
    } catch (error) {
        console.error('Error inserting default service content:', error);
        alert('Có lỗi xảy ra khi thêm nội dung mặc định: ' + error.message);
    }
}

// Helper function to insert service content
function insertServiceContent(serviceEditor) {
    if (!serviceEditor || typeof serviceEditor.getData !== 'function') {
        alert('CKEditor instance không hợp lệ.');
        return;
    }
    
    // Default HTML content for service
    const defaultContent = '<p><span style="color:#000000"><strong><span style="font-size:12pt">&gt;&gt; L&Agrave;M THẾ N&Agrave;O ĐỂ BẢO QUẢN K&Iacute;NH Đ&Uacute;NG C&Aacute;CH :<br /><img alt="" src="https://matkinhsaigon.com.vn/public/upload/images/Size_G%E1%BB%8Dng/Ve_sinh_mat_kinh_2022.png" /></span></strong></span></p>\n\n<p><strong><span style="color:#000000"><span style="font-size:12pt">KHI ĐEO K&Iacute;NH :</span></span></strong><br /><span style="font-size:12pt"><span style="color:#000000">Bạn n&ecirc;n mở gọng k&iacute;nh v&agrave; đeo &aacute;p theo hai b&ecirc;n mang tai một c&aacute;ch nhẹ nh&agrave;ng v&agrave; cẩn thận để tr&aacute;nh bị c&agrave;ng gọng hai b&ecirc;n chọc v&agrave;o mắt.</span></span></p>\n\n<p><span style="color:#000000"><strong><span style="font-size:12pt">KHI TH&Aacute;O K&Iacute;NH :</span></strong></span><br /><span style="font-size:12pt"><span style="color:#000000">Giữ hai b&ecirc;n c&agrave;ng k&iacute;nh v&agrave; th&aacute;o ra một c&aacute;ch nhẹ nh&agrave;ng theo chiều dọc của gương mặt. Kh&ocirc;ng n&ecirc;n chỉ giữ một b&ecirc;n v&agrave; trực tiếp th&aacute;o xuống v&igrave; c&oacute; thể khiến phần gọng bị hư, biến dạng v&agrave; thậm ch&iacute; l&agrave;m c&agrave;ng gọng hai b&ecirc;n bị lỏng lẻo.</span></span></p>\n\n<p><strong><span style="color:#000000"><span style="font-size:12pt">KHI ĐẶT K&Iacute;NH XUỐNG :</span></span></strong><br /><span style="font-size:12pt"><span style="color:#000000">Để tr&aacute;nh cho tr&ograve;ng k&iacute;nh bị hư hỏng, bạn n&ecirc;n đặt mặt trước của tr&ograve;ng k&iacute;nh ngửa l&ecirc;n tr&ecirc;n.</span></span></p>\n\n<p><span style="color:#000000"><strong><span style="font-size:12pt">KHI VỆ SINH K&Iacute;NH :</span></strong></span><br /><span style="font-size:12pt"><span style="color:#000000">Khi lau, bạn phải giữ b&ecirc;n ngo&agrave;i m&eacute;p gọng v&agrave; chỉ n&ecirc;n lau tr&ograve;ng k&iacute;nh bằng vải chuy&ecirc;n dụng. Kh&ocirc;ng n&ecirc;n lau tr&ograve;ng nếu vẫn c&ograve;n c&aacute;c vật lạ, cứng như c&aacute;t, bụi c&ograve;n b&aacute;m d&iacute;nh tr&ecirc;n tr&ograve;ng c&oacute; thể l&agrave;m bề mặt k&iacute;nh bị bong tr&oacute;c v&agrave; l&agrave;m hỏng lớp phủ.</span></span></p>\n\n<p><strong><span style="color:#000000"><span style="font-size:12pt">KHI TR&Ograve;NG K&Iacute;NH B&Aacute;M BỤI :</span></span></strong><br /><span style="font-size:12pt"><span style="color:#000000">Khi tr&ograve;ng k&iacute;nh bị bụi b&aacute;m tr&ecirc;n bề mặt, bạn n&ecirc;n rửa qua tr&ograve;ng k&iacute;nh với nước sạch, lau kh&ocirc; bằng khăn mềm, sau đ&oacute; sử dụng khăn vải chuy&ecirc;n dụng để lau. Nếu tr&ograve;ng k&iacute;nh được lau sạch nhưng c&aacute;t bụi c&ograve;n b&aacute;m lại vẫn c&oacute; thể l&agrave;m trầy xước bề mặt k&iacute;nh.</span></span></p>\n\n<p><span style="color:#000000"><strong><span style="font-size:12pt">KHI TR&Ograve;NG K&Iacute;NH QU&Aacute; BẨN :</span></strong></span><br /><span style="font-size:12pt"><span style="color:#000000">Nếu tr&ograve;ng k&iacute;nh qu&aacute; bẩn, h&atilde;y sử dụng chất tẩy trung h&ograve;a pha lo&atilde;ng, sau đ&oacute; rửa lại bằng nước sạch, lau kh&ocirc; bằng khăn mềm rồi d&ugrave;ng khăn vải chuy&ecirc;n dụng để lau lại lần nữa.</span></span></p>';
    
    // Get current content
    const currentContent = serviceEditor.getData();
    
    // If editor already has content, append at the end, otherwise replace
    if (currentContent && currentContent.trim() !== '') {
        // Append with a line break
        serviceEditor.setData(currentContent + '<p>&nbsp;</p>' + defaultContent);
    } else {
        // Replace empty content
        serviceEditor.setData(defaultContent);
    }
    
    // Focus the editor
    serviceEditor.focus();
}

// Function to insert default tutorial content into CKEditor
function insertDefaultTutorialContent() {
    try {
        // Check if CKEditor is loaded
        if (typeof CKEDITOR === 'undefined') {
            alert('CKEditor chưa được tải. Vui lòng đợi vài giây rồi thử lại.');
            return;
        }
        
        // Try to get editor instance directly from CKEDITOR
        let tutorialEditor = null;
        
        // Method 1: Try window.ckEditors first
        if (typeof window.ckEditors !== 'undefined' && window.ckEditors['tutorial']) {
            tutorialEditor = window.ckEditors['tutorial'];
        } 
        // Method 2: Try CKEDITOR.instances
        else if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['tutorial']) {
            tutorialEditor = CKEDITOR.instances['tutorial'];
        }
        // Method 3: Wait and retry
        else {
            // Wait a bit and retry
            setTimeout(function() {
                if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['tutorial']) {
                    tutorialEditor = CKEDITOR.instances['tutorial'];
                    insertTutorialContent(tutorialEditor);
                } else {
                    alert('CKEditor chưa sẵn sàng. Vui lòng đợi vài giây rồi thử lại.');
                }
            }, 500);
            return;
        }
        
        insertTutorialContent(tutorialEditor);
    } catch (error) {
        console.error('Error inserting default tutorial content:', error);
        alert('Có lỗi xảy ra khi thêm nội dung mặc định: ' + error.message);
    }
}

// Helper function to insert tutorial content
function insertTutorialContent(tutorialEditor) {
    if (!tutorialEditor || typeof tutorialEditor.getData !== 'function') {
        alert('CKEditor instance không hợp lệ.');
        return;
    }
    
    // Default HTML content for tutorial
    const defaultContent = '<p><span style="color:#000000"><strong><span style="font-size:12pt"><span>HƯỚNG DẪN MUA H&Agrave;NG :&nbsp;</span></span></strong></span></p>\n\n<p><span style="color:#000000"><strong><span style="font-size:12pt"><span>Bước 1 </span></span></strong></span><span style="font-size:12pt"><span><span style="color:#000000"><strong>:</strong> Chọn sản phẩm ưng &yacute; (m&agrave;u sắc, k&iacute;ch cỡ, m&agrave;u sắc..) v&agrave; đặt h&agrave;ng tr&ecirc;n trang web th&agrave;nh c&ocirc;ng</span></span></span></p>\n\n<p><span style="color:#000000"><strong><span style="font-size:12pt"><span>Bước 2 :</span></span></strong></span><span style="font-size:12pt"><span><span style="color:#000000"> Gọi điện hoặc nhắn tin thống nhất đơn h&agrave;ng Online th&ocirc;ng qua :&nbsp; Hotline 0888368889&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></span></p>\n\n<p><span style="color:#000000"><strong><span style="font-size:12pt"><span>Bước 3 :</span></span></strong></span><span style="font-size:12pt"><span><span style="color:#000000"> Chuyển tiền thanh to&aacute;n đơn h&agrave;ng tới khoản tới chủ t&agrave;i khoản sau :</span></span></span></p>\n\n<p><span style="font-size:12pt"><span><span style="color:#000000">Nếu qu&yacute; kh&aacute;ch lựa chọn h&igrave;nh thức chuyển khoản qua ng&acirc;n h&agrave;ng, h&atilde;y chuyển khoản cho ch&uacute;ng t&ocirc;i v&agrave;o 1 trong số c&aacute;c số t&agrave;i khoản sau đ&acirc;y:</span></span></span></p>\n\n<p><span style="font-size:12pt"><span><span style="color:#000000"><strong>1. ACB Bank </strong>: 1929 8888 8888</span></span></span></p>\n\n<p><strong><span style="font-size:12pt"><span><span style="color:#000000">2. </span></span></span></strong><span style="font-size:12pt"><span><span style="color:#050505"><strong>Vietcombank</strong> : 8888888.301</span></span></span></p>\n\n<p><span style="font-size:12pt"><span><span style="color:#050505"><strong>3. Vietcombank :</strong> 6666666.301</span></span></span></p>\n\n<p><span style="color:#000000"><strong><span style="font-size:12pt"><span>Bước 4 :</span></span></strong></span><span style="font-size:12pt"><span><span style="color:#000000"> Ngay khi nhận được tiền, sẽ thực hiện đ&oacute;ng g&oacute;i v&agrave; chuyển h&agrave;ng th&ocirc;ng qua c&ocirc;ng ty chuyển ph&aacute;t Viettel. Qu&yacute; kh&aacute;ch sẽ nhận được sản phẩm trong v&ograve;ng từ 24 đến 72 giờ ( trừ ng&agrave;y nghỉ , thứ 7, chủ nhật).</span></span></span></p>\n\n<p><span style="color:#000000"><strong><span style="font-size:12pt"><span>Bước 5 :</span></span></strong></span><span style="font-size:12pt"><span><span style="color:#000000"> X&aacute;c nhận đơn h&agrave;ng đ&atilde; ho&agrave;n th&agrave;nh với kh&aacute;ch h&agrave;ng sau khi kh&aacute;ch nhận được sản phẩm</span></span></span></p>\n\n<p><a href="https://matkinhsaigon.com.vn/chinh-sach-va-quy-dinh/quy-dinh-phuong-thuc-thanh-toan"><span style="font-size:12pt"><span><span style="color:#000000"><img src="https://matkinhsaigon.com.vn/public/upload/images/icon-web/animated-arrow-mk2.gif" style="height:14px; width:24px" /></span></span></span><span style="font-size:10pt"><span><span style="color:#808080"> </span></span></span><span style="font-size:12pt"><span><span style="color:#555555">Hướng dẫn thanh to&aacute;n v&agrave; nhận h&agrave;ng </span></span></span></a></p>';
    
    // Get current content
    const currentContent = tutorialEditor.getData();
    
    // If editor already has content, append at the end, otherwise replace
    if (currentContent && currentContent.trim() !== '') {
        // Append with a line break
        tutorialEditor.setData(currentContent + '<p>&nbsp;</p>' + defaultContent);
    } else {
        // Replace empty content
        tutorialEditor.setData(defaultContent);
    }
    
    // Focus the editor
    tutorialEditor.focus();
}

// Function to insert default address content into CKEditor
function insertDefaultAddressContent() {
    try {
        // Check if CKEditor is loaded
        if (typeof CKEDITOR === 'undefined') {
            alert('CKEditor chưa được tải. Vui lòng đợi vài giây rồi thử lại.');
            return;
        }
        
        // Try to get editor instance directly from CKEDITOR
        let addressEditor = null;
        
        // Method 1: Try window.ckEditors first
        if (typeof window.ckEditors !== 'undefined' && window.ckEditors['address']) {
            addressEditor = window.ckEditors['address'];
        } 
        // Method 2: Try CKEDITOR.instances
        else if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['address']) {
            addressEditor = CKEDITOR.instances['address'];
        }
        // Method 3: Wait and retry
        else {
            // Wait a bit and retry
            setTimeout(function() {
                if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['address']) {
                    addressEditor = CKEDITOR.instances['address'];
                    insertAddressContent(addressEditor);
                } else {
                    alert('CKEditor chưa sẵn sàng. Vui lòng đợi vài giây rồi thử lại.');
                }
            }, 500);
            return;
        }
        
        insertAddressContent(addressEditor);
    } catch (error) {
        console.error('Error inserting default address content:', error);
        alert('Có lỗi xảy ra khi thêm nội dung mặc định: ' + error.message);
    }
}

// Helper function to insert address content
function insertAddressContent(addressEditor) {
    if (!addressEditor || typeof addressEditor.getData !== 'function') {
        alert('CKEditor instance không hợp lệ.');
        return;
    }
    
    // Default HTML content for address
    const defaultContent = '<p><span style="font-size:12pt"><span><span style="color:#000000"><strong>Địa Chỉ 1 :</strong> 301B Điện Bi&ecirc;n Phủ, Phường V&otilde; Thị S&aacute;u , Quận 3, TP. Hồ Ch&iacute; Minh</span></span></span></p>\n\n<p><span style="font-size:12pt"><span><span style="color:#000000"><strong>Địa Chỉ 2 :&nbsp;</strong> 245C X&ocirc; Viết Nghệ Tĩnh, P.17, Q. B&igrave;nh Thạnh, TP. HCM</span></span></span></p>\n\n<p><strong><span style="font-size:12pt"><span><span style="color:#000000">Địa Chỉ </span></span></span><span style="font-size:10pt"><span><span style="color:#808080">&nbsp;</span></span></span></strong><span style="font-size:12pt"><span><span style="color:#000000"><strong>3 :</strong>&nbsp; 90 Nguyễn Hữu Thọ, P. Phước Trung, TP. B&agrave; Rịa</span></span></span></p>\n\n<p><span style="font-size:12pt"><span><span style="color:#000000"><img alt="" src="https://matkinhsaigon.com.vn/public/upload/images/icon-web/Logo_mksg_2023_2.png" /></span></span></span></p>';
    
    // Get current content
    const currentContent = addressEditor.getData();
    
    // If editor already has content, append at the end, otherwise replace
    if (currentContent && currentContent.trim() !== '') {
        // Append with a line break
        addressEditor.setData(currentContent + '<p>&nbsp;</p>' + defaultContent);
    } else {
        // Replace empty content
        addressEditor.setData(defaultContent);
    }
    
    // Focus the editor
    addressEditor.focus();
}

// Function to insert default opening hours content into CKEditor
function insertDefaultOpeningHoursContent() {
    try {
        // Check if CKEditor is loaded
        if (typeof CKEDITOR === 'undefined') {
            alert('CKEditor chưa được tải. Vui lòng đợi vài giây rồi thử lại.');
            return;
        }
        
        // Try to get editor instance directly from CKEDITOR
        let openingHoursEditor = null;
        
        // Method 1: Try window.ckEditors first
        if (typeof window.ckEditors !== 'undefined' && window.ckEditors['opening_hours']) {
            openingHoursEditor = window.ckEditors['opening_hours'];
        } 
        // Method 2: Try CKEDITOR.instances
        else if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['opening_hours']) {
            openingHoursEditor = CKEDITOR.instances['opening_hours'];
        }
        // Method 3: Wait and retry
        else {
            // Wait a bit and retry
            setTimeout(function() {
                if (typeof CKEDITOR.instances !== 'undefined' && CKEDITOR.instances['opening_hours']) {
                    openingHoursEditor = CKEDITOR.instances['opening_hours'];
                    insertOpeningHoursContent(openingHoursEditor);
                } else {
                    alert('CKEditor chưa sẵn sàng. Vui lòng đợi vài giây rồi thử lại.');
                }
            }, 500);
            return;
        }
        
        insertOpeningHoursContent(openingHoursEditor);
    } catch (error) {
        console.error('Error inserting default opening hours content:', error);
        alert('Có lỗi xảy ra khi thêm nội dung mặc định: ' + error.message);
    }
}

// Helper function to insert opening hours content
function insertOpeningHoursContent(openingHoursEditor) {
    if (!openingHoursEditor || typeof openingHoursEditor.getData !== 'function') {
        alert('CKEditor instance không hợp lệ.');
        return;
    }
    
    // Default HTML content for opening hours
    const defaultContent = '<p><span style="font-size:12pt"><span style="color:#000000"><strong>Thời gian l&agrave;m việc</strong></span></span></p>\n\n<p><span style="font-size:12pt"><span style="color:#000000">Thứ 2 -Thứ 7 : 7h30 - 20h30</span></span></p>\n\n<p><span style="font-size:12pt"><span style="color:#000000">Ng&agrave;y lễ &amp; CN : 8h00 - 20h00</span></span></p>\n\n<p><span style="font-size:12pt"><span style="color:#000000"><strong>Hỗ trợ tư vấn</strong></span></span></p>\n\n<p><span style="font-size:12pt"><span style="color:#000000">Hotline: 0888368889 - 0966666.301</span></span></p>\n\n<p>&nbsp;</p>\n\n<p>&nbsp;</p>';
    
    // Get current content
    const currentContent = openingHoursEditor.getData();
    
    // If editor already has content, append at the end, otherwise replace
    if (currentContent && currentContent.trim() !== '') {
        // Append with a line break
        openingHoursEditor.setData(currentContent + '<p>&nbsp;</p>' + defaultContent);
    } else {
        // Replace empty content
        openingHoursEditor.setData(defaultContent);
    }
    
    // Focus the editor
    openingHoursEditor.focus();
}

// Form submit handler - lấy data từ textarea và CKEditor
document.getElementById('productForm').addEventListener('submit', function(e) {
    // Sync all CKEditor instances before submit
    if (typeof window.syncCKEditors === 'function') {
        window.syncCKEditors();
    }
    
    // Helper function để lấy dữ liệu từ textarea
    function getTextareaData(name) {
        const textarea = document.querySelector('textarea[name="' + name + '"]');
        return textarea ? textarea.value : '';
    }
    
    // Description - lưu dạng JSON
    const descData = {
        name: document.querySelector('input[name="description_name"]')?.value || 'Mô tả sản phẩm',
        hidden: document.querySelector('input[name="description_hidden"]')?.checked ? '1' : '0',
        text: getTextareaData('description')
    };
    // Tạo hoặc cập nhật hidden input cho description (JSON)
    let descriptionInput = document.querySelector('input[name="description"][type="hidden"]');
    if (!descriptionInput) {
        descriptionInput = document.createElement('input');
        descriptionInput.type = 'hidden';
        descriptionInput.name = 'description';
        document.getElementById('productForm').appendChild(descriptionInput);
    }
    descriptionInput.value = JSON.stringify(descData);
    // Disable textarea description để không gửi lên server
    const descTextarea = document.getElementById('description');
    if (descTextarea) {
        descTextarea.disabled = true;
    }
    
    // Content - lưu dạng JSON
    const contentData = {
        name: document.querySelector('input[name="content_name"]')?.value || 'Nội dung',
        hidden: document.querySelector('input[name="content_hidden"]')?.checked ? '1' : '0',
        text: getTextareaData('content')
    };
    // Tạo hoặc cập nhật hidden input cho content (JSON)
    let contentInput = document.querySelector('input[name="content"][type="hidden"]');
    if (!contentInput) {
        contentInput = document.createElement('input');
        contentInput.type = 'hidden';
        contentInput.name = 'content';
        document.getElementById('productForm').appendChild(contentInput);
    }
    contentInput.value = JSON.stringify(contentData);
    // Disable textarea content để không gửi lên server
    const contentTextarea = document.getElementById('content');
    if (contentTextarea) {
        contentTextarea.disabled = true;
    }
    
    // Tech - lưu dạng JSON
    const techData = {
        name: document.querySelector('input[name="tech_name"]')?.value || 'Thông số kỹ thuật',
        hidden: document.querySelector('input[name="tech_hidden"]')?.checked ? '1' : '0',
        text: getTextareaData('tech')
    };
    // Tạo hoặc cập nhật hidden input cho tech (JSON)
    let techInput = document.querySelector('input[name="tech"][type="hidden"]');
    if (!techInput) {
        techInput = document.createElement('input');
        techInput.type = 'hidden';
        techInput.name = 'tech';
        document.getElementById('productForm').appendChild(techInput);
    }
    techInput.value = JSON.stringify(techData);
    // Disable textarea tech để không gửi lên server
    const techTextarea = document.getElementById('tech');
    if (techTextarea) {
        techTextarea.disabled = true;
    }
    
    // Service - lưu dạng JSON
    const serviceData = {
        name: document.querySelector('input[name="service_name"]')?.value || 'Tư vấn sử dụng',
        hidden: document.querySelector('input[name="service_hidden"]')?.checked ? '1' : '0',
        text: getTextareaData('service')
    };
    // Tạo hoặc cập nhật hidden input cho service (JSON)
    let serviceInput = document.querySelector('input[name="service"][type="hidden"]');
    if (!serviceInput) {
        serviceInput = document.createElement('input');
        serviceInput.type = 'hidden';
        serviceInput.name = 'service';
        document.getElementById('productForm').appendChild(serviceInput);
    }
    serviceInput.value = JSON.stringify(serviceData);
    // Disable textarea service để không gửi lên server
    const serviceTextarea = document.getElementById('service');
    if (serviceTextarea) {
        serviceTextarea.disabled = true;
    }
    
    // Tutorial - lưu dạng JSON
    const tutorialData = {
        name: document.querySelector('input[name="tutorial_name"]')?.value || 'Hướng dẫn mua hàng',
        hidden: document.querySelector('input[name="tutorial_hidden"]')?.checked ? '1' : '0',
        text: getTextareaData('tutorial')
    };
    // Tạo hoặc cập nhật hidden input cho tutorial (JSON)
    let tutorialInput = document.querySelector('input[name="tutorial"][type="hidden"]');
    if (!tutorialInput) {
        tutorialInput = document.createElement('input');
        tutorialInput.type = 'hidden';
        tutorialInput.name = 'tutorial';
        document.getElementById('productForm').appendChild(tutorialInput);
    }
    tutorialInput.value = JSON.stringify(tutorialData);
    // Disable textarea tutorial để không gửi lên server
    const tutorialTextarea = document.getElementById('tutorial');
    if (tutorialTextarea) {
        tutorialTextarea.disabled = true;
    }
    
    // Address (address_sale) - lưu dạng JSON
    const addressData = {
        name: document.querySelector('input[name="address_sale_name"]')?.value || 'Địa chỉ mua hàng',
        hidden: document.querySelector('input[name="address_sale_hidden"]')?.checked ? '1' : '0',
        text: getTextareaData('address')
    };
    // Tạo hoặc cập nhật hidden input cho address_sale (JSON)
    let addressSaleJsonInput = document.querySelector('input[name="address_sale"][type="hidden"]');
    if (!addressSaleJsonInput) {
        addressSaleJsonInput = document.createElement('input');
        addressSaleJsonInput.type = 'hidden';
        addressSaleJsonInput.name = 'address_sale';
        document.getElementById('productForm').appendChild(addressSaleJsonInput);
    }
    addressSaleJsonInput.value = JSON.stringify(addressData);
    // Disable textarea address để không gửi lên server
    const addressTextarea = document.getElementById('address');
    if (addressTextarea) {
        addressTextarea.disabled = true;
    }
    
    // Opening Hours (open_time) - lưu dạng JSON
    const openingHoursData = {
        name: document.querySelector('input[name="opening_hours_name"]')?.value || 'Thời gian mở cửa',
        hidden: document.querySelector('input[name="opening_hours_hidden"]')?.checked ? '1' : '0',
        text: getTextareaData('opening_hours')
    };
    // Tạo hoặc cập nhật hidden input cho open_time (JSON)
    let openTimeInput = document.querySelector('input[name="open_time"]');
    if (!openTimeInput) {
        openTimeInput = document.createElement('input');
        openTimeInput.type = 'hidden';
        openTimeInput.name = 'open_time';
        document.getElementById('productForm').appendChild(openTimeInput);
    }
    openTimeInput.value = JSON.stringify(openingHoursData);
    // Disable textarea opening_hours để không gửi lên server
    const openingHoursTextarea = document.getElementById('opening_hours');
    if (openingHoursTextarea) {
        openingHoursTextarea.disabled = true;
    }
    
    // Create hidden inputs for image colors
    selectedImages.forEach((img, index) => {
        // Remove existing color input if any
        const existingInput = document.querySelector(`input[name="image_colors[${index}]"]`);
        if (existingInput) {
            existingInput.remove();
        }
        
        // Create new hidden input for color_id
        if (img.color_id) {
            const colorInput = document.createElement('input');
            colorInput.type = 'hidden';
            colorInput.name = `image_colors[${index}]`;
            colorInput.value = img.color_id;
            document.getElementById('productForm').appendChild(colorInput);
        }
    });
});

// Sale Prices Management
let salePriceRowIndex = 1;

function addSalePriceRow() {
    const container = document.getElementById('salePricesContainer');
    if (!container) {
        console.error('salePricesContainer not found');
        return;
    }
    
    const newRow = document.createElement('div');
    newRow.className = 'sale-price-row grid grid-cols-1 gap-4 bg-white p-4 rounded-lg border border-gray-200 fade-in';
    
    // Get categories HTML from first select
    const firstSelect = container.querySelector('select[name*="[category1]"]');
    let categoriesOptions = '<option value="">Tất cả</option>';
    if (firstSelect) {
        firstSelect.querySelectorAll('option').forEach(option => {
            if (option.value !== '') {
                categoriesOptions += `<option value="${option.value}">${option.textContent}</option>`;
            }
        });
    }
    
    newRow.innerHTML = `
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Danh Mục
            </label>
            <select name="sale_prices[${salePriceRowIndex}][category1]" 
                    class="category1-select w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white">
                ${categoriesOptions}
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Danh Mục
            </label>
            <select name="sale_prices[${salePriceRowIndex}][category2]" 
                    class="category2-select w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white">
                <option value="">Tất cả</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Giá Giảm (VNĐ):
            </label>
            <div class="flex items-center gap-2">
                <input type="number" 
                       name="sale_prices[${salePriceRowIndex}][discount_price]" 
                       value=""
                       min="0"
                       step="1000"
                       placeholder="0"
                       class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth">
                <button type="button" 
                        onclick="removeSalePriceRow(this)"
                        class="remove-row-btn p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    salePriceRowIndex++;
    
    // Show remove buttons for all rows except the first one
    updateRemoveButtons();
    
    // Attach event listener to the new category1 select
    const newCategory1Select = newRow.querySelector('.category1-select');
    const newCategory2Select = newRow.querySelector('.category2-select');
    if (newCategory1Select && newCategory2Select) {
        newCategory1Select.addEventListener('change', function() {
            updateCategory2Options(this, newCategory2Select);
        });
    }
}

// Function to update category2 options based on category1 selection
function updateCategory2Options(category1Select, category2Select) {
    const parentId = category1Select.value;
    
    // Clear existing options except "Tất cả"
    category2Select.innerHTML = '<option value="">Tất cả</option>';
    
    if (!parentId) {
        return;
    }
    
    // Filter categories by parent_id
    const childCategories = allCategories.filter(cat => cat.parent_id == parentId);
    
    // Add child categories to select
    childCategories.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.id;
        option.textContent = cat.name;
        category2Select.appendChild(option);
    });
}

function removeSalePriceRow(button) {
    const row = button.closest('.sale-price-row');
    if (!row) return;
    
    row.style.transition = 'opacity 0.3s, transform 0.3s';
    row.style.opacity = '0';
    row.style.transform = 'translateX(-20px)';
    
    setTimeout(() => {
        row.remove();
        updateRemoveButtons();
    }, 300);
}

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.sale-price-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-row-btn');
        if (removeBtn) {
            if (index === 0 && rows.length === 1) {
                removeBtn.classList.add('hidden');
            } else {
                removeBtn.classList.remove('hidden');
            }
        }
    });
}

// Initialize event listeners for existing category selects
document.addEventListener('DOMContentLoaded', function() {
    // Attach event listeners to all existing category1 selects
    document.querySelectorAll('.category1-select').forEach(function(category1Select) {
        const row = category1Select.closest('.sale-price-row');
        if (row) {
            const category2Select = row.querySelector('.category2-select');
            if (category2Select) {
                category1Select.addEventListener('change', function() {
                    updateCategory2Options(this, category2Select);
                });
            }
        }
    });
});

// Combo Management
let comboRowIndex = 1;

function addComboRow() {
    const container = document.getElementById('comboContainer');
    if (!container) {
        console.error('comboContainer not found');
        return;
    }
    
    const newRow = document.createElement('div');
    newRow.className = 'combo-row bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow fade-in';
    
    newRow.innerHTML = `
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tên Combo <span class="text-red-500">*</span>
                </label>
                <div class="flex items-start gap-2">
                    <input type="text" 
                           name="combos[${comboRowIndex}][name]" 
                           value=""
                           placeholder="Nhập tên combo"
                           class="flex-1 combo-name w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth">
                    <button type="button" 
                            onclick="removeComboRow(this)"
                            class="remove-combo-btn p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Giá (VNĐ)
                    </label>
                    <input type="number" 
                           name="combos[${comboRowIndex}][price]" 
                           value=""
                           min="0"
                           step="1000"
                           placeholder="0"
                           class="combo-price w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Thứ tự
                    </label>
                    <input type="number" 
                           name="combos[${comboRowIndex}][weight]" 
                           value="0"
                           min="0"
                           placeholder="0"
                           class="combo-weight w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Mô tả
                </label>
                <textarea name="combos[${comboRowIndex}][description]" 
                          rows="2"
                          placeholder="Nhập mô tả combo (tùy chọn)"
                          class="combo-description w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth resize-none"></textarea>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    comboRowIndex++;
    
    // Show remove buttons for all rows except the first one
    updateComboRemoveButtons();
}

function removeComboRow(button) {
    const row = button.closest('.combo-row');
    if (!row) return;
    
    row.style.transition = 'opacity 0.3s, transform 0.3s';
    row.style.opacity = '0';
    row.style.transform = 'translateX(-20px)';
    
    setTimeout(() => {
        row.remove();
        updateComboRemoveButtons();
    }, 300);
}

function updateComboRemoveButtons() {
    const rows = document.querySelectorAll('.combo-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-combo-btn');
        if (removeBtn) {
            if (index === 0 && rows.length === 1) {
                removeBtn.classList.add('hidden');
            } else {
                removeBtn.classList.remove('hidden');
            }
        }
    });
}

// Initialize combo remove buttons on page load
document.addEventListener('DOMContentLoaded', function() {
    updateComboRemoveButtons();
});

// Handle hidden checkbox
const hiddenCheckbox = document.querySelector('input[name="hidden"][type="checkbox"]');
const hiddenDefault = document.getElementById('hidden_default');
if (hiddenCheckbox) {
    hiddenCheckbox.addEventListener('change', function() {
        if (this.checked) {
            hiddenDefault.value = '0';
        } else {
            hiddenDefault.value = '1';
        }
    });
    // Set initial value
    if (hiddenCheckbox.checked) {
        hiddenDefault.value = '0';
    }
}

function previewMainImage(input) {
    const container = document.getElementById('imagePreviewContainer');
    const placeholder = document.getElementById('imagePlaceholder');
    const preview = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        if (preview) preview.classList.add('hidden');
        if (placeholder) placeholder.classList.remove('hidden');
    }
}

// Drag and drop handlers
function handleDragOver(e) {
    e.preventDefault();
    e.stopPropagation();
    const container = document.getElementById('multipleImagesContainer');
    if (container) {
        container.classList.add('drag-over');
    }
}

function handleDragLeave(e) {
    e.preventDefault();
    e.stopPropagation();
    const container = document.getElementById('multipleImagesContainer');
    if (container) {
        container.classList.remove('drag-over');
    }
}

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    const container = document.getElementById('multipleImagesContainer');
    if (container) {
        container.classList.remove('drag-over');
    }
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const input = document.getElementById('multipleImagesInput');
        const dt = new DataTransfer();
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                dt.items.add(file);
            }
        });
        input.files = dt.files;
        handleMultipleImages(input);
    }
}

function handleMultipleImages(input) {
    if (input.files && input.files.length > 0) {
        const previewContainer = document.getElementById('selectedImagesPreview');
        previewContainer.classList.remove('hidden');
        
        // Process all selected images
        Array.from(input.files).forEach((file, index) => {
            // Validate file size (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert(`Hình ảnh "${file.name}" vượt quá 2MB. Vui lòng chọn hình khác.`);
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const imageId = 'img_' + Date.now() + '_' + index;
                selectedImages.push({
                    id: imageId,
                    file: file,
                    preview: e.target.result,
                    number: selectedImages.length,
                    color_id: null
                });
                
                const imageNumber = selectedImages.length;
                const totalImages = selectedImages.length;
                
                // Create modern preview card with improved design
                const imageCard = document.createElement('div');
                imageCard.id = imageId;
                imageCard.className = 'relative group bg-white rounded-xl border-2 border-gray-200 overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-fade-in cursor-move';
                imageCard.draggable = true;
                imageCard.dataset.imageId = imageId;
                imageCard.innerHTML = `
                    <div class="aspect-square relative bg-gray-100 rounded-t-xl overflow-hidden">
                        <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover pointer-events-none">
                        <!-- Subtle overlay only on hover -->
                        <div class="absolute top-0 bottom-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent opacity-40" style="width: 200px; left: 50%; transform: translateX(-50%);"></div>
                        
                        <!-- Number badge - always visible, improved design -->
                        <div class="absolute top-3 left-3 bg-primary-600 text-white rounded-lg px-2.5 py-1.5 flex items-center justify-center font-bold text-sm shadow-lg z-20">
                            #${imageNumber}
                        </div>
                        
                        <!-- Order controls - improved layout and visibility -->
                        <div class="absolute top-3 right-3 flex gap-1.5 z-20">
                            ${imageNumber > 1 ? `
                            <button type="button" 
                                    onclick="moveImageUp('${imageId}')"
                                    class="bg-white hover:bg-blue-50 text-blue-600 hover:text-blue-700 rounded-lg px-2.5 py-2 shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 border border-blue-200"
                                    title="Di chuyển lên"
                                    id="up-btn-${imageId}">
                                <i class="fas fa-arrow-up text-sm"></i>
                            </button>` : ''}
                            ${imageNumber < selectedImages.length ? `
                            <button type="button" 
                                    onclick="moveImageDown('${imageId}')"
                                    class="bg-white hover:bg-blue-50 text-blue-600 hover:text-blue-700 rounded-lg px-2.5 py-2 shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 border border-blue-200"
                                    title="Di chuyển xuống"
                                    id="down-btn-${imageId}">
                                <i class="fas fa-arrow-down text-sm"></i>
                            </button>` : ''}
                            <button type="button" 
                                    onclick="removeImage('${imageId}')"
                                    class="bg-white hover:bg-red-50 text-red-600 hover:text-red-700 rounded-lg px-2.5 py-2 shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 border border-red-200"
                                    title="Xóa hình ảnh">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                        
                        <!-- Color selection button - improved design and placement -->
                        <div class="absolute bottom-3 left-3 right-3 z-20">
                            <button type="button" 
                                    onclick="openColorSelector('${imageId}')"
                                    class="w-full bg-white hover:bg-purple-50 text-gray-700 hover:text-purple-700 px-3 py-2 rounded-lg shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center gap-2 text-sm font-medium border border-gray-200 hover:border-purple-300">
                                <i class="fas fa-palette text-purple-600"></i>
                                <span id="color-label-${imageId}">Chọn màu</span>
                            </button>
                        </div>
                        
                        <!-- Selected color badge - improved positioning -->
                        <div id="color-badge-${imageId}" class="absolute top-3 right-3 hidden z-30" style="margin-top: 40px;">
                            <div class="bg-white rounded-lg p-1.5 shadow-lg border-2 border-purple-300">
                                <div class="w-6 h-6 rounded border-2 border-gray-400 overflow-hidden" id="color-preview-${imageId}"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Info bar below image - always visible -->
                    <div class="bg-gray-50 border-t border-gray-200 px-3 py-2 rounded-b-xl">
                        <p class="text-xs text-gray-600 font-medium truncate" title="${file.name}">${file.name}</p>
                        <p class="text-xs text-gray-400 mt-0.5">${(file.size / 1024).toFixed(1)} KB</p>
                    </div>
                `;
                
                // Add drag event listeners for reordering
                imageCard.addEventListener('dragstart', handleImageDragStart);
                imageCard.addEventListener('dragover', handleImageDragOver);
                imageCard.addEventListener('drop', handleImageDrop);
                imageCard.addEventListener('dragend', handleImageDragEnd);
                
                previewContainer.appendChild(imageCard);
                
                // Update numbers and button visibility for all images
                updateImageNumbers();
            };
            
            reader.readAsDataURL(file);
        });
        
        // Update file input
        updateFileInput();
        
        // Reset container style
        const container = document.getElementById('multipleImagesContainer');
        if (container) {
            container.classList.remove('drag-over');
        }
    }
}

function removeImage(imageId) {
    // Remove from selectedImages array
    selectedImages = selectedImages.filter(img => img.id !== imageId);
    
    // Remove from DOM with animation
    const imageCard = document.getElementById(imageId);
    if (imageCard) {
        imageCard.style.opacity = '0';
        imageCard.style.transform = 'scale(0.8)';
        setTimeout(() => {
        imageCard.remove();
            // Update numbers after removal
            updateImageNumbers();
        }, 300);
    }
    
    // Update file input
    updateFileInput();
}

function updateImageNumbers() {
    selectedImages.forEach((img, index) => {
        const imageCard = document.getElementById(img.id);
        if (imageCard) {
            const numberBadge = imageCard.querySelector('.absolute.top-3.left-3');
            if (numberBadge && numberBadge.classList.contains('bg-primary-600')) {
                numberBadge.textContent = `#${index + 1}`;
            }
            img.number = index + 1;
            
            // Update button visibility for move up/down
            const upButton = imageCard.querySelector('button[onclick*="moveImageUp"]');
            const downButton = imageCard.querySelector('button[onclick*="moveImageDown"]');
            if (upButton) {
                upButton.style.display = index === 0 ? 'none' : 'flex';
            }
            if (downButton) {
                downButton.style.display = index === selectedImages.length - 1 ? 'none' : 'flex';
            }
        }
    });
    updateFileInput();
}

// Move image up
function moveImageUp(imageId) {
    const currentIndex = selectedImages.findIndex(img => img.id === imageId);
    if (currentIndex > 0) {
        // Swap with previous
        [selectedImages[currentIndex], selectedImages[currentIndex - 1]] = 
        [selectedImages[currentIndex - 1], selectedImages[currentIndex]];
        
        // Re-render preview
        reorderImagePreviews();
    }
}

// Move image down
function moveImageDown(imageId) {
    const currentIndex = selectedImages.findIndex(img => img.id === imageId);
    if (currentIndex < selectedImages.length - 1) {
        // Swap with next
        [selectedImages[currentIndex], selectedImages[currentIndex + 1]] = 
        [selectedImages[currentIndex + 1], selectedImages[currentIndex]];
        
        // Re-render preview
        reorderImagePreviews();
    }
}

// Reorder image previews in DOM
function reorderImagePreviews() {
    const previewContainer = document.getElementById('selectedImagesPreview');
    const fragment = document.createDocumentFragment();
    
    selectedImages.forEach((img, index) => {
        const imageCard = document.getElementById(img.id);
        if (imageCard) {
            // Update number badge
            const numberBadge = imageCard.querySelector('.absolute.top-3.left-3');
            if (numberBadge && numberBadge.classList.contains('bg-primary-600')) {
                numberBadge.textContent = `#${index + 1}`;
            }
            img.number = index + 1;
            
            // Update color badge and label if color is selected
            if (img.color_id) {
                const selectedColor = colorsData.find(c => c.id == img.color_id);
                if (selectedColor) {
                    const colorBadge = document.getElementById(`color-badge-${img.id}`);
                    const colorPreview = document.getElementById(`color-preview-${img.id}`);
                    const colorLabel = document.getElementById(`color-label-${img.id}`);
                    
                    if (colorBadge) colorBadge.classList.remove('hidden');
                    if (colorPreview) {
                        // Use image if available, otherwise use background color
                        if (selectedColor.url_img) {
                            const img = document.createElement('img');
                            img.src = colorImageBasePath + selectedColor.url_img;
                            img.alt = selectedColor.name || 'Color';
                            img.className = 'w-full h-full object-cover';
                            img.onerror = function() {
                                colorPreview.style.backgroundColor = selectedColor.color || '#cccccc';
                            };
                            colorPreview.innerHTML = '';
                            colorPreview.appendChild(img);
                        } else {
                            colorPreview.style.backgroundColor = selectedColor.color || '#cccccc';
                        }
                    }
                    if (colorLabel) colorLabel.textContent = selectedColor.name || 'Đã chọn';
                }
            }
            
            // Update button onclick handlers and visibility
            const upButton = imageCard.querySelector('button[onclick*="moveImageUp"]');
            const downButton = imageCard.querySelector('button[onclick*="moveImageDown"]');
            if (upButton) {
                upButton.setAttribute('onclick', `moveImageUp('${img.id}')`);
                upButton.style.display = index === 0 ? 'none' : 'flex';
            }
            if (downButton) {
                downButton.setAttribute('onclick', `moveImageDown('${img.id}')`);
                downButton.style.display = index === selectedImages.length - 1 ? 'none' : 'flex';
            }
            
            // Re-add drag event listeners
            imageCard.removeEventListener('dragstart', handleImageDragStart);
            imageCard.removeEventListener('dragover', handleImageDragOver);
            imageCard.removeEventListener('drop', handleImageDrop);
            imageCard.removeEventListener('dragend', handleImageDragEnd);
            
            imageCard.addEventListener('dragstart', handleImageDragStart);
            imageCard.addEventListener('dragover', handleImageDragOver);
            imageCard.addEventListener('drop', handleImageDrop);
            imageCard.addEventListener('dragend', handleImageDragEnd);
            
            fragment.appendChild(imageCard);
        }
    });
    
    previewContainer.innerHTML = '';
    previewContainer.appendChild(fragment);
    
    updateFileInput();
}

// Drag and drop for reordering images
let draggedImageElement = null;

function handleImageDragStart(e) {
    draggedImageElement = this;
    this.style.opacity = '0.5';
    this.style.cursor = 'grabbing';
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.id);
}

function handleImageDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    e.dataTransfer.dropEffect = 'move';
    
    if (draggedImageElement && draggedImageElement !== this) {
        const previewContainer = document.getElementById('selectedImagesPreview');
        const allCards = Array.from(previewContainer.querySelectorAll('[data-image-id]'));
        const draggedIndex = allCards.indexOf(draggedImageElement);
        const targetIndex = allCards.indexOf(this);
        
        if (draggedIndex !== -1 && targetIndex !== -1) {
            // Visual feedback
            if (draggedIndex < targetIndex) {
                previewContainer.insertBefore(draggedImageElement, this.nextSibling);
            } else {
                previewContainer.insertBefore(draggedImageElement, this);
            }
        }
    }
    
    return false;
}

function handleImageDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    
    if (draggedImageElement && draggedImageElement !== this) {
        const previewContainer = document.getElementById('selectedImagesPreview');
        const allCards = Array.from(previewContainer.querySelectorAll('[data-image-id]'));
        const draggedIndex = allCards.indexOf(draggedImageElement);
        const targetIndex = allCards.indexOf(this);
        
        if (draggedIndex !== -1 && targetIndex !== -1) {
            // Reorder in array
            const draggedImage = selectedImages[draggedIndex];
            selectedImages.splice(draggedIndex, 1);
            selectedImages.splice(targetIndex, 0, draggedImage);
            
            // Re-render
            reorderImagePreviews();
        }
    }
    
    return false;
}

function handleImageDragEnd(e) {
    if (draggedImageElement) {
        draggedImageElement.style.opacity = '1';
        draggedImageElement.style.cursor = 'move';
    }
    draggedImageElement = null;
}

// Color selector functions
let currentImageIdForColor = null;
let selectedColorId = null;

function openColorSelector(imageId) {
    currentImageIdForColor = imageId;
    const imageData = selectedImages.find(img => img.id === imageId);
    selectedColorId = imageData ? imageData.color_id : null;
    
    // Populate color grid
    const colorGrid = document.getElementById('colorGrid');
    colorGrid.innerHTML = '';
    
    colorsData.forEach(color => {
        const colorOption = document.createElement('div');
        colorOption.className = `color-option ${selectedColorId == color.id ? 'selected' : ''}`;
        colorOption.dataset.colorId = color.id;
        colorOption.dataset.colorName = color.name || '';
        colorOption.onclick = () => selectColorInModal(color.id);
        
        // Use image if available, otherwise use background color
        if (color.url_img) {
            const img = document.createElement('img');
            img.src = colorImageBasePath + color.url_img;
            img.alt = color.name || 'Color';
            img.className = 'w-full h-full object-cover rounded';
            img.onerror = function() {
                // Fallback to background color if image fails to load
                this.style.display = 'none';
                colorOption.style.backgroundColor = color.color || '#cccccc';
            };
            colorOption.appendChild(img);
        } else {
            // Fallback to background color
            colorOption.style.backgroundColor = color.color || '#cccccc';
        }
        
        colorGrid.appendChild(colorOption);
    });
    
    // Show modal
    const modal = document.getElementById('colorSelectorModal');
    modal.classList.add('active');
}

function closeColorSelector() {
    const modal = document.getElementById('colorSelectorModal');
    modal.classList.remove('active');
    currentImageIdForColor = null;
    selectedColorId = null;
}

function closeColorSelectorOnBackdrop(event) {
    if (event.target.id === 'colorSelectorModal') {
        closeColorSelector();
    }
}

function selectColorInModal(colorId) {
    selectedColorId = colorId;
    
    // Update visual selection
    document.querySelectorAll('.color-option').forEach(option => {
        option.classList.remove('selected');
        if (option.dataset.colorId == colorId) {
            option.classList.add('selected');
        }
    });
}

function clearColorSelection() {
    selectedColorId = null;
    document.querySelectorAll('.color-option').forEach(option => {
        option.classList.remove('selected');
    });
}

function confirmColorSelection() {
    if (!currentImageIdForColor) return;
    
    // Update image data
    const imageData = selectedImages.find(img => img.id === currentImageIdForColor);
    if (imageData) {
        imageData.color_id = selectedColorId;
        
        // Update UI
        const colorBadge = document.getElementById(`color-badge-${currentImageIdForColor}`);
        const colorPreview = document.getElementById(`color-preview-${currentImageIdForColor}`);
        const colorLabel = document.getElementById(`color-label-${currentImageIdForColor}`);
        
        if (selectedColorId) {
            const selectedColor = colorsData.find(c => c.id == selectedColorId);
            if (selectedColor) {
                colorBadge.classList.remove('hidden');
                // Use image if available, otherwise use background color
                if (selectedColor.url_img) {
                    const img = document.createElement('img');
                    img.src = colorImageBasePath + selectedColor.url_img;
                    img.alt = selectedColor.name || 'Color';
                    img.className = 'w-full h-full object-cover';
                    img.onerror = function() {
                        colorPreview.style.backgroundColor = selectedColor.color || '#cccccc';
                    };
                    colorPreview.innerHTML = '';
                    colorPreview.appendChild(img);
                } else {
                    colorPreview.style.backgroundColor = selectedColor.color || '#cccccc';
                }
                colorLabel.textContent = selectedColor.name || 'Đã chọn';
            }
        } else {
            colorBadge.classList.add('hidden');
            colorLabel.textContent = 'Chọn màu';
        }
    }
    
    closeColorSelector();
}

function updateFileInput() {
    const input = document.getElementById('multipleImagesInput');
    const dt = new DataTransfer();
    
    selectedImages.forEach(img => {
        dt.items.add(img.file);
    });
    
    input.files = dt.files;
}

// Auto-generate alias from name
(function() {
    const nameInput = document.querySelector('input[name="name"]');
    const aliasInput = document.querySelector('input[name="alias"]');
    let aliasManuallyEdited = false;
    
    if (!nameInput || !aliasInput) return;
    
    // Track if user manually edits alias
    aliasInput.addEventListener('input', function() {
        aliasManuallyEdited = true;
    });
    
    // Track if user manually edits alias (on focus/change)
    aliasInput.addEventListener('focus', function() {
        aliasManuallyEdited = true;
    });
    
    // Auto-generate alias when name changes (only if alias hasn't been manually edited)
    nameInput.addEventListener('input', function() {
        if (!aliasManuallyEdited && this.value) {
            const slug = this.value
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/Đ/g, 'D')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        
        aliasInput.value = slug;
    }
});

    // Also generate on blur if alias is empty
    nameInput.addEventListener('blur', function() {
        if (!aliasInput.value && this.value) {
            aliasManuallyEdited = false; // Reset flag if alias was empty
            const slug = this.value
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd')
                .replace(/Đ/g, 'D')
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
            
            aliasInput.value = slug;
        }
    });
})();

</script>
@endpush
@endsection

