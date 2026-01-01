@extends('admin.layouts.master')

@section('title', 'Thêm tin tức mới')

@php
$breadcrumbs = [
    ['label' => 'Tin tức', 'url' => route('admin.news.index')],
    ['label' => 'Thêm mới', 'url' => route('admin.news.create')]
];
@endphp

@push('styles')
<style>
    .image-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        background: #f9fafb;
        cursor: pointer;
    }
    
    .image-upload-area:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }
    
    .preview-image {
        width: 100%;
        max-height: 300px;
        object-fit: contain;
        background: #f9fafb;
        border-radius: 8px;
        padding: 16px;
        margin-top: 12px;
    }
    
    .image-preview-container {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .remove-image-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 32px;
        height: 32px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .remove-image-btn:hover {
        background: rgba(220, 38, 38, 1);
        transform: scale(1.1);
    }
    
    .category-checkbox-item {
        transition: all 0.2s ease;
        animation: fadeIn 0.3s ease-out;
    }
    
    .category-checkbox-item input[type="checkbox"]:checked + span {
        color: #2563eb;
        font-weight: 600;
    }
    
    .category-checkbox-item.level-0 {
        border-left: 3px solid transparent;
        margin-bottom: 6px;
    }
    
    .category-checkbox-item.level-0:hover {
        border-left-color: #2563eb;
    }
    
    .category-checkbox-item.level-0 label {
        background: linear-gradient(to right, rgba(239, 68, 68, 0.03), rgba(37, 99, 235, 0.03));
        border: 1px solid rgba(37, 99, 235, 0.1);
    }
    
    .category-checkbox-item.level-0 input[type="checkbox"]:checked ~ span,
    .category-checkbox-item.level-0:has(input[type="checkbox"]:checked) label {
        background: linear-gradient(to right, rgba(239, 68, 68, 0.08), rgba(37, 99, 235, 0.08));
        border-color: rgba(37, 99, 235, 0.3);
    }
    
    .category-checkbox-item.level-1 {
        margin-left: 8px;
    }
    
    .category-checkbox-item.level-2 {
        margin-left: 8px;
        opacity: 0.9;
    }
    
    .category-checkbox-item.hidden {
        display: none;
    }
    
    .category-checkbox:checked {
        background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);
        border-color: #2563eb;
        background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-3.5-3.5a1 1 0 011.414-1.414L4.5 10.586l6.293-6.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    #categoriesContainer::-webkit-scrollbar {
        width: 6px;
    }
    
    #categoriesContainer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    #categoriesContainer::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);
        border-radius: 3px;
    }
    
    #categoriesContainer::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #dc2626 0%, #1d4ed8 100%);
    }
