<header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 sticky top-0 z-30 shadow-sm">
    <!-- Left Section: Sidebar Toggle -->
    <div class="flex items-center space-x-4 flex-1">
        <!-- Sidebar Toggle Button (Desktop & Mobile) -->
        <button id="sidebar-toggle" class="flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Toggle Sidebar">
            <i class="fas fa-indent text-xl"></i>
        </button>
        <!-- Home Page Link -->
        <a href="{{ url('/') }}"
           target="_blank"
           rel="noopener noreferrer"
           class="flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
           title="Về trang chủ">
            <i class="fas fa-home text-xl"></i>
        </a>
    </div>
    
    <!-- Right Section: User Menu -->
    <div class="flex items-center space-x-4">
        <!-- Order Notifications -->
        <div class="relative" id="order-notify-container">
            <button id="order-notify-toggle"
                    class="relative flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                    title="Đơn hàng mới">
                <span class="absolute -top-1 -right-1 hidden" id="order-notify-dot"></span>
                <i class="fas fa-bell text-lg"></i>
                <span id="order-notify-badge"
                      class="hidden absolute -top-1.5 -right-1.5 bg-gradient-to-br from-red-500 to-rose-600 text-white text-[10px] font-bold rounded-full min-w-[20px] h-[20px] flex items-center justify-center px-1 shadow-lg ring-2 ring-white">
                    0
                </span>
            </button>

            <div id="order-notify-menu"
                 class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                <div class="px-4 py-3 border-b border-gray-200">
                    <p class="text-sm font-semibold text-gray-900">Đơn hàng mới</p>
                    <p id="order-notify-count-text" class="text-xs text-gray-500">Không có đơn hàng mới</p>
                </div>
                <div id="order-notify-list" class="max-h-80 overflow-y-auto">
                    <div class="px-4 py-4 text-sm text-gray-500 text-center">
                        Chưa có đơn hàng mới
                    </div>
                </div>
                <div class="px-4 py-2 border-t border-gray-200 text-center">
                    <a href="{{ route('admin.orders.index') }}"
                       class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Xem tất cả đơn hàng
                    </a>
                </div>
            </div>
        </div>

        <!-- User Dropdown -->
        <div class="relative" id="user-dropdown-container">
            <button id="user-dropdown-toggle" class="flex items-center space-x-3 p-2 hover:bg-gradient-to-r hover:from-red-50 hover:to-blue-50 rounded-lg transition-colors">
                <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm" style="background: linear-gradient(135deg, #ef4444 0%, #2563eb 100%);">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-900">Admin User</p>
                    <p class="text-xs text-gray-500">Quản trị viên</p>
                </div>
                <i class="fas fa-chevron-down text-gray-400 text-xs hidden md:block"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <div id="user-dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-red-50 hover:to-blue-50 transition-colors">
                    <i class="fas fa-user-circle mr-2 text-blue-600"></i>
                    Hồ sơ
                </a>
                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-red-50 hover:to-blue-50 transition-colors">
                    <i class="fas fa-cog mr-2 text-blue-600"></i>
                    Cài đặt
                </a>
                <hr class="my-1 border-gray-200">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    // User dropdown toggle
    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('user-dropdown-toggle');
        const menu = document.getElementById('user-dropdown-menu');
        const notifyToggle = document.getElementById('order-notify-toggle');
        const notifyMenu = document.getElementById('order-notify-menu');
        const notifyBadge = document.getElementById('order-notify-badge');
        const notifyDot = document.getElementById('order-notify-dot');
        const notifyList = document.getElementById('order-notify-list');
        const notifyCountText = document.getElementById('order-notify-count-text');
        
        if (toggle && menu) {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#user-dropdown-container')) {
                    menu.classList.add('hidden');
                }
            });
        }

        if (notifyToggle && notifyMenu) {
            notifyToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                notifyMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('#order-notify-container')) {
                    notifyMenu.classList.add('hidden');
                }
            });
        }

        const fetchUnreadOrders = async () => {
            try {
                const response = await fetch('{{ route('admin.orders.unread.count') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    return;
                }

                const data = await response.json();
                const count = parseInt(data.count || 0, 10);

                if (notifyBadge) {
                    if (count > 0) {
                        notifyBadge.textContent = count > 99 ? '99+' : count;
                        notifyBadge.classList.remove('hidden');
                    } else {
                        notifyBadge.classList.add('hidden');
                    }
                }

                if (notifyDot) {
                    if (count > 0) {
                        notifyDot.className = 'absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white shadow-sm';
                    } else {
                        notifyDot.className = 'hidden';
                    }
                }

                if (notifyCountText) {
                    notifyCountText.textContent = count > 0
                        ? `Có ${count} đơn hàng mới`
                        : 'Không có đơn hàng mới';
                }

                if (notifyList) {
                    if (!data.recent || data.recent.length === 0) {
                        notifyList.innerHTML = `
                            <div class="px-4 py-4 text-sm text-gray-500 text-center">
                                Chưa có đơn hàng mới
                            </div>
                        `;
                        return;
                    }

                    const baseOrderUrl = '{{ url('admin/orders') }}';
                    notifyList.innerHTML = data.recent.map(order => {
                        const orderCode = order.code_bill || order.id;
                        const orderDate = order.created_at ? new Date(order.created_at) : null;
                        const dateText = orderDate ? orderDate.toLocaleString('vi-VN') : '—';
                        return `
                            <a href="${baseOrderUrl}/${order.id}"
                               class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">#${orderCode}</p>
                                        <p class="text-xs text-gray-500">${order.name || ''}</p>
                                    </div>
                                    <div class="text-xs text-gray-400">${dateText}</div>
                                </div>
                            </a>
                        `;
                    }).join('');
                }
            } catch (e) {
                // ignore
            }
        };

        fetchUnreadOrders();
        setInterval(fetchUnreadOrders, 30000);
    });
</script>

