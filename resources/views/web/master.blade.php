<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/favicon.ico">
    <title>@yield('title', config('texts.page_title'))</title>
    @php
        $defaultTitle = $settings->meta_title ?? config('texts.page_title');
        $defaultDescription = $settings->meta_description ?? null;
        $defaultKeywords = $settings->meta_keyword ?? null;
        $defaultCanonical = request()->url();
        $defaultImage = ($settings && $settings->logo)
            ? $settings->getLogo()
            : asset('img/logo.png');
        $defaultImageSecure = preg_replace('/^http:/i', 'https:', $defaultImage);
    @endphp
    @hasSection('meta')
        @yield('meta')
    @else
        @if(!empty($defaultDescription))
            <meta name="description" content="{{ trim(strip_tags($defaultDescription)) }}">
        @endif
        @if(!empty($defaultKeywords))
            <meta name="keywords" content="{{ $defaultKeywords }}">
        @endif
        <link rel="canonical" href="{{ $defaultCanonical }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $defaultTitle }}">
        @if(!empty($defaultDescription))
            <meta property="og:description" content="{{ trim(strip_tags($defaultDescription)) }}">
        @endif
        <meta property="og:url" content="{{ $defaultCanonical }}">
        <meta property="og:site_name" content="{{ $defaultTitle }}">
        <meta property="og:image" content="{{ $defaultImage }}">
        <meta property="og:image:secure_url" content="{{ $defaultImageSecure }}">
        <meta property="og:image:alt" content="{{ $defaultTitle }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $defaultTitle }}">
        @if(!empty($defaultDescription))
            <meta name="twitter:description" content="{{ trim(strip_tags($defaultDescription)) }}">
        @endif
        <meta name="twitter:image" content="{{ $defaultImage }}">
    @endif
    
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