</style>
@endpush

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle mr-3" style="color: #2563eb;"></i>
                Thêm tin tức mới
            </h1>
            <p class="mt-1 text-sm text-gray-500">Điền thông tin để tạo tin tức mới</p>
        </div>
        <a href="{{ route('admin.news.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
    </div>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" id="newsForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2" style="color: #2563eb;"></i>
                        Thông tin cơ bản
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tiêu đề tin tức <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('name') border-red-500 @enderror"
                                   placeholder="Nhập tiêu đề tin tức"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Alias (URL)
                            </label>
                            <input type="text" 
                                   name="alias" 
                                   value="{{ old('alias') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('alias') border-red-500 @enderror"
                                   placeholder="alias-tin-tuc (tự động nếu để trống)">
                            <p class="mt-1 text-xs text-gray-500">Để trống sẽ tự động tạo từ tiêu đề</p>
                            @error('alias')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mô tả ngắn
                            </label>
                            <textarea name="description" 
                                      id="description"
                                      rows="6"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('description') border-red-500 @enderror"
                                      placeholder="Nhập mô tả ngắn về tin tức">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nội dung chi tiết
                            </label>
                            <div class="relative">
                                <textarea name="content" 
                                          id="content"
                                          rows="15"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('content') border-red-500 @enderror"
                                          placeholder="Nhập nội dung chi tiết của tin tức">{{ old('content') }}</textarea>
                                <button type="button" 
                                        onclick="insertDefaultContent()"
                                        id="insertDefaultContentBtn"
                                        class="absolute bottom-3 right-3 inline-flex items-center px-3 py-1.5 text-xs font-medium text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 z-10"
                                        style="background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);">
                                    <i class="fas fa-magic mr-1.5"></i>
                                    Chèn text mặc định
                                </button>
                            </div>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO Information -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-search mr-2" style="color: #2563eb;"></i>
                        SEO & Meta
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Từ khóa (Keywords)
                            </label>
                            <input type="text" 
                                   name="kw" 
                                   value="{{ old('kw') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('kw') border-red-500 @enderror"
                                   placeholder="Nhập từ khóa, phân cách bằng dấu phẩy">
                            <p class="mt-1 text-xs text-gray-500">Ví dụ: tin tức, báo chí, thời sự</p>
                            @error('kw')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea name="meta_description" 
                                      rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('meta_description') border-red-500 @enderror"
                                      placeholder="Nhập mô tả ngắn cho SEO (tối đa 500 ký tự)">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Mô tả ngắn gọn về tin tức để tối ưu SEO</p>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Categories -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-tags mr-2" style="color: #2563eb;"></i>
                            Danh mục
                        </h2>
                        <span id="selectedCount" class="px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-red-100 to-blue-100 text-blue-700 border border-blue-200">
                            0 đã chọn
                        </span>
                    </div>
                    
                    <!-- Search Box -->
                    <div class="mb-3">
                        <div class="relative">
                            <input type="text" 
                                   id="categorySearch"
                                   placeholder="Tìm kiếm danh mục..." 
                                   class="w-full px-4 py-2 pl-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <button type="button" 
                                    onclick="clearCategorySearch()"
                                    id="clearSearchBtn"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-2 mb-3">
                        <button type="button" 
                                onclick="selectAllCategories()"
                                class="flex-1 px-3 py-2 text-xs font-semibold bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-check-square mr-1"></i>Chọn tất cả
                        </button>
                        <button type="button" 
                                onclick="deselectAllCategories()"
                                class="flex-1 px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-300">
                            <i class="fas fa-square mr-1"></i>Bỏ chọn
                        </button>
                    </div>
                    
                    <!-- Categories List -->
                    <div class="max-h-80 overflow-y-auto border border-gray-200 rounded-lg p-3 bg-white custom-scrollbar shadow-inner" id="categoriesContainer">
                        @if(isset($categories) && count($categories) > 0)
                            @foreach($categories as $cat)
                                <div class="category-checkbox-item level-{{ $cat['level'] }}" 
                                     data-category-id="{{ $cat['id'] }}"
                                     data-category-name="{{ strtolower($cat['name']) }}"
                                     style="padding-left: {{ $cat['level'] * 16 }}px; margin-bottom: 4px;">
                                    <label class="flex items-center cursor-pointer py-2 px-3 rounded-lg transition-all duration-200 hover:bg-gradient-to-r hover:from-red-50 hover:to-blue-50 group">
                                        <div class="relative flex items-center flex-1">
                                            <input type="checkbox" 
                                                   name="categories[]" 
                                                   value="{{ $cat['id'] }}"
                                                   id="cat_{{ $cat['id'] }}"
                                                   {{ in_array($cat['id'], old('categories', [])) ? 'checked' : '' }}
                                                   class="category-checkbox w-5 h-5 text-red-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:ring-offset-1 transition-all cursor-pointer"
                                                   onchange="updateSelectedCount()">
                                            <span class="ml-3 text-sm flex-1 {{ $cat['level'] == 0 ? 'font-semibold text-gray-900' : ($cat['level'] == 1 ? 'font-medium text-gray-700' : 'font-normal text-gray-600') }}">
                                                @if($cat['level'] > 0)
                                                    <i class="fas fa-chevron-right text-xs text-gray-400 mr-1"></i>
                                                @endif
                                                {{ $cat['name'] }}
                                            </span>
                                            @if($cat['level'] == 0)
                                                <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    Parent
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-500">Chưa có danh mục nào</p>
                            </div>
                        @endif
                    </div>
                    @error('categories')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-image mr-2" style="color: #2563eb;"></i>
                        Hình ảnh
                    </h2>
                    
                    <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('url_img').click()">
                        <input type="file" 
                               id="url_img" 
                               name="url_img" 
                               accept="image/*" 
                               class="hidden"
                               onchange="handleImageUpload(event)">
                        <div id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-600 font-medium">Nhấp để tải ảnh lên</p>
                            <p class="text-xs text-gray-500 mt-1">hoặc kéo thả ảnh vào đây</p>
                            <p class="text-xs text-gray-400 mt-2">JPG, PNG, GIF (tối đa 2MB)</p>
                        </div>
                        <div id="imagePreview" class="hidden">
                            <div class="image-preview-container">
                                <img id="previewImage" src="" alt="Preview" class="preview-image">
                                <button type="button" class="remove-image-btn" onclick="removeImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('url_img')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Settings -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cog mr-2" style="color: #2563eb;"></i>
                        Cài đặt
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Thứ tự sắp xếp
                            </label>
                            <input type="number" 
                                   name="weight" 
                                   value="{{ old('weight', 0) }}"
                                   min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('weight') border-red-500 @enderror"
                                   placeholder="0">
                            <p class="mt-1 text-xs text-gray-500">Số càng nhỏ, hiển thị càng trước</p>
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="hidden" 
                                       value="1"
                                       {{ old('hidden') == '1' ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">Lưu dưới dạng bản nháp</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Bản nháp sẽ không hiển thị trên website</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                            <i class="fas fa-save mr-2"></i>
                            Lưu tin tức
                        </button>
                        <a href="{{ route('admin.news.index') }}" 
                           class="block w-full px-4 py-3 text-center border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                            <i class="fas fa-times mr-2"></i>
                            Hủy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Include CKEditor Component --}}
