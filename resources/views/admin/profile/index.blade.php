@extends('admin.layouts.master')

@section('title', 'Hồ sơ cá nhân')

@php
$breadcrumbs = [
    ['label' => 'Hồ sơ', 'url' => route('admin.profile')]
];
@endphp

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);
        border-radius: 16px;
        padding: 32px;
        color: white;
        margin-bottom: 24px;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: white;
    }
    
    .profile-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #e5e7eb;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }
    
    .profile-section:hover {
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
    
    .form-input:disabled {
        background: #f9fafb;
        cursor: not-allowed;
    }
    
    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }
    
    .avatar-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        background: #f9fafb;
        cursor: pointer;
    }
    
    .avatar-upload-area:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }
    
    .avatar-upload-area.dragover {
        border-color: #2563eb;
        background: #dbeafe;
    }
    
    .preview-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e5e7eb;
        margin: 0 auto;
        display: block;
    }
    
    .avatar-preview-container {
        position: relative;
        display: inline-block;
        margin: 0 auto;
    }
    
    .remove-avatar-btn {
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
    
    .remove-avatar-btn:hover {
        background: rgba(220, 38, 38, 1);
        transform: scale(1.1);
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
    
    .info-item {
        display: flex;
        align-items: center;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 12px;
    }
    
    .info-item-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 12px;
        flex-shrink: 0;
    }
    
    .info-item-content {
        flex: 1;
    }
    
    .info-item-label {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
    }
    
    .info-item-value {
        font-size: 14px;
        font-weight: 500;
        color: #111827;
    }
</style>
@endpush

@section('content')
<!-- Profile Header -->
<div class="profile-header fade-in">
    <div class="flex flex-col md:flex-row items-center gap-6">
        <div class="relative">
            @if($user->avatar ?? false)
                <img src="{{ asset('img/avatars/' . $user->avatar) }}" 
                     alt="{{ $user->username ?? $user->name ?? 'User' }}" 
                     class="profile-avatar"
                     id="currentAvatar"
                     onerror="this.style.display='none'; document.getElementById('avatarPlaceholder').style.display='flex';">
            @endif
            <div class="profile-avatar-placeholder" id="avatarPlaceholder" style="{{ ($user->avatar ?? false) ? 'display: none;' : 'display: flex;' }}">
                <i class="fas fa-user"></i>
            </div>
        </div>
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-3xl font-bold mb-2">{{ $user->username ?? $user->name ?? 'Người dùng' }}</h1>
            <p class="text-white/90 text-lg mb-1">{{ $user->email ?? '' }}</p>
            <div class="flex items-center justify-center md:justify-start gap-2 mt-3">
                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white/20 backdrop-blur-sm">
                    <i class="fas fa-shield-alt mr-1"></i>Quản trị viên
                </span>
                @if($user->status ?? false)
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-500/30 backdrop-blur-sm">
                        <i class="fas fa-check-circle mr-1"></i>Hoạt động
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Profile Form -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <form id="profileForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Thông tin cơ bản -->
        <div class="profile-section">
            <div class="section-header">
                <h3>
                    <i class="fas fa-user" style="color: #2563eb;"></i>
                    Thông tin cơ bản
                </h3>
            </div>
            
            <div class="space-y-4">
                <div class="form-group">
                    <label class="form-label" for="username">
                        <i class="fas fa-user-circle mr-2 text-gray-400"></i>Tên đăng nhập <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-input" 
                           value="{{ $user->username ?? $user->name ?? '' }}"
                           placeholder="Nhập tên đăng nhập"
                           required>
                    <div id="usernameError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-input" 
                           value="{{ $user->email ?? '' }}"
                           placeholder="Nhập địa chỉ email"
                           required>
                    <div id="emailError" class="error-message hidden"></div>
                </div>
            </div>
        </div>

        <!-- Avatar -->
        <div class="profile-section">
            <div class="section-header">
                <h3>
                    <i class="fas fa-image" style="color: #2563eb;"></i>
                    Ảnh đại diện
                </h3>
            </div>
            
            <div class="form-group">
                <div class="avatar-upload-area" id="avatarUploadArea" onclick="document.getElementById('avatarInput').click()">
                    <input type="file" 
                           id="avatarInput" 
                           name="avatar" 
                           accept="image/*" 
                           class="hidden"
                           onchange="handleAvatarUpload(event)">
                    <div id="avatarPlaceholderUpload">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-600 font-medium">Nhấp để tải ảnh đại diện lên</p>
                        <p class="text-xs text-gray-500 mt-1">hoặc kéo thả ảnh vào đây</p>
                        <p class="text-xs text-gray-400 mt-2">Kích thước tối đa: 2MB</p>
                    </div>
                    <div id="avatarPreview" class="hidden">
                        <div class="avatar-preview-container">
                            <img id="avatarPreviewImage" src="" alt="Avatar Preview" class="preview-avatar">
                            <button type="button" class="remove-avatar-btn" onclick="removeAvatar()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="avatarError" class="error-message hidden"></div>
            </div>
        </div>

        <!-- Đổi mật khẩu -->
        <div class="profile-section">
            <div class="section-header">
                <h3>
                    <i class="fas fa-lock" style="color: #2563eb;"></i>
                    Đổi mật khẩu
                </h3>
            </div>
            
            <div class="space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-blue-800 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        Để trống nếu không muốn thay đổi mật khẩu
                    </p>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="fas fa-key mr-2 text-gray-400"></i>Mật khẩu mới
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input" 
                           placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)"
                           minlength="6">
                    <div id="passwordError" class="error-message hidden"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">
                        <i class="fas fa-key mr-2 text-gray-400"></i>Xác nhận mật khẩu
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-input" 
                           placeholder="Nhập lại mật khẩu mới"
                           minlength="6">
                    <div id="password_confirmationError" class="error-message hidden"></div>
                </div>
            </div>
        </div>

        <!-- Thông tin tài khoản -->
        <div class="profile-section">
            <div class="section-header">
                <h3>
                    <i class="fas fa-info-circle" style="color: #2563eb;"></i>
                    Thông tin tài khoản
                </h3>
            </div>
            
            <div class="space-y-3">
                <div class="info-item">
                    <div class="info-item-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="info-item-content">
                        <div class="info-item-label">ID Tài khoản</div>
                        <div class="info-item-value">#{{ $user->id ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-item-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="info-item-content">
                        <div class="info-item-label">Ngày tạo</div>
                        <div class="info-item-value">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="info-item">
                    <div class="info-item-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="info-item-content">
                        <div class="info-item-label">Cập nhật lần cuối</div>
                        <div class="info-item-value">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-4 mt-6">
            <button type="submit" id="submitBtn" class="btn-save">
                <i class="fas fa-save"></i>
                <span id="submitText">Cập nhật hồ sơ</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
