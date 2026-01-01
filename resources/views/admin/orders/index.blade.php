@extends('admin.layouts.master')

@section('title', 'Quản lý đơn hàng')

@php
$breadcrumbs = [
    ['label' => 'Đơn hàng', 'url' => route('admin.orders.index')]
];
@endphp

@push('styles')
<style>
    .order-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e5e7eb;
    }
    
    .order-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: #d1d5db;
    }
    
    .status-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .status-select:hover {
        border-color: #9ca3af;
    }
    
    .status-select:focus {
        outline: none;
        ring: 2px;
        ring-color: #ef4444;
        border-color: #ef4444;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 1px solid #fcd34d;
    }
    
    .status-processing {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border: 1px solid #60a5fa;
    }
    
    .status-completed {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 1px solid #34d399;
    }
    
    .status-cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 1px solid #f87171;
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .stat-card {
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>
@endpush

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 fade-in">
    <div class="stat-card bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Tổng đơn hàng</p>
                <p class="text-4xl font-bold">{{ number_format($totalOrders) }}</p>
                <p class="text-blue-200 text-xs mt-2">
                    <i class="fas fa-chart-line mr-1"></i>Tất cả đơn hàng
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm">
                <i class="fas fa-shopping-cart text-3xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-gradient-to-br from-yellow-500 via-yellow-600 to-orange-500 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium mb-1">Chờ xử lý</p>
                <p class="text-4xl font-bold">{{ number_format($pendingOrders) }}</p>
                <p class="text-yellow-200 text-xs mt-2">
                    <i class="fas fa-clock mr-1"></i>Cần xử lý
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm">
                <i class="fas fa-hourglass-half text-3xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-500 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-indigo-100 text-sm font-medium mb-1">Đang xử lý</p>
                <p class="text-4xl font-bold">{{ number_format($processingOrders) }}</p>
                <p class="text-indigo-200 text-xs mt-2">
                    <i class="fas fa-spinner mr-1"></i>Đang giao hàng
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm">
                <i class="fas fa-truck text-3xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-gradient-to-br from-green-500 via-green-600 to-emerald-500 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Hoàn thành</p>
                <p class="text-4xl font-bold">{{ number_format($completedOrders) }}</p>
                <p class="text-green-200 text-xs mt-2">
                    <i class="fas fa-check-circle mr-1"></i>Đã giao hàng
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm">
                <i class="fas fa-check-double text-3xl"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card bg-gradient-to-br from-red-500 via-red-600 to-rose-500 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium mb-1">Đã hủy</p>
                <p class="text-4xl font-bold">{{ number_format($cancelledOrders) }}</p>
                <p class="text-red-200 text-xs mt-2">
                    <i class="fas fa-times-circle mr-1"></i>Đã hủy
                </p>
            </div>
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-white/20 backdrop-blur-sm">
                <i class="fas fa-ban text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center mb-2">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-blue-600 flex items-center justify-center mr-4 shadow-lg">
                    <i class="fas fa-shopping-bag text-white text-xl"></i>
                </div>
                Quản lý đơn hàng
            </h1>
            <p class="text-gray-500 ml-16">Quản lý và theo dõi tất cả đơn hàng của khách hàng một cách hiệu quả</p>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6 fade-in">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search -->
        <div class="md:col-span-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-search mr-2 text-red-500"></i>Tìm kiếm
            </label>
            <div class="relative">
                <input type="text" 
                       name="search"
                       value="{{ $search }}"
                       placeholder="Tìm theo tên, email, SĐT, mã đơn hàng..." 
                       class="w-full px-4 py-3 pl-11 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        
        <!-- Status Filter -->
        <div class="md:col-span-3">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-filter mr-2 text-red-500"></i>Trạng thái
            </label>
            <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all bg-white">
                <option value="">Tất cả trạng thái</option>
                <option value="0" {{ $status === '0' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="1" {{ $status === '1' ? 'selected' : '' }}>Đang xử lý</option>
                <option value="2" {{ $status === '2' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="3" {{ $status === '3' ? 'selected' : '' }}>Đã hủy</option>
            </select>
        </div>
        
        <!-- Sort -->
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-sort mr-2 text-red-500"></i>Sắp xếp
            </label>
            <select name="sort" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all bg-white">
                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                <option value="total_asc" {{ $sort == 'total_asc' ? 'selected' : '' }}>Tổng tiền ↑</option>
                <option value="total_desc" {{ $sort == 'total_desc' ? 'selected' : '' }}>Tổng tiền ↓</option>
            </select>
        </div>
        
        <!-- Actions -->
        <div class="md:col-span-2 flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-blue-600 hover:from-red-700 hover:to-blue-700 text-white rounded-xl transition-all duration-200 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-search mr-2"></i>Tìm
            </button>
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-3 border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-all">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Orders Cards Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    @forelse($orders as $order)
        @php
            $statusConfig = [
                0 => ['class' => 'status-pending', 'text' => 'Chờ xử lý', 'icon' => 'fa-clock'],
                1 => ['class' => 'status-processing', 'text' => 'Đang xử lý', 'icon' => 'fa-spinner'],
                2 => ['class' => 'status-completed', 'text' => 'Hoàn thành', 'icon' => 'fa-check-circle'],
                3 => ['class' => 'status-cancelled', 'text' => 'Đã hủy', 'icon' => 'fa-times-circle'],
            ];
            $currentStatus = $statusConfig[$order->status] ?? $statusConfig[0];
        @endphp
        <div class="order-card bg-white rounded-2xl shadow-lg border border-gray-200 p-6 fade-in" data-order-id="{{ $order->id }}">
            <!-- Header -->
            <div class="flex items-start justify-between mb-4 pb-4 border-b border-gray-200">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-blue-600 flex items-center justify-center shadow-md">
                            <i class="fas fa-shopping-bag text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">
                                #{{ $order->code_bill ?? $order->id }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                @if(isset($order->created_at) && $order->created_at)
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-gray-900 mb-1">
                        {{ number_format($order->total ?? 0) }} đ
                    </div>
                    <p class="text-xs text-gray-500">Tổng tiền</p>
                </div>
            </div>
            
            <!-- Customer Info -->
            <div class="mb-4 space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-user text-gray-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->name }}</p>
                        @if($order->sex)
                            <p class="text-xs text-gray-500">
                                <i class="fas fa-{{ $order->sex == 'male' ? 'mars' : 'venus' }} mr-1"></i>
                                {{ $order->sex == 'male' ? 'Nam' : 'Nữ' }}
                            </p>
                        @endif
                    </div>
                </div>
                
                @if($order->phone)
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-phone w-4"></i>
                    <span>{{ $order->phone }}</span>
                </div>
                @endif
                
                @if($order->email)
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-envelope w-4"></i>
                    <span class="truncate">{{ $order->email }}</span>
                </div>
                @endif
                
                @if($order->address)
                <div class="flex items-start gap-2 text-sm text-gray-600">
                    <i class="fas fa-map-marker-alt w-4 mt-0.5"></i>
                    <div>
                        <p>{{ $order->address }}</p>
                        @if($order->district || $order->city)
                            <p class="text-xs text-gray-500">
                                {{ $order->district ?? '' }}{{ $order->district && $order->city ? ', ' : '' }}{{ $order->city ?? '' }}
                            </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Status Update -->
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 mb-2">
                    <i class="fas fa-edit mr-1 text-red-500"></i>Cập nhật trạng thái
                </label>
                <select 
                    class="status-select w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white font-semibold {{ $currentStatus['class'] }} transition-all"
                    data-order-id="{{ $order->id }}"
                    onchange="updateOrderStatus({{ $order->id }}, this.value)"
                    id="status-select-{{ $order->id }}">
                    <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.orders.show', $order->id) }}" 
                   class="flex-1 px-4 py-2.5 text-center text-sm font-semibold text-blue-600 hover:bg-blue-50 rounded-xl transition-all border-2 border-blue-200 hover:border-blue-300">
                    <i class="fas fa-eye mr-2"></i>Chi tiết
                </a>
                <button onclick="confirmDelete({{ $order->id }}, 'Đơn hàng #{{ $order->code_bill ?? $order->id }}')" 
                        class="px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition-all border-2 border-red-200 hover:border-red-300">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
                <div class="text-gray-400">
                    <i class="fas fa-shopping-cart text-6xl mb-4"></i>
                    <p class="text-xl font-semibold mb-2">Không có đơn hàng nào</p>
                    <p class="text-sm">Chưa có đơn hàng nào trong hệ thống</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($orders->hasPages())
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4">
        {{ $orders->links('admin.helpers.pagination') }}
    </div>
@endif

<!-- Delete Confirmation Modal -->
@include('admin.partials.confirm-modal')

<!-- Hidden Delete Form -->
<form id="deleteOrderForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
// Update order status via AJAX
async function updateOrderStatus(orderId, newStatus) {
    const select = document.getElementById(`status-select-${orderId}`);
    const originalValue = select.getAttribute('data-original-value') || select.value;
    const card = document.querySelector(`[data-order-id="${orderId}"]`);
    
    // Disable select during update
    select.disabled = true;
    const originalHTML = select.innerHTML;
    select.innerHTML = '<option>Đang cập nhật...</option>';
    
    // Add loading state
    if (card) {
        card.style.opacity = '0.7';
        card.style.pointerEvents = 'none';
    }
    
    try {
        // Use route helper to generate URL - ensure /status is included
        const baseUrl = `{{ url('admin/orders') }}`;
        const url = `${baseUrl}/${orderId}/status`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        const response = await fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: parseInt(newStatus) })
        });
        
        // Check if response is ok
        if (!response.ok) {
            // Try to parse error response
            let errorMessage = `HTTP ${response.status}: ${response.statusText}`;
            try {
                const errorData = await response.json();
                errorMessage = errorData.message || errorData.error || errorMessage;
            } catch (e) {
                // If can't parse JSON, use status text
            }
            throw new Error(errorMessage);
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Update select with new status
            const statusConfig = {
                0: { text: 'Chờ xử lý', class: 'status-pending' },
                1: { text: 'Đang xử lý', class: 'status-processing' },
                2: { text: 'Hoàn thành', class: 'status-completed' },
                3: { text: 'Đã hủy', class: 'status-cancelled' }
            };
            
            const config = statusConfig[newStatus];
            select.innerHTML = `
                <option value="0" ${newStatus == 0 ? 'selected' : ''}>Chờ xử lý</option>
                <option value="1" ${newStatus == 1 ? 'selected' : ''}>Đang xử lý</option>
                <option value="2" ${newStatus == 2 ? 'selected' : ''}>Hoàn thành</option>
                <option value="3" ${newStatus == 3 ? 'selected' : ''}>Đã hủy</option>
            `;
            select.className = `status-select w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl bg-white font-semibold ${config.class} transition-all`;
            select.setAttribute('data-original-value', newStatus);
            
            // Show success notification
            if (typeof showSuccess === 'function') {
                showSuccess(data.message || 'Trạng thái đơn hàng đã được cập nhật thành công!');
            } else {
                alert(data.message || 'Trạng thái đơn hàng đã được cập nhật thành công!');
            }
            
            // Reload page after 1.5 seconds to update stats
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra khi cập nhật trạng thái');
        }
    } catch (error) {
        console.error('Error updating order status:', error);
        // Revert select
        select.innerHTML = originalHTML;
        select.value = originalValue;
        
        // Show error notification
        if (typeof showError === 'function') {
            showError(error.message || 'Không thể cập nhật trạng thái đơn hàng. Vui lòng thử lại!');
        } else {
            alert(error.message || 'Không thể cập nhật trạng thái đơn hàng. Vui lòng thử lại!');
        }
    } finally {
        select.disabled = false;
        if (card) {
            card.style.opacity = '1';
            card.style.pointerEvents = 'auto';
        }
    }
}

// Store original values on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-select').forEach(select => {
        select.setAttribute('data-original-value', select.value);
    });
});

// Delete confirmation using the modal helper
function confirmDelete(id, name) {
    showConfirmModal({
        title: 'Xác nhận xóa đơn hàng',
        message: `Bạn có chắc chắn muốn xóa đơn hàng ${name}? Hành động này không thể hoàn tác.`,
        type: 'danger',
        confirmText: 'Xóa đơn hàng',
        cancelText: 'Hủy',
        onConfirm: function() {
            const form = document.getElementById('deleteOrderForm');
            form.action = `/admin/orders/${id}`;
            form.submit();
        }
    });
}
</script>
@endsection