@include('admin.components.ckeditor', [
    'editorIds' => ['description', 'content']
])

<script>
// Default content template
const defaultContentTemplate = `<h4><strong>Bạn được trải nghiệm những g&igrave;?</strong></h4>

<p>Nếu bạn l&agrave; một người l&atilde;o thị nhưng trước đ&oacute; bạn cũng đ&atilde; bị cận thị, bạn cần sử dụng 2 cặp k&iacute;nh ri&ecirc;ng biệt : Một cặp k&iacute;nh để nh&igrave;n xa, v&agrave; một cặp k&iacute;nh kh&aacute;c để nh&igrave;n gần. Nếu bạn sử dụng &lsquo;k&iacute;nh l&atilde;o&rsquo;, điều n&agrave;y khiến bạn trở n&ecirc;n gi&agrave; đi khi cứ phải đeo v&agrave; th&aacute;o k&iacute;nh mỗi khi cần đọc s&aacute;ch b&aacute;o hoặc viết chữ Nếu bạn sử dụng &lsquo;k&iacute;nh hai tr&ograve;ng&rsquo;, bạn sẽ gặp kh&oacute; khăn khi chuyển v&ugrave;ng nh&igrave;n từ xa tới gần do c&oacute; đường ph&acirc;n c&aacute;ch giữa hai v&ugrave;ng nh&igrave;n v&agrave; h&igrave;nh ảnh của bạn trở n&ecirc;n gi&agrave; nua, k&eacute;m năng động.</p>

<p><img alt="" src="https://matkinhsaigon.com.vn/public/upload/images/Essilor/%C4%90%E1%BB%95i%20M%C3%A0u%20Gen8/Transitions%20Gen8_2.png" style="margin:auto" /></p>

<p>&nbsp;</p>

<div class="row">
<div class="col-lg-6 col-md-12 col-sm-12 col-xl-6">
<h4><strong>Bạn được trải nghiệm những g&igrave;?</strong></h4>

<p><span style="color:#000000; font-family:be vietnam,sans-serif; font-size:16px">Ngoài ra, m&ocirc;̣t đi&ecirc;̀u đặc bi&ecirc;̣t b&acirc;́t ngờ trong l&acirc;̀n tái xu&acirc;́t này, Transitions&reg; kh&ocirc;ng chỉ xu&acirc;́t hi&ecirc;̣n với 3 màu sắc quen thu&ocirc;̣c trước kia (Kh&oacute;i, Tr&agrave;, Xanh Graphite) mà mang tới t&acirc;̣n 7 m&agrave;u sắc đ&ocirc;̣c đáo đ&ecirc;̉ chinh phục hoàn toàn những khách hàng sành đi&ecirc;̣u, y&ecirc;u thời trang. Với b&ocirc;̣ sưu t&acirc;̣p Transitions&reg; Style Colors, bạn c&oacute; thể thỏa thích lựa chọn m&agrave;u th&ecirc;m 4 sắc màu c&aacute; t&iacute;nh nữa cho phong cách ri&ecirc;ng của mình (Ngọc Lục Bảo, Xanh Sapphire, Thạch Anh T&iacute;m, Hổ Ph&aacute;ch).</span></p>
</div>

<div class="col-lg-6 col-md-12 col-sm-12 col-xl-6"><img alt="" src="https://matkinhsaigon.com.vn/public/upload/images/Essilor/%C4%90%E1%BB%95i%20M%C3%A0u%20Gen8/Transitions%20Gen8_2.png" style="margin:auto" /></div>
</div>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>Nếu bạn l&agrave; một người l&atilde;o thị nhưng trước đ&oacute; bạn cũng đ&atilde; bị cận thị, bạn cần sử dụng 2 cặp k&iacute;nh ri&ecirc;ng biệt : Một cặp k&iacute;nh để nh&igrave;n xa, v&agrave; một cặp k&iacute;nh kh&aacute;c để nh&igrave;n gần. Nếu bạn sử dụng &lsquo;k&iacute;nh l&atilde;o&rsquo;, điều n&agrave;y khiến bạn trở n&ecirc;n gi&agrave; đi khi cứ phải đeo v&agrave; th&aacute;o k&iacute;nh mỗi khi cần đọc s&aacute;ch b&aacute;o hoặc viết chữ Nếu bạn sử dụng &lsquo;k&iacute;nh hai tr&ograve;ng&rsquo;, bạn sẽ gặp kh&oacute; khăn khi chuyển v&ugrave;ng nh&igrave;n từ xa tới gần do c&oacute; đường ph&acirc;n c&aacute;ch giữa hai v&ugrave;ng nh&igrave;n v&agrave; h&igrave;nh ảnh của bạn trở n&ecirc;n gi&agrave; nua, k&eacute;m năng động.</p>

<p>&nbsp;</p>

<h4><strong>Bạn được trải nghiệm những g&igrave;?</strong></h4>

<p>Nếu bạn l&agrave; một người l&atilde;o thị nhưng trước đ&oacute; bạn cũng đ&atilde; bị cận thị, bạn cần sử dụng 2 cặp k&iacute;nh ri&ecirc;ng biệt : Một cặp k&iacute;nh để nh&igrave;n xa, v&agrave; một cặp k&iacute;nh kh&aacute;c để nh&igrave;n gần. Nếu bạn sử dụng &lsquo;k&iacute;nh l&atilde;o&rsquo;, điều n&agrave;y khiến bạn trở n&ecirc;n gi&agrave; đi khi cứ phải đeo v&agrave; th&aacute;o k&iacute;nh mỗi khi cần đọc s&aacute;ch b&aacute;o hoặc viết chữ Nếu bạn sử dụng &lsquo;k&iacute;nh hai tr&ograve;ng&rsquo;, bạn sẽ gặp kh&oacute; khăn khi chuyển v&ugrave;ng nh&igrave;n từ xa tới gần do c&oacute; đường ph&acirc;n c&aacute;ch giữa hai v&ugrave;ng nh&igrave;n v&agrave; h&igrave;nh ảnh của bạn trở n&ecirc;n gi&agrave; nua, k&eacute;m năng động.</p>

<ul>
	<li>8:30 gặp hướng dẫn vi&ecirc;n</li>
	<li>9:00, bắt đầu tour, tham quan chợ nổi Damnoen Saduak</li>
	<li>Đi xe lửa qua Chợ Đường Sắt Maeklong</li>
	<li>L&ecirc;n thuyền v&agrave; kh&aacute;m ph&aacute; chợ nổi</li>
</ul>

<p>Nếu bạn l&agrave; một người l&atilde;o thị nhưng trước đ&oacute; bạn cũng đ&atilde; bị cận thị, bạn cần sử dụng 2 cặp k&iacute;nh ri&ecirc;ng biệt : Một cặp k&iacute;nh để nh&igrave;n xa, v&agrave; một cặp k&iacute;nh kh&aacute;c để nh&igrave;n gần. Nếu bạn sử dụng &lsquo;k&iacute;nh l&atilde;o&rsquo;, điều n&agrave;y khiến bạn trở n&ecirc;n gi&agrave; đi khi cứ phải đeo v&agrave; th&aacute;o k&iacute;nh mỗi khi cần đọc s&aacute;ch b&aacute;o hoặc viết chữ Nếu bạn sử dụng &lsquo;k&iacute;nh hai tr&ograve;ng&rsquo;, bạn sẽ gặp kh&oacute; khăn khi chuyển v&ugrave;ng nh&igrave;n từ xa tới gần do c&oacute; đường ph&acirc;n c&aacute;ch giữa hai v&ugrave;ng nh&igrave;n v&agrave; h&igrave;nh ảnh của bạn trở n&ecirc;n gi&agrave; nua, k&eacute;m năng động.</p>`;

