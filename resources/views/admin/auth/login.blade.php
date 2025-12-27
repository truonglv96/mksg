<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - Admin Panel</title>
    
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
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chinese Calligraphy Font for Dragon & Phoenix effect -->
    <link href="https://fonts.googleapis.com/css2?family=Ma+Shan+Zheng&family=ZCOOL+QingKe+HuangYou&family=Long+Cang&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }
        
        /* Animated Background Gradient */
        .gradient-bg {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe, #00f2fe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Floating Shapes Animation */
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 20s infinite ease-in-out;
        }
        
        .shape-1 {
            width: 300px;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            bottom: -100px;
            right: -100px;
            animation-delay: 5s;
        }
        
        .shape-3 {
            width: 250px;
            height: 250px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            top: 50%;
            right: -125px;
            animation-delay: 10s;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        /* Glass Morphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        
        /* Input Group Animation */
        .input-group {
            position: relative;
            margin-bottom: 2rem;
        }
        
        .input-field {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #0ea5e9;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
            transform: translateY(-2px);
        }
        
        .input-field:focus + .input-label,
        .input-field:not(:placeholder-shown) + .input-label {
            top: -0.75rem;
            left: 0.75rem;
            font-size: 0.75rem;
            color: #0ea5e9;
            background: white;
            padding: 0 0.5rem;
        }
        
        .input-label {
            position: absolute;
            top: 1rem;
            left: 3rem;
            color: #6b7280;
            font-size: 1rem;
            pointer-events: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .input-field:focus ~ .input-icon {
            color: #0ea5e9;
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Button Animation */
        .btn-primary {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-primary span {
            position: relative;
            z-index: 1;
        }
        
        /* Logo Animation */
        .logo-container {
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        /* Error/Success Messages */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Remember Me Checkbox */
        .checkbox-custom {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 0.375rem;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .checkbox-custom:checked {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
        }
        
        .checkbox-custom:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 0.875rem;
            font-weight: bold;
        }
        
        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: #0ea5e9;
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Fade In Animation */
        .fade-in {
            animation: fadeIn 0.6s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Loading Spinner */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Balloon Container */
        .balloon-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: visible;
            z-index: 1;
            pointer-events: none;
        }
        
        /* Beautiful Balloon Design */
        .balloon {
            position: fixed;
            width: var(--size, 70px);
            height: var(--size, 90px);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            opacity: 0;
            bottom: -150px;
            left: var(--start-x, 50%);
            cursor: pointer;
            transition: transform 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
            will-change: transform, opacity;
        }
        
        /* Balloon highlight for 3D effect */
        .balloon::before {
            content: '';
            position: absolute;
            top: 20%;
            left: 30%;
            width: 25%;
            height: 25%;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            filter: blur(8px);
        }
        
        /* Balloon string */
        .balloon::after {
            content: '';
            position: absolute;
            bottom: -25px;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 120px;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.1));
            border-radius: 1px;
        }
        
        /* Balloon Colors with beautiful gradients */
        .balloon.red {
            background: radial-gradient(circle at 30% 30%, #ff6b6b, #ee5a6f, #dc3545);
            box-shadow: 
                inset -15px -15px 0 rgba(0, 0, 0, 0.15),
                inset 10px 10px 0 rgba(255, 255, 255, 0.2),
                0 6px 20px rgba(255, 107, 107, 0.3);
        }
        
        .balloon.blue {
            background: radial-gradient(circle at 30% 30%, #4ecdc4, #44a08d, #2d8659);
            box-shadow: 
                inset -15px -15px 0 rgba(0, 0, 0, 0.15),
                inset 10px 10px 0 rgba(255, 255, 255, 0.2),
                0 6px 20px rgba(78, 205, 196, 0.3);
        }
        
        .balloon.yellow {
            background: radial-gradient(circle at 30% 30%, #ffe66d, #ffd93d, #ffc107);
            box-shadow: 
                inset -15px -15px 0 rgba(0, 0, 0, 0.15),
                inset 10px 10px 0 rgba(255, 255, 255, 0.2),
                0 6px 20px rgba(255, 230, 109, 0.3);
        }
        
        .balloon.green {
            background: radial-gradient(circle at 30% 30%, #95e1d3, #6bcf7f, #28a745);
            box-shadow: 
                inset -15px -15px 0 rgba(0, 0, 0, 0.15),
                inset 10px 10px 0 rgba(255, 255, 255, 0.2),
                0 6px 20px rgba(149, 225, 211, 0.3);
        }
        
        .balloon.purple {
            background: radial-gradient(circle at 30% 30%, #a8e6cf, #dcedc1, #9c88ff);
            box-shadow: 
                inset -15px -15px 0 rgba(0, 0, 0, 0.15),
                inset 10px 10px 0 rgba(255, 255, 255, 0.2),
                0 6px 20px rgba(168, 230, 207, 0.3);
        }
        
        .balloon.pink {
            background: radial-gradient(circle at 30% 30%, #ff9ff3, #f368e0, #e91e63);
            box-shadow: 
                inset -15px -15px 0 rgba(0, 0, 0, 0.15),
                inset 10px 10px 0 rgba(255, 255, 255, 0.2),
                0 6px 20px rgba(255, 159, 243, 0.3);
        }
        
        .balloon.orange {
            background: radial-gradient(circle at 30% 30%, #ffa07a, #ff7f50, #ff5722);
            box-shadow: 
                inset -15px -15px 0 rgba(0, 0, 0, 0.15),
                inset 10px 10px 0 rgba(255, 255, 255, 0.2),
                0 6px 20px rgba(255, 160, 122, 0.3);
        }
        
        .balloon.cyan {
            background: radial-gradient(circle at 30% 30%, #00d4ff, #00a8cc, #0097a7);
            box-shadow: 
                inset -15px -15px 0 rgba(0, 0, 0, 0.15),
                inset 10px 10px 0 rgba(255, 255, 255, 0.2),
                0 6px 20px rgba(0, 212, 255, 0.3);
        }
        
        .balloon.lavender {
            background: radial-gradient(circle at 30% 30%, #e6e6fa, #d8bfd8, #ba55d3);
            box-shadow: 
                inset -15px -15px 0 rgba(0, 0, 0, 0.15),
                inset 10px 10px 0 rgba(255, 255, 255, 0.2),
                0 6px 20px rgba(230, 230, 250, 0.3);
        }
        
        /* Floating Animation - from bottom to top */
        @keyframes floatUp {
            0% {
                bottom: -150px;
                transform: translateX(0) rotate(0deg);
                opacity: 0;
            }
            5% {
                opacity: 0.9;
            }
            50% {
                transform: translateX(var(--drift-x, 0px)) rotate(var(--rotate, 5deg));
                opacity: 0.9;
            }
            95% {
                opacity: 0.9;
            }
            100% {
                bottom: 100vh;
                transform: translateX(var(--drift-x, 0px)) rotate(var(--rotate, 10deg));
                opacity: 0;
            }
        }
        
        /* Sway animation for natural movement */
        @keyframes sway {
            0%, 100% {
                transform: translateX(0) rotate(0deg);
            }
            25% {
                transform: translateX(8px) rotate(3deg);
            }
            75% {
                transform: translateX(-8px) rotate(-3deg);
            }
        }
        
        .balloon.floating {
            animation: 
                floatUp var(--duration, 8s) linear forwards,
                sway 4s ease-in-out infinite;
        }
        
        @keyframes pop {
            0% {
                transform: scale(1);
                opacity: 0.7;
            }
            50% {
                transform: scale(1.3);
                opacity: 0.5;
            }
            100% {
                transform: scale(0);
                opacity: 0;
            }
        }
        
        .balloon.popping {
            animation: pop 0.4s ease-out forwards;
        }
        
        .balloon.popping::before,
        .balloon.popping::after {
            animation: pop 0.4s ease-out forwards;
        }
        
        /* Particle effect when balloon pops */
        .particle {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            pointer-events: none;
            animation: particleFall 1s ease-out forwards;
        }
        
        @keyframes particleFall {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(var(--tx), var(--ty)) scale(0);
                opacity: 0;
            }
        }
        
        /* Calligraphy Text - Dragon & Phoenix Effect */
        .calligraphy-title {
            font-family: 'Ma Shan Zheng', 'Long Cang', cursive;
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, #b8860b, #ffd700, #ff4500, #ff6347, #ffd700, #b8860b);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: 
                dragonFly 4s ease-in-out infinite,
                gradientShift 5s ease infinite;
            position: relative;
            display: inline-block;
            text-shadow: 
                2px 2px 4px rgba(0, 0, 0, 0.3),
                0 0 20px rgba(255, 215, 0, 0.6),
                0 0 40px rgba(255, 215, 0, 0.4);
            letter-spacing: 0.1em;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5));
            -webkit-text-stroke: 0.5px rgba(184, 134, 11, 0.3);
        }
        
        .calligraphy-subtitle {
            font-family: 'Ma Shan Zheng', 'Long Cang', cursive;
            font-size: 1.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, #dc143c, #ff6347, #00ced1, #ffd700, #dc143c);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: 
                phoenixDance 3s ease-in-out infinite,
                gradientShift 4s ease infinite;
            position: relative;
            display: inline-block;
            letter-spacing: 0.05em;
            text-shadow: 
                1px 1px 3px rgba(0, 0, 0, 0.3),
                0 0 15px rgba(220, 20, 60, 0.5),
                0 0 30px rgba(220, 20, 60, 0.3);
            filter: drop-shadow(1px 1px 3px rgba(0, 0, 0, 0.4));
            -webkit-text-stroke: 0.3px rgba(220, 20, 60, 0.3);
        }
        
        /* Dragon Flying Animation */
        @keyframes dragonFly {
            0%, 100% {
                transform: translateY(0) rotate(0deg) scale(1);
            }
            25% {
                transform: translateY(-8px) rotate(-2deg) scale(1.02);
            }
            50% {
                transform: translateY(-12px) rotate(0deg) scale(1.05);
            }
            75% {
                transform: translateY(-8px) rotate(2deg) scale(1.02);
            }
        }
        
        /* Phoenix Dancing Animation */
        @keyframes phoenixDance {
            0%, 100% {
                transform: translateY(0) rotate(0deg) scale(1);
            }
            33% {
                transform: translateY(-5px) rotate(-1deg) scale(1.03);
            }
            66% {
                transform: translateY(-8px) rotate(1deg) scale(1.05);
            }
        }
        
        /* Gradient Shift Animation */
        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
        
        /* Individual Character Animation for Dragon Effect */
        .calligraphy-title span,
        .calligraphy-subtitle span {
            display: inline-block;
            animation: characterFloat 2s ease-in-out infinite;
            animation-delay: calc(var(--i) * 0.1s);
        }
        
        @keyframes characterFloat {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-6px) rotate(1deg);
            }
        }
        
        /* Glow Effect - Enhanced */
        .calligraphy-title::before,
        .calligraphy-subtitle::before {
            content: attr(data-text);
            position: absolute;
            left: 0;
            top: 0;
            z-index: -1;
            background: linear-gradient(135deg, #b8860b, #ffd700, #ff4500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: blur(6px);
            opacity: 0.8;
            animation: glowPulse 2s ease-in-out infinite;
        }
        
        @keyframes glowPulse {
            0%, 100% {
                opacity: 0.6;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.03);
            }
        }
        
        /* Additional shadow layer for depth */
        .calligraphy-title::after,
        .calligraphy-subtitle::after {
            content: attr(data-text);
            position: absolute;
            left: 2px;
            top: 2px;
            z-index: -2;
            color: rgba(0, 0, 0, 0.3);
            -webkit-text-stroke: 0;
            filter: blur(3px);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <!-- Balloon Container -->
    <div class="balloon-container" id="balloonContainer"></div>
    
    <!-- Animated Background Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    
    <!-- Login Card -->
    <div class="glass-card rounded-2xl p-8 w-full max-w-md fade-in relative" style="z-index: 10;">
        <!-- Logo -->
        <div class="text-center mb-8 logo-container">
            <img src="{{ asset('img/logo/logo_mksg.png') }}" alt="Logo" class="h-16 mx-auto mb-4">
            <h1 class="calligraphy-title mb-2" data-text="Chào mừng trở lại">
                <span style="--i: 0">Ch</span><span style="--i: 1">ào</span> 
                <span style="--i: 2">m</span><span style="--i: 3">ừng</span> 
                <span style="--i: 4">tr</span><span style="--i: 5">ở</span> 
                <span style="--i: 6">l</span><span style="--i: 7">ại</span>
            </h1>
            <p class="calligraphy-subtitle" data-text="Đăng nhập vào hệ thống quản trị">
                <span style="--i: 0">Đ</span><span style="--i: 1">ăng</span> 
                <span style="--i: 2">n</span><span style="--i: 3">hập</span> 
                <span style="--i: 4">v</span><span style="--i: 5">ào</span> 
                <span style="--i: 6">h</span><span style="--i: 7">ệ</span> 
                <span style="--i: 8">t</span><span style="--i: 9">hống</span> 
                <span style="--i: 10">q</span><span style="--i: 11">uản</span> 
                <span style="--i: 12">t</span><span style="--i: 13">rị</span>
            </p>
        </div>
        
        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert bg-red-50 border border-red-200 text-red-800">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert bg-green-50 border border-green-200 text-green-800">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        <!-- Login Form -->
        <form action="{{ route('admin.login.post') }}" method="POST" id="loginForm">
            @csrf
            
            <!-- Login Input (accepts username or email) -->
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input 
                    type="text" 
                    name="login" 
                    id="login"
                    class="input-field" 
                    placeholder=" "
                    value="{{ old('login') }}"
                    required
                    autocomplete="username"
                    autofocus>
                <label for="login" class="input-label">Tên đăng nhập hoặc Email</label>
            </div>
            
            <!-- Password Input -->
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    class="input-field" 
                    placeholder=" "
                    required
                    autocomplete="current-password">
                <label for="password" class="input-label">Mật khẩu</label>
                <span class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye" id="toggleIcon"></i>
                </span>
            </div>
            
            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center cursor-pointer group">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        class="checkbox-custom mr-2"
                        {{ old('remember') ? 'checked' : '' }}>
                    <span class="text-gray-700 group-hover:text-primary-600 transition-colors">Ghi nhớ đăng nhập</span>
                </label>
                <a href="#" class="text-primary-600 hover:text-primary-700 font-medium text-sm transition-colors">
                    Quên mật khẩu?
                </a>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="btn-primary" id="submitBtn">
                <span id="buttonText">Đăng nhập</span>
                <span id="buttonLoader" class="hidden">
                    <span class="spinner mr-2"></span>
                    Đang xử lý...
                </span>
            </button>
        </form>
        
        <!-- Footer -->
        <div class="mt-6 text-center text-gray-600 text-sm">
            <p>© {{ date('Y') }} Admin Panel. All rights reserved.</p>
        </div>
    </div>
    
    <script>
        // Balloon System
        const balloonContainer = document.getElementById('balloonContainer');
        const balloonColors = ['red', 'blue', 'yellow', 'green', 'purple', 'pink', 'orange', 'cyan', 'lavender'];
        let balloons = [];
        let balloonIdCounter = 0;
        
        // Create a beautiful balloon
        function createBalloon() {
            const balloon = document.createElement('div');
            const color = balloonColors[Math.floor(Math.random() * balloonColors.length)];
            balloon.className = `balloon ${color} floating`;
            balloon.id = `balloon-${balloonIdCounter++}`;
            
            // Random starting position from bottom (0-100% of screen width)
            const startX = Math.random() * 100;
            // Random drift left/right during flight
            const driftX = (Math.random() - 0.5) * 80; 
            // Random rotation for natural movement
            const rotate = (Math.random() - 0.5) * 15; 
            // Random size for variety (60-90px)
            const size = 60 + Math.random() * 30;
            // Random duration 6-10 seconds (faster!)
            const duration = 6 + Math.random() * 4;
            
            balloon.style.setProperty('--start-x', `${startX}%`);
            balloon.style.setProperty('--size', `${size}px`);
            balloon.style.setProperty('--drift-x', `${driftX}px`);
            balloon.style.setProperty('--rotate', `${rotate}deg`);
            balloon.style.setProperty('--duration', `${duration}s`);
            balloon.style.left = `${startX}%`;
            balloon.style.bottom = '-150px';
            
            balloonContainer.appendChild(balloon);
            balloons.push(balloon);
            
            // Force reflow to ensure animation starts
            balloon.offsetHeight;
            
            // Remove balloon after animation completes
            setTimeout(() => {
                if (balloon.parentNode) {
                    balloon.remove();
                    balloons = balloons.filter(b => b !== balloon);
                }
            }, duration * 1000);
        }
        
        // Pop a balloon with particle effect
        function popBalloon(balloon) {
            if (!balloon || balloon.classList.contains('popping')) return;
            
            const rect = balloon.getBoundingClientRect();
            const x = rect.left + rect.width / 2;
            const y = rect.top + rect.height / 2;
            
            // Add pop animation
            balloon.classList.add('popping');
            
            // Create particles
            const particleCount = 12;
            const balloonColor = getComputedStyle(balloon).background;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = `${x}px`;
                particle.style.top = `${y}px`;
                particle.style.background = balloonColor;
                
                const angle = (Math.PI * 2 * i) / particleCount;
                const velocity = 50 + Math.random() * 50;
                const tx = Math.cos(angle) * velocity;
                const ty = Math.sin(angle) * velocity;
                
                particle.style.setProperty('--tx', `${tx}px`);
                particle.style.setProperty('--ty', `${ty}px`);
                
                document.body.appendChild(particle);
                
                setTimeout(() => particle.remove(), 1000);
            }
            
            // Remove balloon after animation
            setTimeout(() => {
                if (balloon.parentNode) {
                    balloon.remove();
                    balloons = balloons.filter(b => b !== balloon);
                }
            }, 400);
        }
        
        // Pop random balloon
        function popRandomBalloon() {
            if (balloons.length === 0) return;
            
            const randomIndex = Math.floor(Math.random() * balloons.length);
            const balloon = balloons[randomIndex];
            popBalloon(balloon);
        }
        
        // Initialize balloons
        function initBalloons() {
            // Create many initial balloons (50 balloons) - faster creation
            for (let i = 0; i < 50; i++) {
                setTimeout(() => createBalloon(), i * 80);
            }
            
            // Continuously create new balloons to maintain many balloons on screen
            setInterval(() => {
                // Keep 40-50 balloons on screen for continuous effect
                if (balloons.length < 40) {
                    createBalloon();
                }
            }, 400);
        }
        
        // Input typing handlers
        let lastInputLength = {
            login: 0,
            password: 0
        };
        
        function handleInputTyping(inputId) {
            const input = document.getElementById(inputId);
            const currentLength = input.value.length;
            const lastLength = lastInputLength[inputId];
            
            // If user is typing (length increased)
            if (currentLength > lastLength) {
                const charsTyped = currentLength - lastLength;
                
                // Pop balloons based on characters typed
                for (let i = 0; i < charsTyped && balloons.length > 0; i++) {
                    setTimeout(() => {
                        popRandomBalloon();
                    }, i * 100);
                }
            }
            
            lastInputLength[inputId] = currentLength;
        }
        
        // Password Toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Form Submission Animation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const buttonText = document.getElementById('buttonText');
            const buttonLoader = document.getElementById('buttonLoader');
            
            buttonText.classList.add('hidden');
            buttonLoader.classList.remove('hidden');
            submitBtn.disabled = true;
        });
        
        // Input Focus Animation Enhancement
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focused');
            });
            
            // Handle typing events
            input.addEventListener('input', function() {
                handleInputTyping(this.id);
            });
            
            // Handle keypress for better responsiveness
            input.addEventListener('keydown', function(e) {
                // Pop a balloon on keydown for immediate feedback
                if (e.key.length === 1 && balloons.length > 0) {
                    setTimeout(() => popRandomBalloon(), 50);
                }
            });
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
        
        // Initialize balloons immediately when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                initBalloons();
            });
        } else {
            // DOM is already ready
            initBalloons();
        }
        
        // Also initialize on window load as backup
        window.addEventListener('load', () => {
            if (balloons.length === 0) {
                initBalloons();
            }
        });
    </script>
</body>
</html>

