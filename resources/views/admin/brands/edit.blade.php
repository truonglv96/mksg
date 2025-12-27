@extends('admin.layouts.master')

@section('title', 'Sửa thương hiệu')

@php
$breadcrumbs = [
    ['label' => 'Thương hiệu', 'url' => route('admin.brands.index')],
    ['label' => 'Sửa', 'url' => route('admin.brands.edit', $brand->id)]
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
    }
    
    .image-upload-area:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .preview-image {
        width: 100%;
        height: 200px;
        object-fit: contain;
        background: #f9fafb;
        border-radius: 8px;
        padding: 16px;
    }
    
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
    }
    
    .preview-item {
        position: relative;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        background: #f9fafb;
    }
    
    .preview-item img {
        width: 100%;
        height: 150px;
        object-fit: contain;
        padding: 8px;
    }
    
    .preview-item .remove-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 28px;
        height: 28px;
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
    
    .preview-item .remove-btn:hover {
        background: rgba(220, 38, 38, 1);
        transform: scale(1.1);
    }
    
    .existing-image {
        position: relative;
    }
    
    .existing-image input[type="checkbox"] {
        position: absolute;
        top: 8px;
        left: 8px;
        width: 24px;
        height: 24px;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-edit text-primary-600 mr-3"></i>
                Sửa thương hiệu: {{ $brand->name }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">Cập nhật thông tin thương hiệu</p>
        </div>
        <a href="{{ route('admin.brands.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
    </div>

    <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" id="brandForm">
        @csrf
        @method('PUT')
        
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
                        <!-- Name -->
                        @include('admin.helpers.form-input', [
                            'name' => 'name',
                            'label' => 'Tên thương hiệu',
                            'type' => 'text',
                            'value' => old('name', $brand->name),
                            'placeholder' => 'Nhập tên thương hiệu',
                            'required' => true
                        ])
                        
                        <!-- Alias -->
                        @include('admin.helpers.form-input', [
                            'name' => 'alias',
                            'label' => 'Alias (URL)',
                            'type' => 'text',
                            'value' => old('alias', $brand->alias),
                            'placeholder' => 'ten-thuong-hieu',
                            'required' => true,
                            'attributes' => ['id' => 'alias']
                        ])
                        
                        <!-- Content -->
                        @include('admin.helpers.form-textarea', [
                            'name' => 'content',
                            'label' => 'Mô tả / Nội dung',
                            'value' => old('content', $brand->content),
                            'placeholder' => 'Mô tả về thương hiệu...',
                            'rows' => 6,
                            'attributes' => ['id' => 'content']
                        ])
                    </div>
                </div>
                
                <!-- Images Section -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-images text-primary-600 mr-2"></i>
                        Hình ảnh thương hiệu
                    </h2>
                    
                    <div class="space-y-4">
                        <!-- Existing Images -->
                        @php
                            $existingImages = [];
                            if ($brand->url_imgs) {
                                $existingImages = is_string($brand->url_imgs) ? json_decode($brand->url_imgs, true) : $brand->url_imgs;
                                if (!is_array($existingImages)) {
                                    $existingImages = [];
                                }
                            }
                        @endphp
                        
                        @if(count($existingImages) > 0 || ($brand->images && $brand->images->count() > 0))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ảnh hiện có (tích để xóa)
                                </label>
                                <div class="preview-grid">
                                    @foreach($existingImages as $index => $image)
                                        <div class="preview-item existing-image">
                                            <input type="checkbox" name="delete_images[]" value="{{ $index }}" class="absolute top-2 left-2 z-10 w-6 h-6">
                                            <img src="{{ asset('img/brand/' . $image) }}" alt="Brand image">
                                        </div>
                                    @endforeach
                                    @if(isset($brand->images))
                                        @foreach($brand->images as $brandImage)
                                            <div class="preview-item existing-image">
                                                <input type="checkbox" name="delete_brand_images[]" value="{{ $brandImage->id }}" class="absolute top-2 left-2 z-10 w-6 h-6">
                                                <img src="{{ $brandImage->getImage() }}" alt="Brand image">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <!-- New Images Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Thêm hình ảnh mới (có thể chọn nhiều)
                            </label>
                            <div class="image-upload-area" id="imageUploadArea">
                                <input type="file" 
                                       name="images[]" 
                                       id="imagesInput"
                                       multiple
                                       accept="image/*"
                                       class="hidden">
                                <div class="cursor-pointer" onclick="document.getElementById('imagesInput').click()">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-sm text-gray-600 mb-1">Click hoặc kéo thả để tải ảnh lên</p>
                                    <p class="text-xs text-gray-500">Hỗ trợ: JPG, PNG, GIF</p>
                                </div>
                            </div>
                            <div id="imagesPreview" class="preview-grid mt-4"></div>
                            @error('images.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Logo and Settings -->
            <div class="space-y-6">
                <!-- Logo Upload -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-image text-primary-600 mr-2"></i>
                        Logo thương hiệu
                    </h2>
                    
                    <div class="space-y-4">
                        @if($brand->logo)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Logo hiện tại
                                </label>
                                <img src="{{ asset('img/brand/' . $brand->logo) }}" alt="Current logo" class="preview-image mb-3">
                                <label class="flex items-center text-sm text-red-600 cursor-pointer">
                                    <input type="checkbox" name="delete_logo" value="1" class="mr-2">
                                    Xóa logo hiện tại
                                </label>
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $brand->logo ? 'Thay đổi logo' : 'Logo' }} <span class="text-red-500">{{ !$brand->logo ? '*' : '' }}</span>
                            </label>
                            <input type="file" 
                                   name="logo" 
                                   id="logoInput"
                                   accept="image/*"
                                   {{ !$brand->logo ? 'required' : '' }}
                                   class="hidden">
                            <div class="image-upload-area cursor-pointer" id="logoUploadArea" onclick="document.getElementById('logoInput').click()">
                                <div id="logoPreview" class="hidden">
                                    <img id="logoPreviewImg" src="" alt="Logo preview" class="preview-image mb-3">
                                    <button type="button" onclick="removeLogoPreview()" class="text-sm text-red-600 hover:text-red-700">
                                        <i class="fas fa-times mr-1"></i>Xóa
                                    </button>
                                </div>
                                <div id="logoUploadText">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                                    <p class="text-sm text-gray-600 mb-1">Click để tải logo lên</p>
                                    <p class="text-xs text-gray-500">Khuyến nghị: 300x300px</p>
                                </div>
                            </div>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                        <!-- Weight -->
                        @include('admin.helpers.form-input', [
                            'name' => 'weight',
                            'label' => 'Thứ tự hiển thị',
                            'type' => 'number',
                            'value' => old('weight', $brand->weight ?? 0),
                            'min' => 0,
                            'placeholder' => '0'
                        ])
                        
                        <!-- Hidden -->
                        <div>
                            <input type="hidden" name="hidden" value="0">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="hidden" 
                                       value="1" 
                                       {{ old('hidden', $brand->hidden ?? 1) == 1 ? 'checked' : '' }}
                                       class="mr-2 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm font-medium text-gray-700">Hiển thị</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.brands.index') }}" 
               class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                Hủy
            </a>
            <button type="submit" 
                    class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg hover:from-primary-700 hover:to-primary-800 transition-all duration-200 shadow-md"
                    style="background: linear-gradient(to right, #0284c7, #0369a1); color: white; border-radius: 0.5rem; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);">
                <i class="fas fa-save mr-2"></i>Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Include CKEditor Component --}}
