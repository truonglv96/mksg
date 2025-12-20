<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Admin') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        sidebar: {
                            bg: '#ffffff',
                            hover: '#f1f5f9',
                            active: '#0ea5e9',
                            border: '#e2e8f0',
                            text: '#475569',
                            textHover: '#0f172a',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Sidebar transitions */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        /* Active menu item - now handled by inline classes */
        
        /* Sidebar collapsed state - Option 1: Collapsed to icon-only */
        .sidebar-main.sidebar-collapsed {
            width: 80px !important;
        }
        
        /* Sidebar collapsed state - Option 2: Completely hidden (uncomment to use) */
        /* .sidebar-main.sidebar-collapsed {
            transform: translateX(-100%);
        } */
        .sidebar-main.sidebar-collapsed .sidebar-menu-text,
        .sidebar-main.sidebar-collapsed .sidebar-user-info {
            display: none !important;
        }
        .sidebar-main.sidebar-collapsed .sidebar-logo-content {
            justify-content: center;
        }
        .sidebar-main.sidebar-collapsed nav ul li a {
            justify-content: center;
            padding-left: 1rem;
            padding-right: 1rem;
            border-left-width: 0 !important;
        }
        .sidebar-main.sidebar-collapsed .sidebar-user-section {
            padding: 1rem 0.5rem;
        }
        .sidebar-main.sidebar-collapsed .sidebar-user-section a,
        .sidebar-main.sidebar-collapsed .sidebar-user-section button {
            justify-content: center;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        .sidebar-main.sidebar-collapsed nav ul li a span.sidebar-menu-text,
        .sidebar-main.sidebar-collapsed .sidebar-user-section .sidebar-menu-text {
            display: none !important;
        }
        /* Smooth transition for sidebar width */
        .sidebar-main {
            transition: width 0.3s ease-in-out;
        }
        
        /* Logo styling */
        .sidebar-logo {
            max-height: 40px;
            object-fit: contain;
            transition: max-height 0.3s ease-in-out;
        }
        .sidebar-main.sidebar-collapsed .sidebar-logo {
            max-height: 32px;
        }
        
        /* Ensure Font Awesome icons display correctly */
        .fas, .far, .fal, .fab, .fa {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands", "Font Awesome 6 Pro" !important;
            font-weight: 900;
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
        }
        
        .far {
            font-weight: 400;
        }
        
        .fab {
            font-family: "Font Awesome 6 Brands" !important;
            font-weight: 400;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')
        
        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            @include('admin.partials.header')
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50 p-6">
                <!-- Breadcrumb -->
                @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                    @include('admin.partials.breadcrumb')
                @endif
                
                <!-- Page Content -->
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            @include('admin.partials.footer')
        </div>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>
    
    <!-- Sidebar Toggle Button (shown when sidebar is collapsed) -->
    <button id="sidebar-open-btn" class="hidden fixed left-4 top-4 z-50 w-10 h-10 bg-sidebar-bg text-white rounded-lg shadow-lg hover:bg-sidebar-hover transition-all duration-300 flex items-center justify-center" title="Mở sidebar">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Scripts -->
    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            
            // Load saved state from localStorage
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true' && window.innerWidth >= 1024) {
                sidebar.classList.add('sidebar-collapsed');
            }
            
            // Toggle sidebar function
            function toggleSidebar() {
                if (window.innerWidth < 1024) {
                    // Mobile: slide in/out
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                } else {
                    // Desktop: collapse/expand
                    const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                    if (isCollapsed) {
                        sidebar.classList.remove('sidebar-collapsed');
                        localStorage.setItem('sidebarCollapsed', 'false');
                    } else {
                        sidebar.classList.add('sidebar-collapsed');
                        localStorage.setItem('sidebarCollapsed', 'true');
                    }
                }
            }
            
            // Attach toggle event
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            
            // Close sidebar when clicking overlay on mobile
            if (overlay) {
                overlay.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                        overlay.classList.add('hidden');
                    }
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    // Desktop: remove mobile slide state
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.add('hidden');
                    // Restore collapsed state if saved
                    const savedState = localStorage.getItem('sidebarCollapsed');
                    if (savedState === 'true') {
                        sidebar.classList.add('sidebar-collapsed');
                    } else {
                        sidebar.classList.remove('sidebar-collapsed');
                    }
                } else {
                    // Mobile: remove collapsed state
                    sidebar.classList.remove('sidebar-collapsed');
                }
            });
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>

