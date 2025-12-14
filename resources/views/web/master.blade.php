<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mắt Kính Sài Gòn')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Quicksand', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="md:pb-0 pb-20">
    @include('web.partials.header')
    
    @include('web.partials.cart')
    
    @include('web.partials.mobile-sidebar')
    
    <main>
        @yield('content')
    </main>
    
    @include('web.partials.footer')
    @include('web.partials.mobile-contact-bar')
    <a class="top_button" href="#" style="display: block;">&nbsp;</a>
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>

