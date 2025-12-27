<aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 sidebar-transition lg:translate-x-0 -translate-x-full flex flex-col sidebar-main shadow-sm">
    <!-- Logo Section -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex items-center justify-center sidebar-logo-content">
            <img src="{{ asset('img/logo/logo_mksg.png') }}" 
                 alt="Logo" 
                 class="h-10 w-auto sidebar-logo flex-shrink-0">
        </div>
        <!-- Mobile Close Button -->
        <button id="sidebar-close" class="lg:hidden text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg p-1 transition-colors" onclick="toggleSidebar()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
        <ul class="space-y-1 px-3">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.dashboard') ? 'menu-item-active bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                    <i class="fas fa-home w-5 flex-shrink-0"></i>
                    <span class="ml-3 sidebar-menu-text font-medium">Dashboard</span>
                </a>
            </li>
            
            <!-- Categories -->
            <li>
                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.categories.*') ? 'menu-item-active bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                    <i class="fas fa-folder-tree w-5 flex-shrink-0"></i>
                    <span class="ml-3 sidebar-menu-text font-medium">Danh mục</span>
                </a>
            </li>
            
            <!-- Products with Submenu -->
            <li>
                <button type="button" 
                        id="products-menu-toggle"
                        class="w-full flex items-center justify-between px-4 py-3 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.brands.*') ? 'menu-item-active bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-box w-5 flex-shrink-0"></i>
                        <span class="ml-3 sidebar-menu-text font-medium">Sản phẩm</span>
                    </div>
                    <i class="fas fa-chevron-down sidebar-menu-text transition-transform duration-200" id="products-menu-icon"></i>
                </button>
                <ul id="products-submenu" class="hidden pl-2 mt-2 space-y-1.5">
                    <li>
                        <a href="{{ route('admin.products.index') }}" 
                           class="flex items-center px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-all duration-250 {{ request()->routeIs('admin.products.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <i class="fas fa-box w-4 flex-shrink-0"></i>
                            <span class="ml-3 sidebar-menu-text">Sản phẩm</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.brands.index') }}" 
                           class="flex items-center px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-all duration-250 {{ request()->routeIs('admin.brands.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <i class="fas fa-star w-4 flex-shrink-0"></i>
                            <span class="ml-3 sidebar-menu-text">Thương hiệu</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" 
                           class="flex items-center px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-all duration-250">
                            <i class="fas fa-fabric w-4 flex-shrink-0"></i>
                            <span class="ml-3 sidebar-menu-text">Chất liệu</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" 
                           class="flex items-center px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-all duration-250">
                            <i class="fas fa-palette w-4 flex-shrink-0"></i>
                            <span class="ml-3 sidebar-menu-text">Màu sắc</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" 
                           class="flex items-center px-4 py-2.5 text-sm text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-all duration-250">
                            <i class="fas fa-star-of-life w-4 flex-shrink-0"></i>
                            <span class="ml-3 sidebar-menu-text">Danh mục nổi bật</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <!-- Orders -->
            <li>
                <a href="{{ route('admin.orders.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.orders.*') ? 'menu-item-active bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                    <i class="fas fa-shopping-cart w-5 flex-shrink-0"></i>
                    <span class="ml-3 sidebar-menu-text font-medium">Đơn hàng</span>
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full sidebar-menu-text font-semibold">3</span>
                </a>
            </li>
            
            <!-- Customers -->
            <li>
                <a href="{{ route('admin.customers.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.customers.*') ? 'menu-item-active bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                    <i class="fas fa-users w-5 flex-shrink-0"></i>
                    <span class="ml-3 sidebar-menu-text font-medium">Khách hàng</span>
                </a>
            </li>
            
            <!-- News -->
            <li>
                <a href="{{ route('admin.news.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.news.*') ? 'menu-item-active bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                    <i class="fas fa-newspaper w-5 flex-shrink-0"></i>
                    <span class="ml-3 sidebar-menu-text font-medium">Tin tức</span>
                </a>
            </li>
            
            
            <!-- Sliders -->
            <li>
                <a href="{{ route('admin.sliders.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.sliders.*') ? 'menu-item-active bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                    <i class="fas fa-images w-5 flex-shrink-0"></i>
                    <span class="ml-3 sidebar-menu-text font-medium">Slider</span>
                </a>
            </li>
            
            <!-- Settings -->
            <li class="pt-4 border-t border-gray-200">
                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center px-4 py-3 text-gray-600 rounded-lg hover:bg-blue-50 hover:text-primary-600 transition-colors {{ request()->routeIs('admin.settings.*') ? 'menu-item-active bg-primary-50 text-primary-600 border-l-4 border-primary-600' : '' }}">
                    <i class="fas fa-cog w-5 flex-shrink-0"></i>
                    <span class="ml-3 sidebar-menu-text font-medium">Cài đặt</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <!-- User Section -->
    <div class="p-4 border-t border-gray-200 bg-gray-50 sidebar-user-section">
        <div class="flex items-center space-x-3 mb-3">
            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                <i class="fas fa-user text-white text-sm"></i>
            </div>
            <div class="flex-1 min-w-0 sidebar-user-info">
                <p class="text-sm font-semibold text-gray-900 truncate">Admin User</p>
                <p class="text-xs text-gray-500 truncate">admin@example.com</p>
            </div>
        </div>
        <a href="{{ route('admin.profile') }}" class="block w-full px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-white hover:text-primary-600 transition-colors text-center sidebar-menu-text font-medium border border-gray-200 hover:border-primary-300">
            <i class="fas fa-user-circle mr-2"></i>
            <span class="sidebar-menu-text">Hồ sơ</span>
        </a>
        <form action="{{ route('admin.logout') }}" method="POST" class="mt-2" id="logoutForm">
            @csrf
            <button type="submit" class="block w-full px-4 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 hover:text-red-700 transition-colors sidebar-menu-text font-medium border border-red-200 hover:border-red-300">
                <i class="fas fa-sign-out-alt mr-2"></i>
                <span class="sidebar-menu-text">Đăng xuất</span>
            </button>
        </form>
    </div>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }
    
    // Products Submenu Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        
        // Load saved state from localStorage
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            sidebar.classList.add('sidebar-collapsed');
        }
        
        // Products submenu toggle
        const productsMenuToggle = document.getElementById('products-menu-toggle');
        const productsSubmenu = document.getElementById('products-submenu');
        const productsMenuIcon = document.getElementById('products-menu-icon');
        
        if (productsMenuToggle && productsSubmenu) {
            // Check if any submenu item is active
            const isSubmenuActive = productsSubmenu.querySelector('a.bg-primary-50');
            if (isSubmenuActive) {
                productsSubmenu.classList.remove('hidden');
                if (productsMenuIcon) {
                    productsMenuIcon.classList.add('rotate-180');
                }
            }
            
            productsMenuToggle.addEventListener('click', function(e) {
                e.preventDefault();
                // Don't toggle if sidebar is collapsed
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    return;
                }
                productsSubmenu.classList.toggle('hidden');
                if (productsMenuIcon) {
                    productsMenuIcon.classList.toggle('rotate-180');
                }
            });
            
            // Hide submenu when sidebar is collapsed
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        if (sidebar.classList.contains('sidebar-collapsed')) {
                            productsSubmenu.classList.add('hidden');
                            if (productsMenuIcon) {
                                productsMenuIcon.classList.remove('rotate-180');
                            }
                        }
                    }
                });
            });
            
            observer.observe(sidebar, {
                attributes: true,
                attributeFilter: ['class']
            });
        }
    });
</script>