// Avatar Upload Functions
function handleAvatarUpload(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2048 * 1024) {
            showError('avatar', 'Kích thước ảnh không được vượt quá 2MB');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            showAvatarPreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function showAvatarPreview(imageSrc) {
    const placeholder = document.getElementById('avatarPlaceholderUpload');
    const preview = document.getElementById('avatarPreview');
    const previewImage = document.getElementById('avatarPreviewImage');
    const currentAvatar = document.getElementById('currentAvatar');
    
    if (placeholder) placeholder.classList.add('hidden');
    if (preview) {
        preview.classList.remove('hidden');
        if (previewImage) previewImage.src = imageSrc;
    }
    if (currentAvatar) currentAvatar.style.display = 'none';
    if (document.getElementById('avatarPlaceholder')) {
        document.getElementById('avatarPlaceholder').style.display = 'none';
    }
}

function removeAvatar() {
    const input = document.getElementById('avatarInput');
    const placeholder = document.getElementById('avatarPlaceholderUpload');
    const preview = document.getElementById('avatarPreview');
    const previewImage = document.getElementById('avatarPreviewImage');
    
    if (input) input.value = '';
    if (placeholder) placeholder.classList.remove('hidden');
    if (preview) preview.classList.add('hidden');
    if (previewImage) previewImage.src = '';
}

function clearErrors() {
    document.querySelectorAll('.error-message').forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });
    document.querySelectorAll('.form-input').forEach(el => {
        el.classList.remove('error');
    });
}

function showError(field, message) {
    const input = document.getElementById(field);
    const errorDiv = document.getElementById(field + 'Error');
    
    if (input) {
        input.classList.add('error');
    }
    
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
}

// Drag and drop for avatar upload
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('avatarUploadArea');
    const avatarInput = document.getElementById('avatarInput');
    
    if (uploadArea && avatarInput) {
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
                avatarInput.files = files;
                handleAvatarUpload({ target: avatarInput });
            }
        }, false);
    }
});

// Form Submit
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    if (!submitBtn || !submitText) {
        console.error('Submit button elements not found');
        return;
    }
    
    const originalText = submitText.textContent;
    
    // Clear previous errors
    clearErrors();
    
    // Validate password confirmation if password is provided
    const password = formData.get('password');
    const passwordConfirmation = formData.get('password_confirmation');
    
    if (password && password !== passwordConfirmation) {
        showError('password_confirmation', 'Mật khẩu xác nhận không khớp');
        return;
    }
    
    // Disable submit button
    submitBtn.disabled = true;
    submitText.innerHTML = '<span class="loading-spinner"></span> Đang cập nhật...';
    
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
    
    fetch('{{ route("admin.profile.update") }}', {
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
                Object.keys(data.errors).forEach(field => {
                    showError(field, data.errors[field][0]);
                });
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
            
            submitBtn.disabled = false;
            submitText.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật hồ sơ: ' + error.message);
        submitBtn.disabled = false;
        submitText.textContent = originalText;
    });
});
</script>
@endpush

