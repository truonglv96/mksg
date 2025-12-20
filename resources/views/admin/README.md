# Admin Template Documentation

Template admin hiện đại và thân thiện với người dùng cho Laravel application.

## Cấu trúc thư mục

```
admin/
├── layouts/
│   └── master.blade.php          # Layout chính
├── partials/
│   ├── sidebar.blade.php         # Sidebar navigation
│   ├── header.blade.php          # Header với search và user menu
│   ├── footer.blade.php          # Footer
│   └── breadcrumb.blade.php      # Breadcrumb navigation
├── components/
│   ├── card.blade.php            # Component card
│   ├── button.blade.php          # Component button
│   ├── table.blade.php           # Component table
│   ├── badge.blade.php           # Component badge
│   └── modal.blade.php           # Component modal
├── dashboard/
│   └── index.blade.php           # Trang dashboard mẫu
└── products/
    └── index.blade.php           # Trang quản lý sản phẩm mẫu
```

## Sử dụng

### 1. Tạo một trang mới

```blade
@extends('admin.layouts.master')

@section('title', 'Tiêu đề trang')

@php
$breadcrumbs = [
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Trang hiện tại']
];
@endphp

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <!-- Nội dung của bạn -->
</div>
@endsection
```

### 2. Sử dụng Components

#### Card Component

```blade
<x-admin.card title="Tiêu đề card" action-route="{{ route('admin.example') }}" action-label="Xem thêm">
    <!-- Nội dung card -->
</x-admin.card>
```

#### Button Component

```blade
<x-admin.button variant="primary" size="md" icon="fas fa-save">
    Lưu
</x-admin.button>

<!-- Các variant: primary, secondary, success, danger, warning, info -->
<!-- Các size: sm, md, lg -->
```

#### Badge Component

```blade
<x-admin.badge variant="success">Đang bán</x-admin.badge>

<!-- Các variant: default, success, warning, danger, info -->
```

#### Table Component

```blade
<x-admin.table :headers="['Cột 1', 'Cột 2', 'Cột 3']">
    <tr class="hover:bg-gray-50">
        <td class="px-6 py-4 whitespace-nowrap">Dữ liệu 1</td>
        <td class="px-6 py-4 whitespace-nowrap">Dữ liệu 2</td>
        <td class="px-6 py-4 whitespace-nowrap">Dữ liệu 3</td>
        <td class="px-6 py-4 whitespace-nowrap text-right">
            <a href="#" class="text-primary-600 hover:text-primary-900">Xem</a>
        </td>
    </tr>
</x-admin.table>
```

#### Modal Component

```blade
<x-admin.modal id="example-modal" title="Tiêu đề modal" size="md">
    <!-- Nội dung modal -->
    
    <x-slot:footer>
        <button onclick="closeModal('example-modal')" class="px-4 py-2 bg-gray-200 rounded-lg">
            Hủy
        </button>
        <button class="px-4 py-2 bg-primary-600 text-white rounded-lg">
            Xác nhận
        </button>
    </x-slot:footer>
</x-admin.modal>

<!-- Mở modal: openModal('example-modal') -->
<!-- Đóng modal: closeModal('example-modal') -->
```

### 3. Flash Messages

Flash messages sẽ tự động hiển thị trong layout master:

```php
// Trong controller
return redirect()->route('admin.example')->with('success', 'Thành công!');
return redirect()->route('admin.example')->with('error', 'Có lỗi xảy ra!');
```

### 4. Sidebar Navigation

Cập nhật menu trong `partials/sidebar.blade.php`:

```blade
<li>
    <a href="{{ route('admin.example') }}" 
       class="flex items-center px-4 py-3 text-gray-300 rounded-lg hover:bg-sidebar-hover hover:text-white transition-colors {{ request()->routeIs('admin.example.*') ? 'menu-item-active' : '' }}">
        <i class="fas fa-icon w-5"></i>
        <span class="ml-3">Tên menu</span>
    </a>
</li>
```

## Tính năng

- ✅ Responsive design (Mobile, Tablet, Desktop)
- ✅ Dark sidebar với navigation menu
- ✅ Header với search và user dropdown
- ✅ Breadcrumb navigation
- ✅ Flash messages (success/error)
- ✅ Reusable components (Card, Button, Table, Badge, Modal)
- ✅ Modern UI với Tailwind CSS
- ✅ Font Awesome icons
- ✅ Custom scrollbar
- ✅ Smooth transitions và animations

## Tùy chỉnh

### Màu sắc

Màu sắc có thể được tùy chỉnh trong `layouts/master.blade.php` trong phần `tailwind.config`:

```javascript
colors: {
    primary: {
        // Tùy chỉnh màu primary
    },
    sidebar: {
        bg: '#1e293b',
        hover: '#334155',
        active: '#0ea5e9',
    }
}
```

### Fonts

Font mặc định là Inter. Có thể thay đổi trong `layouts/master.blade.php`.

## Routes cần thiết

Đảm bảo bạn có các routes sau trong `routes/web.php`:

```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    // ... các routes khác
});
```

## Ghi chú

- Tất cả các routes trong sidebar cần được định nghĩa trong routes file
- Có thể thay đổi user information trong sidebar và header
- Components có thể được mở rộng và tùy chỉnh theo nhu cầu

