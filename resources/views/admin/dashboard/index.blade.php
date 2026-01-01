@extends('admin.layouts.master')

@section('title', 'Dashboard')

@php
$breadcrumbs = [
    ['label' => 'Dashboard']
];
@endphp

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Tổng đơn hàng</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                <p class="text-xs {{ $orderGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                    @if($orderGrowth > 0)
                        <i class="fas fa-arrow-up"></i> {{ $orderGrowth }}% so với tháng trước
                    @elseif($orderGrowth < 0)
                        <i class="fas fa-arrow-down"></i> {{ abs($orderGrowth) }}% so với tháng trước
                    @else
                        <i class="fas fa-minus"></i> Không đổi
                    @endif
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Doanh thu</p>
                <p class="text-3xl font-bold text-gray-900">
                    @if($totalRevenue >= 1000000000)
                        ₫{{ number_format($totalRevenue / 1000000000, 1) }}B
                    @elseif($totalRevenue >= 1000000)
                        ₫{{ number_format($totalRevenue / 1000000, 1) }}M
                    @elseif($totalRevenue >= 1000)
                        ₫{{ number_format($totalRevenue / 1000, 1) }}K
                    @else
                        ₫{{ number_format($totalRevenue) }}
                    @endif
                </p>
                <p class="text-xs {{ $revenueGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                    @if($revenueGrowth > 0)
                        <i class="fas fa-arrow-up"></i> {{ $revenueGrowth }}% so với tháng trước
                    @elseif($revenueGrowth < 0)
                        <i class="fas fa-arrow-down"></i> {{ abs($revenueGrowth) }}% so với tháng trước
                    @else
                        <i class="fas fa-minus"></i> Không đổi
                    @endif
                </p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Products -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Sản phẩm</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalProducts) }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-minus"></i> Tổng số sản phẩm
                </p>
            </div>
            <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-box text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Total Customers -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Khách hàng</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</p>
                <p class="text-xs {{ $customerGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                    @if($customerGrowth > 0)
                        <i class="fas fa-arrow-up"></i> {{ $customerGrowth }}% so với tháng trước
                    @elseif($customerGrowth < 0)
                        <i class="fas fa-arrow-down"></i> {{ abs($customerGrowth) }}% so với tháng trước
                    @else
                        <i class="fas fa-minus"></i> Không đổi
                    @endif
                </p>
            </div>
            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-orange-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Revenue Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900">Doanh thu theo tháng</h2>
            <select id="revenuePeriod" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none">
                <option value="12">12 tháng</option>
                <option value="6">6 tháng</option>
                <option value="3">3 tháng</option>
            </select>
        </div>
        <div class="h-64 bg-gray-50 rounded-lg p-4">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Hoạt động gần đây</h2>
        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                @php
                    $bgColorClass = match($activity['color']) {
                        'blue' => 'bg-blue-100',
                        'green' => 'bg-green-100',
                        'purple' => 'bg-purple-100',
                        'orange' => 'bg-orange-100',
                        'red' => 'bg-red-100',
                        default => 'bg-gray-100'
                    };
                    $textColorClass = match($activity['color']) {
                        'blue' => 'text-blue-600',
                        'green' => 'text-green-600',
                        'purple' => 'text-purple-600',
                        'orange' => 'text-orange-600',
                        'red' => 'text-red-600',
                        default => 'text-gray-600'
                    };
                @endphp
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 {{ $bgColorClass }} rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-{{ $activity['icon'] }} {{ $textColorClass }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-500">{{ $activity['detail'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-4">Chưa có hoạt động nào</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900">Đơn hàng gần đây</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-sm bg-gradient-to-r from-red-600 to-blue-600 bg-clip-text text-transparent hover:from-red-700 hover:to-blue-700 font-semibold">
            Xem tất cả
            <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order['id'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order['customer_name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₫{{ number_format($order['total']) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($order['status']) {
                                    'completed', 'hoàn thành' => 'bg-green-100 text-green-800',
                                    'processing', 'đang xử lý' => 'bg-yellow-100 text-yellow-800',
                                    'shipping', 'đang giao' => 'bg-blue-100 text-blue-800',
                                    'cancelled', 'đã hủy' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusText = match($order['status']) {
                                    'completed', 'hoàn thành' => 'Hoàn thành',
                                    'processing', 'đang xử lý' => 'Đang xử lý',
                                    'shipping', 'đang giao' => 'Đang giao',
                                    'cancelled', 'đã hủy' => 'Đã hủy',
                                    default => 'Chờ xử lý'
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order['date'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order['id']) }}" class="text-red-600 hover:text-red-700 font-medium">Xem</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Chưa có đơn hàng nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Revenue Chart Data
    const revenueData12 = @json($revenueByMonth);
    const months12 = @json($months);
    const revenueData6 = @json($revenueByMonth6);
    const months6 = @json($months6);
    const revenueData3 = @json($revenueByMonth3);
    const months3 = @json($months3);
    
    // Format currency
    function formatCurrency(value) {
        if (value >= 1000000000) {
            return (value / 1000000000).toFixed(1) + 'B';
        } else if (value >= 1000000) {
            return (value / 1000000).toFixed(1) + 'M';
        } else if (value >= 1000) {
            return (value / 1000).toFixed(1) + 'K';
        }
        return value.toFixed(0);
    }
    
    // Chart configuration
    const chartConfig = {
        type: 'line',
        data: {
            labels: months12,
            datasets: [{
                label: 'Doanh thu (₫)',
                data: revenueData12,
                borderColor: 'rgb(14, 165, 233)',
                backgroundColor: 'rgba(14, 165, 233, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(14, 165, 233)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ₫' + formatCurrency(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₫' + formatCurrency(value);
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                    }
                },
                x: {
                    grid: {
                        display: false,
                    }
                }
            }
        }
    };
    
    // Initialize chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    let revenueChart = new Chart(ctx, chartConfig);
    
    // Update chart when period changes
    document.getElementById('revenuePeriod').addEventListener('change', function(e) {
        const period = parseInt(e.target.value);
        let labels, data;
        
        if (period === 12) {
            labels = months12;
            data = revenueData12;
        } else if (period === 6) {
            labels = months6;
            data = revenueData6;
        } else {
            labels = months3;
            data = revenueData3;
        }
        
        revenueChart.data.labels = labels;
        revenueChart.data.datasets[0].data = data;
        revenueChart.update();
    });
</script>
@endpush
@endsection