// Function to insert default content into CKEditor
function insertDefaultContent() {
    try {
        // Check if CKEditor is loaded
        if (typeof CKEDITOR === 'undefined') {
            alert('CKEditor chưa được tải. Vui lòng đợi vài giây rồi thử lại.');
            return;
        }
        
        // Try to get editor instance for 'content'
        let contentEditor = null;
        
        // Method 1: Try window.ckEditors first
        if (typeof window.ckEditors !== 'undefined' && window.ckEditors['content']) {
            contentEditor = window.ckEditors['content'];
        } 
        // Method 2: Try CKEDITOR.instances
        else if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && CKEDITOR.instances['content']) {
            contentEditor = CKEDITOR.instances['content'];
        }
        // Method 3: Try to get by element
        else {
            const textarea = document.getElementById('content');
            if (textarea && textarea.id) {
                contentEditor = CKEDITOR.instances[textarea.id];
            }
        }
        
        if (!contentEditor) {
            alert('Không tìm thấy CKEditor cho trường "Nội dung chi tiết". Vui lòng đợi vài giây rồi thử lại.');
            return;
        }
        
        // Get current content
        const currentContent = contentEditor.getData() || '';
        
        // Insert default content (append to existing content if any)
        const newContent = currentContent ? currentContent + '\n\n' + defaultContentTemplate : defaultContentTemplate;
        
        // Set the content
        contentEditor.setData(newContent);
        
        // Show success message
        const button = document.querySelector('button[onclick="insertDefaultContent()"]');
        if (button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-1.5"></i>Đã chèn!';
            button.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = 'linear-gradient(135deg, #ef4444 0%, #2563eb 100%)';
            }, 2000);
        }
        
        // Focus the editor
        contentEditor.focus();
    } catch (error) {
        console.error('Error inserting default content:', error);
        alert('Có lỗi xảy ra khi chèn nội dung mặc định. Vui lòng thử lại.');
    }
}

