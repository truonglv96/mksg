@extends('admin.layouts.master')

@section('title', 'Thêm chính sách')

@php
$breadcrumbs = [
    ['label' => 'Chính sách', 'url' => route('admin.policies.index')],
    ['label' => 'Thêm mới', 'url' => route('admin.policies.create')]
];
@endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle mr-3" style="color: #2563eb;"></i>
                Thêm chính sách mới
            </h1>
            <p class="mt-1 text-sm text-gray-500">Tạo trang chính sách để hiển thị ở footer</p>
        </div>
        <a href="{{ route('admin.policies.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
            <i class="fas fa-arrow-left mr-2"></i>Quay lại
        </a>
    </div>

    <form action="{{ route('admin.policies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2" style="color: #2563eb;"></i>
                        Thông tin cơ bản
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tên chính sách <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="policy-name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('name') border-red-500 @enderror"
                                   placeholder="Nhập tên chính sách"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Link (URL)
                            </label>
                            <input type="text"
                                   id="policy-link"
                                   name="link"
                                   value="{{ old('link') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('link') border-red-500 @enderror"
                                   placeholder="chinh-sach-doi-tra (tự động nếu để trống)">
                            <p class="mt-1 text-xs text-gray-500">Trang sẽ hiển thị tại: /trang/{link}</p>
                            @error('link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nội dung
                            </label>
                            <textarea name="content"
                                      id="content"
                                      rows="12"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('content') border-red-500 @enderror"
                                      placeholder="Nhập nội dung chính sách...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-cog mr-2" style="color: #2563eb;"></i>
                        Cấu hình hiển thị
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Thứ tự
                            </label>
                            <input type="number"
                                   name="weight"
                                   value="{{ old('weight', 0) }}"
                                   min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-smooth @error('weight') border-red-500 @enderror">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox"
                                   id="status"
                                   name="status"
                                   class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                   {{ old('status', '1') ? 'checked' : '' }}>
                            <label for="status" class="text-sm text-gray-700">Hiển thị</label>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-image mr-2" style="color: #2563eb;"></i>
                        Ảnh đại diện
                    </h2>
                    <input type="file"
                           name="image"
                           accept="image/*"
                           class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.policies.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition-smooth">
                <i class="fas fa-times mr-2"></i>Hủy
            </a>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-blue-600 text-white rounded-lg hover:from-red-700 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-save mr-2"></i>Lưu chính sách
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const nameInput = document.getElementById('policy-name');
        const linkInput = document.getElementById('policy-link');
        if (!nameInput || !linkInput) return;

        let linkTouched = false;
        linkInput.addEventListener('input', function() {
            linkTouched = linkInput.value.trim().length > 0;
        });

        function slugify(text) {
            return text
                .toString()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        nameInput.addEventListener('input', function() {
            if (linkTouched) return;
            const slug = slugify(nameInput.value);
            linkInput.value = slug;
        });
    })();
</script>
@include('admin.components.ckeditor', [
    'editorIds' => ['content']
])
@endpush
