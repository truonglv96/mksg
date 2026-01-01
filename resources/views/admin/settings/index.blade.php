@extends('admin.layouts.master')

@section('title', 'Cài đặt hệ thống')

@php
$breadcrumbs = [
    ['label' => 'Cài đặt', 'url' => route('admin.settings.index')]
];
@endphp

@push('styles')
<style>
    .settings-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #e5e7eb;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }
    
    .settings-section:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .section-header h3 {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
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
    
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
        resize: vertical;
        min-height: 100px;
    }
    
    .form-textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }
    
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
    
    .image-upload-area.dragover {
        border-color: #2563eb;
        background: #dbeafe;
    }
    
    .preview-image {
        width: 100%;
        max-height: 200px;
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
    
    .icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .icon-upload-item {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        background: #f9fafb;
    }
    
    .icon-upload-item label {
        display: block;
        font-size: 12px;
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 8px;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);
        color: white;
        padding: 14px 32px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }
    
    .btn-save:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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
    
    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }
</style>
@endpush

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-cog mr-3" style="color: #2563eb;"></i>
                Cài đặt hệ thống
            </h1>
            <p class="mt-1 text-sm text-gray-500">Quản lý và cấu hình các thiết lập của website</p>
        </div>
    </div>

    <form id="settingsForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Thông tin liên hệ -->
        <div class="settings-section">
            <div class="section-header">
                <h3>
                    <i class="fas fa-phone-alt" style="color: #2563eb;"></i>
                    Thông tin liên hệ
                </h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label" for="hotline">
                        <i class="fas fa-phone mr-2 text-gray-400"></i>Hotline
                    </label>
                    <input type="text" 
                           id="hotline" 
                           name="hotline" 
                           class="form-input" 
                           value="{{ $settings->hotline ?? '' }}"
                           placeholder="Nhập số hotline">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-input" 
                           value="{{ $settings->email ?? '' }}"
                           placeholder="Nhập địa chỉ email">
                </div>
                
                <div class="form-group md:col-span-2">
                    <label class="form-label" for="address">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>Địa chỉ
                    </label>
                    <textarea id="address" 
                              name="address" 
                              class="form-textarea" 
                              rows="4"
                              placeholder="Nhập địa chỉ">{{ $settings->address ?? '' }}</textarea>
                </div>
                
                <div class="form-group md:col-span-2">
                    <label class="form-label" for="branch_address">
                        <i class="fas fa-building mr-2 text-gray-400"></i>Địa chỉ chi nhánh
                    </label>
                    <textarea id="branch_address" 
                              name="branch_address" 
                              class="form-textarea" 
                              rows="4"
                              placeholder="Nhập địa chỉ chi nhánh">{{ $settings->branch_address ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="work_time">
                        <i class="fas fa-clock mr-2 text-gray-400"></i>Giờ làm việc
                    </label>
                    <textarea id="work_time" 
                              name="work_time" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="VD: 8:00 - 17:00">{{ $settings->work_time ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="zalo">
                        <i class="fab fa-zalo mr-2 text-gray-400"></i>Zalo
                    </label>
                    <textarea id="zalo" 
                              name="zalo" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="Nhập số Zalo hoặc link Zalo">{{ $settings->zalo ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Mạng xã hội -->
        <div class="settings-section">
            <div class="section-header">
                <h3>
                    <i class="fas fa-share-alt" style="color: #2563eb;"></i>
                    Mạng xã hội
                </h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label" for="facebook">
                        <i class="fab fa-facebook mr-2 text-gray-400"></i>Facebook URL
                    </label>
                    <textarea id="facebook" 
                              name="facebook" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="https://facebook.com/...">{{ $settings->facebook ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="youtube">
                        <i class="fab fa-youtube mr-2 text-gray-400"></i>YouTube URL
                    </label>
                    <textarea id="youtube" 
                              name="youtube" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="https://youtube.com/...">{{ $settings->youtube ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="google_plus">
                        <i class="fab fa-google-plus mr-2 text-gray-400"></i>Google Plus URL
                    </label>
                    <textarea id="google_plus" 
                              name="google_plus" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="https://plus.google.com/...">{{ $settings->google_plus ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="facebook_fb">
                        <i class="fab fa-facebook-f mr-2 text-gray-400"></i>Facebook FB URL
                    </label>
                    <textarea id="facebook_fb" 
                              name="facebook_fb" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="https://fb.com/...">{{ $settings->facebook_fb ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- SEO & Meta -->
        <div class="settings-section">
            <div class="section-header">
                <h3>
                    <i class="fas fa-search" style="color: #2563eb;"></i>
                    SEO & Meta Tags
                </h3>
            </div>
            
            <div class="space-y-4">
                <div class="form-group">
                    <label class="form-label" for="meta_title">
                        <i class="fas fa-heading mr-2 text-gray-400"></i>Meta Title
                    </label>
                    <input type="text" 
                           id="meta_title" 
                           name="meta_title" 
                           class="form-input" 
                           value="{{ $settings->meta_title ?? '' }}"
                           placeholder="Tiêu đề SEO">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="meta_keyword">
                        <i class="fas fa-tags mr-2 text-gray-400"></i>Meta Keywords
                    </label>
                    <input type="text" 
                           id="meta_keyword" 
                           name="meta_keyword" 
                           class="form-input" 
                           value="{{ $settings->meta_keyword ?? '' }}"
                           placeholder="Từ khóa SEO, phân cách bằng dấu phẩy">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="meta_description">
                        <i class="fas fa-align-left mr-2 text-gray-400"></i>Meta Description
                    </label>
                    <textarea id="meta_description" 
                              name="meta_description" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="Mô tả SEO (tối đa 500 ký tự)">{{ $settings->meta_description ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="google_analytic">
                        <i class="fas fa-chart-line mr-2 text-gray-400"></i>Google Analytics Code
                    </label>
                    <textarea id="google_analytic" 
                              name="google_analytic" 
                              class="form-textarea" 
                              rows="6"
                              placeholder="Nhập mã Google Analytics...">{{ $settings->google_analytic ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Hình ảnh -->
        <div class="settings-section">
            <div class="section-header">
                <h3>
                    <i class="fas fa-images" style="color: #2563eb;"></i>
                    Hình ảnh & Logo
                </h3>
            </div>
            
            <div class="space-y-6">
                <!-- Logo -->
                <div class="form-group">
                    <label class="form-label" for="logo">
                        <i class="fas fa-image mr-2 text-gray-400"></i>Logo
                    </label>
                    <div class="image-upload-area" id="logoUploadArea" onclick="document.getElementById('logoInput').click()">
                        <input type="file" 
                               id="logoInput" 
                               name="logo" 
                               accept="image/*" 
                               class="hidden"
                               onchange="handleImageUpload(event, 'logo')">
                        <div id="logoPlaceholder">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-600 font-medium">Nhấp để tải logo lên</p>
                            <p class="text-xs text-gray-500 mt-1">hoặc kéo thả ảnh vào đây</p>
                        </div>
                        <div id="logoPreview" class="hidden">
                            <div class="image-preview-container">
                                <img id="logoPreviewImage" src="" alt="Logo Preview" class="preview-image">
                                <button type="button" class="remove-image-btn" onclick="removeImage('logo')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if($settings->logo)
                        <div class="mt-2">
                            <img src="{{ $settings->getLogo() }}" alt="Current Logo" class="max-h-20">
                        </div>
                    @endif
                </div>
                
                <!-- Icons -->
                <div class="form-group">
                    <label class="form-label mb-4">
                        <i class="fas fa-icons mr-2 text-gray-400"></i>Icons
                    </label>
                    <div class="icon-grid">
                        <!-- Facebook Icon -->
                        <div class="icon-upload-item">
                            <label>Icon Facebook</label>
                            <div class="image-upload-area" style="padding: 12px;" onclick="document.getElementById('iconFbInput').click()">
                                <input type="file" id="iconFbInput" name="icon_fb" accept="image/*" class="hidden" onchange="handleImageUpload(event, 'icon_fb')">
                                <div id="iconFbPlaceholder">
                                    <i class="fas fa-upload text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500">Upload</p>
                                </div>
                                <div id="iconFbPreview" class="hidden">
                                    <img id="iconFbPreviewImage" src="" alt="Preview" class="preview-image" style="max-height: 80px;">
                                </div>
                            </div>
                            @if($settings->icon_fb)
                                <div class="mt-2">
                                    <img src="{{ $settings->getIconFB() }}" alt="Current" class="max-h-12">
                                </div>
                            @endif
                        </div>
                        
                        <!-- YouTube Icon -->
                        <div class="icon-upload-item">
                            <label>Icon YouTube</label>
                            <div class="image-upload-area" style="padding: 12px;" onclick="document.getElementById('iconYoutubeInput').click()">
                                <input type="file" id="iconYoutubeInput" name="icon_youtube" accept="image/*" class="hidden" onchange="handleImageUpload(event, 'icon_youtube')">
                                <div id="iconYoutubePlaceholder">
                                    <i class="fas fa-upload text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500">Upload</p>
                                </div>
                                <div id="iconYoutubePreview" class="hidden">
                                    <img id="iconYoutubePreviewImage" src="" alt="Preview" class="preview-image" style="max-height: 80px;">
                                </div>
                            </div>
                            @if($settings->icon_youtube)
                                <div class="mt-2">
                                    <img src="{{ $settings->getIconYoutube() }}" alt="Current" class="max-h-12">
                                </div>
                            @endif
                        </div>
                        
                        <!-- Zalo Icon -->
                        <div class="icon-upload-item">
                            <label>Icon Zalo</label>
                            <div class="image-upload-area" style="padding: 12px;" onclick="document.getElementById('iconZaloInput').click()">
                                <input type="file" id="iconZaloInput" name="icon_zalo" accept="image/*" class="hidden" onchange="handleImageUpload(event, 'icon_zalo')">
                                <div id="iconZaloPlaceholder">
                                    <i class="fas fa-upload text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500">Upload</p>
                                </div>
                                <div id="iconZaloPreview" class="hidden">
                                    <img id="iconZaloPreviewImage" src="" alt="Preview" class="preview-image" style="max-height: 80px;">
                                </div>
                            </div>
                            @if($settings->icon_zalo)
                                <div class="mt-2">
                                    <img src="{{ $settings->getIconZalo() }}" alt="Current" class="max-h-12">
                                </div>
                            @endif
                        </div>
                        
                        <!-- Email Icon -->
                        <div class="icon-upload-item">
                            <label>Icon Email</label>
                            <div class="image-upload-area" style="padding: 12px;" onclick="document.getElementById('iconEmailInput').click()">
                                <input type="file" id="iconEmailInput" name="icon_email" accept="image/*" class="hidden" onchange="handleImageUpload(event, 'icon_email')">
                                <div id="iconEmailPlaceholder">
                                    <i class="fas fa-upload text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500">Upload</p>
                                </div>
                                <div id="iconEmailPreview" class="hidden">
                                    <img id="iconEmailPreviewImage" src="" alt="Preview" class="preview-image" style="max-height: 80px;">
                                </div>
                            </div>
                            @if($settings->icon_email)
                                <div class="mt-2">
                                    <img src="{{ $settings->getIconEmail() }}" alt="Current" class="max-h-12">
                                </div>
                            @endif
                        </div>
                        
                        <!-- Time Icon -->
                        <div class="icon-upload-item">
                            <label>Icon Time</label>
                            <div class="image-upload-area" style="padding: 12px;" onclick="document.getElementById('iconTimeInput').click()">
                                <input type="file" id="iconTimeInput" name="icon_time" accept="image/*" class="hidden" onchange="handleImageUpload(event, 'icon_time')">
                                <div id="iconTimePlaceholder">
                                    <i class="fas fa-upload text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500">Upload</p>
                                </div>
                                <div id="iconTimePreview" class="hidden">
                                    <img id="iconTimePreviewImage" src="" alt="Preview" class="preview-image" style="max-height: 80px;">
                                </div>
                            </div>
                            @if($settings->icon_time)
                                <div class="mt-2">
                                    <img src="{{ $settings->getIconTime() }}" alt="Current" class="max-h-12">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung -->
        <div class="settings-section">
            <div class="section-header">
                <h3>
                    <i class="fas fa-file-alt" style="color: #2563eb;"></i>
                    Nội dung
                </h3>
            </div>
            
            <div class="space-y-6">
                <div class="form-group">
                    <label class="form-label" for="about">
                        <i class="fas fa-info-circle mr-2 text-gray-400"></i>Giới thiệu
                    </label>
                    <textarea id="about" 
                              name="about" 
                              class="form-textarea" 
                              rows="8"
                              placeholder="Nhập nội dung giới thiệu...">{{ $settings->about ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="info">
                        <i class="fas fa-info mr-2 text-gray-400"></i>Thông tin
                    </label>
                    <textarea id="info" 
                              name="info" 
                              class="form-textarea" 
                              rows="8"
                              placeholder="Nhập thông tin...">{{ $settings->info ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="legal_regulations">
                        <i class="fas fa-gavel mr-2 text-gray-400"></i>Quy định pháp lý
                    </label>
                    <textarea id="legal_regulations" 
                              name="legal_regulations" 
                              class="form-textarea" 
                              rows="8"
                              placeholder="Nhập quy định pháp lý...">{{ $settings->legal_regulations ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="order_success">
                        <i class="fas fa-check-circle mr-2 text-gray-400"></i>Thông báo đặt hàng thành công
                    </label>
                    <textarea id="order_success" 
                              name="order_success" 
                              class="form-textarea" 
                              rows="6"
                              placeholder="Nhập thông báo khi đặt hàng thành công...">{{ $settings->order_success ?? '' }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="copyright">
                        <i class="fas fa-copyright mr-2 text-gray-400"></i>Copyright
                    </label>
                    <input type="text" 
                           id="copyright" 
                           name="copyright" 
                           class="form-input" 
                           value="{{ $settings->copyright ?? '' }}"
                           placeholder="© 2024 Tên công ty. All rights reserved.">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="map">
                        <i class="fas fa-map mr-2 text-gray-400"></i>Bản đồ (Embed Code)
                    </label>
                    <textarea id="map" 
                              name="map" 
                              class="form-textarea" 
                              rows="6"
                              placeholder="Nhập mã embed bản đồ Google Maps...">{{ $settings->map ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4 mt-6">
            <button type="submit" id="submitBtn" class="btn-save">
                <i class="fas fa-save"></i>
                <span id="submitText">Lưu cài đặt</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Include CKEditor Component --}}
@include('admin.components.ckeditor', [
    'editorIds' => ['address', 'branch_address', 'work_time', 'zalo', 'facebook', 'youtube', 'google_plus', 'facebook_fb', 'google_analytic', 'about', 'info', 'legal_regulations', 'order_success', 'map']
])

<script>
// Image Upload Functions
function handleImageUpload(event, fieldName) {
    const file = event.target.files[0];
    if (file) {
        const maxSize = fieldName === 'logo' ? 2048 * 1024 : 1024 * 1024; // 2MB for logo, 1MB for icons
        if (file.size > maxSize) {
            alert(`Kích thước ảnh không được vượt quá ${fieldName === 'logo' ? '2MB' : '1MB'}`);
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            showImagePreview(e.target.result, fieldName);
        };
        reader.readAsDataURL(file);
    }
}

function showImagePreview(imageSrc, fieldName) {
    let placeholderId, previewId, previewImageId;
    
    if (fieldName === 'logo') {
        placeholderId = 'logoPlaceholder';
        previewId = 'logoPreview';
        previewImageId = 'logoPreviewImage';
    } else {
        // Map field names to element IDs
        const fieldMap = {
            'icon_fb': { placeholder: 'iconFbPlaceholder', preview: 'iconFbPreview', image: 'iconFbPreviewImage' },
            'icon_youtube': { placeholder: 'iconYoutubePlaceholder', preview: 'iconYoutubePreview', image: 'iconYoutubePreviewImage' },
            'icon_zalo': { placeholder: 'iconZaloPlaceholder', preview: 'iconZaloPreview', image: 'iconZaloPreviewImage' },
            'icon_email': { placeholder: 'iconEmailPlaceholder', preview: 'iconEmailPreview', image: 'iconEmailPreviewImage' },
            'icon_time': { placeholder: 'iconTimePlaceholder', preview: 'iconTimePreview', image: 'iconTimePreviewImage' }
        };
        
        const mapping = fieldMap[fieldName];
        if (mapping) {
            placeholderId = mapping.placeholder;
            previewId = mapping.preview;
            previewImageId = mapping.image;
        } else {
            console.error('Unknown field name:', fieldName);
            return;
        }
    }
    
    const placeholder = document.getElementById(placeholderId);
    const preview = document.getElementById(previewId);
    const previewImage = document.getElementById(previewImageId);
    
    if (placeholder) placeholder.classList.add('hidden');
    if (preview) {
        preview.classList.remove('hidden');
        if (previewImage) previewImage.src = imageSrc;
    }
}

function removeImage(fieldName) {
    let inputId, placeholderId, previewId, previewImageId;
    
    if (fieldName === 'logo') {
        inputId = 'logoInput';
        placeholderId = 'logoPlaceholder';
        previewId = 'logoPreview';
        previewImageId = 'logoPreviewImage';
    } else {
        // Map field names to element IDs
        const fieldMap = {
            'icon_fb': { input: 'iconFbInput', placeholder: 'iconFbPlaceholder', preview: 'iconFbPreview', image: 'iconFbPreviewImage' },
            'icon_youtube': { input: 'iconYoutubeInput', placeholder: 'iconYoutubePlaceholder', preview: 'iconYoutubePreview', image: 'iconYoutubePreviewImage' },
            'icon_zalo': { input: 'iconZaloInput', placeholder: 'iconZaloPlaceholder', preview: 'iconZaloPreview', image: 'iconZaloPreviewImage' },
            'icon_email': { input: 'iconEmailInput', placeholder: 'iconEmailPlaceholder', preview: 'iconEmailPreview', image: 'iconEmailPreviewImage' },
            'icon_time': { input: 'iconTimeInput', placeholder: 'iconTimePlaceholder', preview: 'iconTimePreview', image: 'iconTimePreviewImage' }
        };
        
        const mapping = fieldMap[fieldName];
        if (mapping) {
            inputId = mapping.input;
            placeholderId = mapping.placeholder;
            previewId = mapping.preview;
            previewImageId = mapping.image;
        } else {
            console.error('Unknown field name:', fieldName);
            return;
        }
    }
    
    const input = document.getElementById(inputId);
    const placeholder = document.getElementById(placeholderId);
    const preview = document.getElementById(previewId);
    const previewImage = document.getElementById(previewImageId);
    
    if (input) input.value = '';
    if (placeholder) placeholder.classList.remove('hidden');
    if (preview) preview.classList.add('hidden');
    if (previewImage) previewImage.src = '';
}

// Drag and drop for image uploads
document.addEventListener('DOMContentLoaded', function() {
    const uploadAreas = ['logoUploadArea'];
    
    uploadAreas.forEach(areaId => {
        const uploadArea = document.getElementById(areaId);
        const imageInput = document.getElementById(areaId.replace('UploadArea', 'Input'));
        
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
                    uploadArea.classList.add('dragover');
                }, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.remove('dragover');
                }, false);
            });
            
            uploadArea.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                
                if (files.length > 0) {
                    imageInput.files = files;
                    handleImageUpload({ target: imageInput }, 'logo');
                }
            }, false);
        }
    });
});

// Form Submit
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Sync CKEditor before submit
    if (typeof window.syncCKEditors === 'function') {
        window.syncCKEditors();
    }
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    if (!submitBtn || !submitText) {
        console.error('Submit button elements not found');
        return;
    }
    
    const originalText = submitText.textContent;
    
    // Disable submit button
    submitBtn.disabled = true;
    submitText.innerHTML = '<span class="loading-spinner"></span> Đang lưu...';
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('Lỗi bảo mật: Không tìm thấy CSRF token');
        submitBtn.disabled = false;
        submitText.textContent = originalText;
        return;
    }
    
    formData.append('_token', csrfToken.content);
    formData.append('_method', 'PUT');
    
    fetch('{{ route("admin.settings.update") }}', {
        method: 'POST',
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
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            // Show error
            if (data.errors) {
                let errorMsg = 'Có lỗi xảy ra:\n';
                Object.keys(data.errors).forEach(field => {
                    errorMsg += '- ' + data.errors[field][0] + '\n';
                });
                alert(errorMsg);
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
            
            submitBtn.disabled = false;
            submitText.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi lưu cài đặt: ' + error.message);
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    });
});
</script>
@endpush