// Move button to CKEditor container after CKEditor is initialized
function moveButtonToCKEditor() {
    const ckeContainer = document.querySelector('#cke_content');
    const button = document.getElementById('insertDefaultContentBtn');
    
    if (ckeContainer && button) {
        // Make CKEditor container relative
        ckeContainer.style.position = 'relative';
        // Move button to CKEditor container
        if (button.parentElement !== ckeContainer) {
            ckeContainer.appendChild(button);
        }
        // Update button position
        button.style.position = 'absolute';
        button.style.bottom = '12px';
        button.style.right = '12px';
        button.style.zIndex = '1000';
    }
}

// Wait for CKEditor to initialize and move button
setTimeout(function() {
    moveButtonToCKEditor();
    // Also try after a longer delay in case CKEditor loads slowly
    setTimeout(moveButtonToCKEditor, 1000);
}, 500);

// Form submit handler - sync CKEditor before submit
document.getElementById('newsForm').addEventListener('submit', function(e) {
    // Sync all CKEditor instances before submit
    if (typeof window.syncCKEditors === 'function') {
        window.syncCKEditors();
    }
});

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2048 * 1024) {
            alert('Kích thước ảnh không được vượt quá 2MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            showImagePreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function showImagePreview(imageSrc) {
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    
    if (placeholder) placeholder.classList.add('hidden');
    if (preview) {
        preview.classList.remove('hidden');
        if (previewImage) previewImage.src = imageSrc;
    }
}

function removeImage() {
    const placeholder = document.getElementById('uploadPlaceholder');
    const preview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    const imageInput = document.getElementById('url_img');
    
    if (placeholder) placeholder.classList.remove('hidden');
    if (preview) preview.classList.add('hidden');
    if (previewImage) previewImage.src = '';
    if (imageInput) imageInput.value = '';
}

// Category Management Functions
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.category-checkbox:checked');
    const countElement = document.getElementById('selectedCount');
    if (countElement) {
        const count = checkboxes.length;
        countElement.textContent = count + ' đã chọn';
        if (count > 0) {
            countElement.classList.remove('bg-gradient-to-r', 'from-red-100', 'to-blue-100', 'text-blue-700');
            countElement.classList.add('bg-gradient-to-r', 'from-red-500', 'to-blue-500', 'text-white');
        } else {
            countElement.classList.remove('bg-gradient-to-r', 'from-red-500', 'to-blue-500', 'text-white');
            countElement.classList.add('bg-gradient-to-r', 'from-red-100', 'to-blue-100', 'text-blue-700');
        }
    }
}

