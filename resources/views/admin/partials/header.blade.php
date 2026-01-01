<header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 sticky top-0 z-30 shadow-sm">
    <!-- Left Section: Sidebar Toggle -->
    <div class="flex items-center space-x-4 flex-1">
        <!-- Sidebar Toggle Button (Desktop & Mobile) -->
        <button id="sidebar-toggle" class="flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Toggle Sidebar">
            <i class="fas fa-indent text-xl"></i>
        </button>
    </div>
    
    <!-- Right Section: User Menu -->
    <div class="flex items-center space-x-4">
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
        
    });
</script>

