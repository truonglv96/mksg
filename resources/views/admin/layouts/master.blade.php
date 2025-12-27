<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Admin') }}</title>
    
    <!-- Preconnect to CDN for faster loading -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Google Fonts - Load with display=swap for better performance -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/app_admin.css') }}">
    
    <!-- Tailwind CSS from CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('css/admin/app_admin.css') }}" as="style">
    
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
            <main class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50 p-3">
                <!-- Breadcrumb -->
                @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                    @include('admin.partials.breadcrumb')
                @endif
                
                <!-- Page Content -->
                <div class="w-full">
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
    
    <!-- Scripts - Defer for better performance -->
    <script>
        // Fix Grammarly extension errors globally - must run early
        (function() {
            // Store original handlers
            const originalWindowError = window.onerror;
            const originalConsoleError = console.error;
            const originalConsoleWarn = console.warn;
            
            // Override window.onerror
            window.onerror = function(msg, url, line, col, error) {
                // Completely suppress Grammarly extension errors
                if (msg && typeof msg === 'string' && (
                    msg.indexOf('Grammarly') !== -1 || 
                    (msg.indexOf('dataset') !== -1 && (url && url.indexOf('Grammarly') !== -1)) ||
                    (error && error.stack && error.stack.indexOf('Grammarly') !== -1) ||
                    (url && url.indexOf('Grammarly') !== -1) ||
                    (msg.indexOf('Cannot read properties of null') !== -1 && url && url.indexOf('Grammarly') !== -1)
                )) {
                    return true; // Suppress error completely
                }
                if (originalWindowError) {
                    return originalWindowError.apply(this, arguments);
                }
                return false;
            };
            
            // Override console.error
            console.error = function(...args) {
                const message = args.join(' ');
                if (message && (
                    message.indexOf('Grammarly') !== -1 ||
                    (message.indexOf('dataset') !== -1 && message.indexOf('null') !== -1 && message.indexOf('Grammarly') !== -1) ||
                    (message.indexOf('Cannot read properties of null') !== -1 && message.indexOf('Grammarly') !== -1)
                )) {
                    return; // Suppress errors
                }
                originalConsoleError.apply(console, args);
            };
            
            console.warn = originalConsoleWarn;
            
            // Catch unhandled promise rejections from Grammarly
            window.addEventListener('unhandledrejection', function(event) {
                if (event.reason && (
                    (event.reason.message && event.reason.message.indexOf('Grammarly') !== -1) ||
                    (event.reason.stack && event.reason.stack.indexOf('Grammarly') !== -1)
                )) {
                    event.preventDefault();
                    return false;
                }
            }, true); // Use capture phase
            
            // Also catch errors from Grammarly script execution
            const originalAddEventListener = EventTarget.prototype.addEventListener;
            EventTarget.prototype.addEventListener = function(type, listener, options) {
                if (type === 'error') {
                    const wrappedListener = function(event) {
                        try {
                            if (listener) {
                                return listener.call(this, event);
                            }
                        } catch (e) {
                            if (e && e.message && e.message.indexOf('Grammarly') === -1) {
                                throw e;
                            }
                        }
                    };
                    return originalAddEventListener.call(this, type, wrappedListener, options);
                }
                return originalAddEventListener.call(this, type, listener, options);
            };
        })();
    </script>
    <script defer>
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
        // setTimeout(() => {
        //     const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
        //     alerts.forEach(alert => {
        //         alert.style.transition = 'opacity 0.3s';
        //         alert.style.opacity = '0';
        //         setTimeout(() => alert.remove(), 300);
        //     });
        // }, 5000);
    </script>
    
    
    <!-- Axios from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        window.axios = axios;
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    </script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" onload="console.log('Swiper đã load xong')" onerror="console.error('Lỗi load Swiper')"></script>
    
    <!-- Custom JS - Load sau Swiper -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Admin JS - Defer for better performance -->
    <script src="{{ asset('js/admin/app_admin.js') }}" defer></script>
    @stack('scripts')
</body>
</html>