function selectAllCategories() {
    const visibleCheckboxes = document.querySelectorAll('.category-checkbox-item:not(.hidden) .category-checkbox');
    visibleCheckboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    updateSelectedCount();
}

function deselectAllCategories() {
    const checkboxes = document.querySelectorAll('.category-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    updateSelectedCount();
}

function clearCategorySearch() {
    const searchInput = document.getElementById('categorySearch');
    const clearBtn = document.getElementById('clearSearchBtn');
    if (searchInput) {
        searchInput.value = '';
        filterCategories('');
    }
    if (clearBtn) {
        clearBtn.classList.add('hidden');
    }
}

function filterCategories(searchTerm) {
    const term = searchTerm.toLowerCase().trim();
    const items = document.querySelectorAll('.category-checkbox-item');
    const clearBtn = document.getElementById('clearSearchBtn');
    let visibleCount = 0;
    
    items.forEach(item => {
        const categoryName = item.getAttribute('data-category-name') || '';
        if (term === '' || categoryName.includes(term)) {
            item.classList.remove('hidden');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });
    
    if (clearBtn) {
        if (term !== '') {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }
    
    // Show message if no results
    const container = document.getElementById('categoriesContainer');
    let noResultsMsg = container.querySelector('.no-results-message');
    if (visibleCount === 0 && term !== '') {
        if (!noResultsMsg) {
            noResultsMsg = document.createElement('div');
            noResultsMsg.className = 'no-results-message text-center py-8';
            noResultsMsg.innerHTML = '<i class="fas fa-search text-4xl text-gray-300 mb-3"></i><p class="text-sm text-gray-500">Không tìm thấy danh mục nào</p>';
            container.appendChild(noResultsMsg);
        }
        noResultsMsg.classList.remove('hidden');
    } else if (noResultsMsg) {
        noResultsMsg.classList.add('hidden');
    }
}

// Initialize category search
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('categorySearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            filterCategories(e.target.value);
        });
    }
    
    // Initialize selected count
    updateSelectedCount();
    
    // Add change listeners to all checkboxes
    const checkboxes = document.querySelectorAll('.category-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
});

// Drag and drop for image upload
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('url_img');
    
    if (uploadArea && imageInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.style.borderColor = '#2563eb';
                uploadArea.style.background = '#dbeafe';
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.style.borderColor = '#d1d5db';
                uploadArea.style.background = '#f9fafb';
            }, false);
        });
        
        uploadArea.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                imageInput.files = files;
                handleImageUpload({ target: imageInput });
            }
        }, false);
    }
});
</script>
@endpush

