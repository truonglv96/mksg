@extends('admin.layouts.master')

@section('title', 'Chi tiết đơn hàng')

@php
$breadcrumbs = [
    ['label' => 'Đơn hàng', 'url' => route('admin.orders.index')],
    ['label' => 'Chi tiết #' . ($order->code_bill ?? $order->id), 'url' => route('admin.orders.show', $order->id)]
];
@endphp

@push('styles')
<style>
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 2px solid #fcd34d;
    }
    
    .status-processing {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
        border: 2px solid #60a5fa;
    }
    
    .status-completed {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
        border: 2px solid #34d399;
    }
    
    .status-cancelled {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #991b1b;
        border: 2px solid #f87171;
    }
    
    .info-card {
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .product-item {
        transition: all 0.2s ease;
    }
    
    .product-item:hover {
        background-color: #f9fafb;
    }
</style>
@endpush

@section('content')
<!-- Header -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-4 mb-3">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-blue-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-shopping-bag text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Đơn hàng #{{ $order->code_bill ?? $order->id }}
                    </h1>
                    <p class="text-gray-500 mt-1">
                        @if(isset($order->created_at) && $order->created_at)
                            Đặt hàng lúc {{ $order->created_at->format('d/m/Y H:i') }}
                        @else
                            Thông tin đơn hàng
                        @endif
                    </p>
                </div>
            </div>
            <div class="ml-20">
                <span class="status-badge {{ $order->statusConfig['class'] }}">
                    <i class="fas {{ $order->statusConfig['icon'] }}"></i>
                    {{ $order->statusConfig['text'] }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" 
               class="px-5 py-2.5 border-2 border-gray-300 rounded-xl hover:bg-gray-50 transition-all font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Order Items -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Items -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 fade-in">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-box text-red-600 mr-3"></i>
                Sản phẩm trong đơn hàng
            </h2>
            
            @if($order->billItems && $order->billItems->count() > 0)
                <div class="space-y-4">
                    @foreach($order->billItems as $item)
                        <div class="product-item border-2 border-gray-200 rounded-xl p-4">
                            <div class="flex items-start gap-4">
                                @if($item->product && $item->product->url_img)
                                    <div class="w-24 h-24 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-200">
                                        <img src="{{ asset('img/product/' . $item->product->url_img) }}" 
                                             alt="{{ $item->product->name ?? 'Sản phẩm' }}"
                                             class="w-full h-full object-cover"
                                             onerror="this.src='{{ asset('img/placeholder.png') }}'">
                                    </div>
                                @else
                                    <div class="w-24 h-24 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 border border-gray-200">
                                        <i class="fas fa-image text-gray-400 text-2xl"></i>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                        {{ $item->product->name ?? 'Sản phẩm #' . $item->product_id }}
                                    </h3>
                                    
                                    @if($item->category_name)
                                        <p class="text-sm text-gray-500 mb-2">
                                            <i class="fas fa-tag mr-1"></i>{{ $item->category_name }}
                                        </p>
                                    @endif
                                    
                                    <div class="flex items-center gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-500">Số lượng:</span>
                                            <span class="font-semibold text-gray-900 ml-1">{{ $item->qty ?? 1 }}</span>
                                        </div>
                                        
                                        @if($item->color_id)
                                            <div>
                                                <span class="text-gray-500">Màu sắc:</span>
                                                <span class="font-semibold text-gray-900 ml-1">ID: {{ $item->color_id }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    @php
                                        $itemPrice = $item->price ?? 0;
                                        $itemSaleOff = $item->sale_off ?? 0;
                                        $finalPrice = $itemSaleOff > 0 ? $itemSaleOff : $itemPrice;
                                        $totalItemPrice = $finalPrice * ($item->qty ?? 1);
                                    @endphp
                                    
                                    @if($itemSaleOff > 0 && $itemSaleOff != $itemPrice)
                                        <div class="text-sm text-gray-400 line-through mb-1">
                                            {{ number_format($itemPrice) }} đ
                                        </div>
                                    @endif
                                    
                                    <div class="text-lg font-bold text-gray-900">
                                        {{ number_format($finalPrice) }} đ
                                    </div>
                                    
                                    <div class="text-sm text-gray-500 mt-1">
                                        Tổng: <span class="font-semibold text-red-600">{{ number_format($totalItemPrice) }} đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Không có sản phẩm nào trong đơn hàng</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Right Column - Order Information -->
    <div class="space-y-6">
        <!-- Customer Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 fade-in info-card">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-user text-red-600 mr-3"></i>
                Thông tin khách hàng
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Họ và tên</label>
                    <p class="text-base font-semibold text-gray-900">{{ $order->name ?? '—' }}</p>
                </div>
                
                @if($order->sex)
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Giới tính</label>
                    <p class="text-base text-gray-900">
                        <i class="fas fa-{{ $order->sex == 'male' ? 'mars' : 'venus' }} mr-2"></i>
                        {{ $order->sex == 'male' ? 'Nam' : 'Nữ' }}
                    </p>
                </div>
                @endif
                
                @if($order->phone)
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Số điện thoại</label>
                    <p class="text-base text-gray-900">
                        <i class="fas fa-phone mr-2 text-gray-400"></i>
                        <a href="tel:{{ $order->phone }}" class="hover:text-red-600 transition-colors">{{ $order->phone }}</a>
                    </p>
                </div>
                @endif
                
                @if($order->email)
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                    <p class="text-base text-gray-900">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>
                        <a href="mailto:{{ $order->email }}" class="hover:text-red-600 transition-colors break-all">{{ $order->email }}</a>
                    </p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Delivery Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 fade-in info-card">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-truck text-red-600 mr-3"></i>
                Thông tin giao hàng
            </h2>
            
            <div class="space-y-4">
                @if($order->address)
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Địa chỉ</label>
                    <p class="text-base text-gray-900">
                        <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                        {{ $order->address }}
                    </p>
                </div>
                @endif
                
                @if($order->district || $order->city)
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Quận/Huyện - Tỉnh/Thành phố</label>
                    <p class="text-base text-gray-900">
                        {{ $order->district ?? '' }}{{ $order->district && $order->city ? ', ' : '' }}{{ $order->city ?? '' }}
                    </p>
                </div>
                @endif
                
                @if($order->ship)
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Phương thức vận chuyển</label>
                    <p class="text-base text-gray-900">{{ $order->ship }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Payment & Summary -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 fade-in info-card">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-money-bill-wave text-red-600 mr-3"></i>
                Thanh toán
            </h2>
            
            <div class="space-y-4">
                @if($order->payment)
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Phương thức thanh toán</label>
                    <p class="text-base text-gray-900">{{ $order->payment }}</p>
                </div>
                @endif
                
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-500">Tổng tiền:</span>
                        <span class="text-2xl font-bold text-red-600">{{ number_format($order->total ?? 0) }} đ</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        @if($order->note)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 fade-in info-card">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-sticky-note text-red-600 mr-3"></i>
                Ghi chú
            </h2>
            <p class="text-base text-gray-700 whitespace-pre-wrap">{{ $order->note }}</p>
        </div>
        @endif
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 fade-in">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-cog text-red-600 mr-3"></i>
                Thao tác nhanh
            </h2>
            
            <div class="space-y-3">
                <label class="block text-xs font-semibold text-gray-700 mb-2">Cập nhật trạng thái</label>
                <select 
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl bg-white font-semibold {{ $order->statusConfig['class'] }} transition-all"
                    onchange="updateOrderStatus({{ $order->id }}, this.value)"
                    id="status-select-{{ $order->id }}">
                    <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="3" {{ $order->status == 3 ? 'selected' : '' }}>Đã hủy</option>
                </select>
                
                <button onclick="confirmDelete({{ $order->id }}, 'Đơn hàng #{{ $order->code_bill ?? $order->id }}')" 
                        class="w-full px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 rounded-xl transition-all border-2 border-red-200 hover:border-red-300">
                    <i class="fas fa-trash mr-2"></i>Xóa đơn hàng
                </button>
            </div>
        </div>
    </div>
</div>

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
    const originalValue = select.value;
    
    // Disable select during update
    select.disabled = true;
    const originalHTML = select.innerHTML;
    select.innerHTML = '<option>Đang cập nhật...</option>';
    
    try {
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
        
        if (!response.ok) {
            let errorMessage = `HTTP ${response.status}: ${response.statusText}`;
            try {
                const errorData = await response.json();
                errorMessage = errorData.message || errorData.error || errorMessage;
            } catch (e) {}
            throw new Error(errorMessage);
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Show success notification
            if (typeof showSuccess === 'function') {
                showSuccess(data.message || 'Trạng thái đơn hàng đã được cập nhật thành công!');
            } else {
                alert(data.message || 'Trạng thái đơn hàng đã được cập nhật thành công!');
            }
            
            // Reload page after 1.5 seconds to update status badge
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
    }
}

// Delete confirmation
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