@include('admin.components.ckeditor', [
    'editorIds' => ['content']
])

<script>
// Logo upload preview
document.getElementById('logoInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreviewImg').src = e.target.result;
            document.getElementById('logoPreview').classList.remove('hidden');
            document.getElementById('logoUploadText').classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
});

function removeLogoPreview() {
    document.getElementById('logoInput').value = '';
    document.getElementById('logoPreview').classList.add('hidden');
    document.getElementById('logoUploadText').classList.remove('hidden');
}

// Images upload preview
const imagesInput = document.getElementById('imagesInput');
const imagesPreview = document.getElementById('imagesPreview');
let imageFiles = [];

imagesInput?.addEventListener('change', function(e) {
    handleImages(e.target.files);
});

// Drag and drop for images
const imageUploadArea = document.getElementById('imageUploadArea');
imageUploadArea?.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
});

imageUploadArea?.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
});

imageUploadArea?.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    handleImages(e.dataTransfer.files);
});

function handleImages(files) {
    Array.from(files).forEach(file => {
        if (file.type.startsWith('image/')) {
            imageFiles.push(file);
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                previewItem.dataset.filename = file.name;
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="${file.name}">
                    <button type="button" class="remove-btn" onclick="removeImagePreview(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                imagesPreview.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        }
    });
    updateImagesInput();
}

function removeImagePreview(btn) {
    const item = btn.closest('.preview-item');
    const filename = item.dataset.filename;
    imageFiles = imageFiles.filter(f => f.name !== filename);
    item.remove();
    updateImagesInput();
}

function updateImagesInput() {
    const dt = new DataTransfer();
    imageFiles.forEach(file => dt.items.add(file));
    imagesInput.files = dt.files;
}
</script>
@endpush

