@extends('web.master')

@section('title', $title ?? 'Sản Phẩm - Mắt Kính Sài Gòn')

@section('content')
<main class="container mx-auto px-4 py-8">

    <!-- Frame Styles Filter Section -->
    <section class="mb-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">KIỂU DÁNG GỌNG</h2>

        <!-- Frame Style Grid -->
        <div class="grid grid-cols-3 md:grid-cols-6 lg:grid-cols-12 gap-3 mb-4">
            <!-- Khoan Ốc -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
                <!-- <p class="text-xs font-medium text-gray-700">KHOAN ỐC</p> -->
            </button>

            <!-- Nguyên Khung -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
                <!-- <p class="text-xs font-medium text-gray-700">NGUYÊN KHUNG</p> -->
            </button>

            <!-- Nửa Khung -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
                <!-- <p class="text-xs font-medium text-gray-700">NỬA KHUNG</p> -->
            </button>

            <!-- Chữ Nhật -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
                <!-- <p class="text-xs font-medium text-gray-700">CHỮ NHẬT</p> -->
            </button>

            <!-- Đa Giác -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
            </button>

            <!-- Phi Công -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
            </button>

            <!-- Club Master -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
            </button>

            <!-- Mắt Mèo -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
            </button>

            <!-- Tròn -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
            </button>

            <!-- Vuông -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
            </button>

            <!-- Oval -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
            </button>

            <!-- Wayfarer -->
            <button
                class="frame-style-btn bg-white border border-gray-200 rounded-lg p-3 hover:border-red-600 hover:shadow-md transition-all text-center">
                <div class="flex justify-center mb-2">
                    <img src="https://matkinhhanghieu.com/wp-content/uploads/2024/05/nguyen-khung.svg" alt="">
                </div>
            </button>
        </div>

        <!-- Additional Filters Row (Desktop) -->
        <div class="hidden md:flex flex-wrap gap-3 items-center mb-4">
            <button
                class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 flex items-center gap-2">
                Giá
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <button
                class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 flex items-center gap-2">
                Chủng Loại
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <button
                class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 flex items-center gap-2">
                Thiết Kế Ve Mũi
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Filter Button -->
        <div class="md:hidden mb-4">
            <button id="mobile-filter-btn"
                class="w-full px-4 py-3 bg-red-600 text-white rounded-lg font-medium flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                Bộ Lọc Sản Phẩm
            </button>
        </div>

        <!-- Sort & Results Info -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
            <div class="text-sm text-gray-600">
                Hiển thị <span class="font-semibold">1-12</span> của <span class="font-semibold">2414</span> kết quả
            </div>
            <select
                class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-medium text-gray-700 border-none focus:ring-2 focus:ring-red-600">
                <option>Mới nhất</option>
                <option>Giá: Thấp đến Cao</option>
                <option>Giá: Cao đến Thấp</option>
                <option>Tên: A-Z</option>
                <option>Tên: Z-A</option>
            </select>
        </div>
    </section>

    <!-- Main Content: Sidebar + Products -->
    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Desktop Sidebar Filter -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-4">

                <!-- Tìm Theo Giá -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">TÌM THEO GIÁ</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all">Dưới
                            500k</button>
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all">500k
                            - 1 Triệu</button>
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all">1
                            - 3 Triệu</button>
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all">3
                            - 5 Triệu</button>
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all col-span-2">>
                            5 Triệu</button>
                    </div>
                </div>

                <!-- Chủng Loại -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">CHỦNG LOẠI</h3>
                    <div class="grid grid-cols-4 sm:grid-cols-6 xl:grid-cols-8 gap-1 sm:gap-1.5">
                        <button type="button" aria-label="Xanh Navy và Đen"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #0b1b4f 50%, #1a2c68 50%);"></button>
                        <button type="button" aria-label="Đỏ và Đen"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #d62828 50%, #000000 50%);"></button>
                        <button type="button" aria-label="Vàng và Đen"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #ffc300 50%, #000000 50%);"></button>
                        <button type="button" aria-label="Xám Đậm và Xám Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #1c1c1c 50%, #4a4a4a 50%);"></button>
                        <button type="button" aria-label="Đen và Tím Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #050505 50%, #c084f5 50%);"></button>
                        <button type="button" aria-label="Đen và Hồng Neon"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #111111 50%, #f9a8d4 50%);"></button>
                        <button type="button" aria-label="Đen và Vàng Chanh"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #000000 50%, #ffcf56 50%);"></button>
                        <button type="button" aria-label="Đen và Xanh Lá"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #000000 50%, #7cfc00 50%);"></button>
                        <button type="button" aria-label="Xanh Lam và Đen"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #3b82f6 50%, #020617 50%);"></button>
                        <button type="button" aria-label="Trắng và Xám Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #f7f7f7 50%, #b5b5b5 50%);"></button>
                        <button type="button" aria-label="Xám Bạc và Trắng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #e0e0e0 50%, #fdfdfd 50%);"></button>
                        <button type="button" aria-label="Tím Gradient"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #c084f5 0%, #8b5cf6 100%);"></button>
                        <button type="button" aria-label="Hồng và Vàng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #f472b6 50%, #facc15 50%);"></button>
                        <button type="button" aria-label="Tím và Vàng Đậm"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #7c3aed 50%, #fbbf24 50%);"></button>
                        <button type="button" aria-label="Tím và Đỏ"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #9333ea 50%, #f43f5e 50%);"></button>
                        <button type="button" aria-label="Hồng Phấn"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fbcfe8 50%, #fee2f2 50%);"></button>
                        <button type="button" aria-label="Nâu Gỗ"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #8b5a2b 50%, #d9a066 50%);"></button>
                        <button type="button" aria-label="Cam và Nâu Đậm"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #ffb347 50%, #6b1d1d 50%);"></button>
                        <button type="button" aria-label="Đồng và Be"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #cd7f32 50%, #f7e7ce 50%);"></button>
                        <button type="button" aria-label="Xanh Da Trời Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #38bdf8 50%, #e0f2fe 50%);"></button>
                        <button type="button" aria-label="Xanh Lá Đậm"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #7fbf7f 50%, #4c7a4c 50%);"></button>
                        <button type="button" aria-label="Xanh Ngọc và Xanh Dương Đậm"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #13505b 50%, #0b7285 50%);"></button>
                        <button type="button" aria-label="Xanh Lục và Kem"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #146356 50%, #f7f7f2 50%);"></button>
                        <button type="button" aria-label="Xanh Navy Đậm"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #0f3d6c 50%, #172554 50%);"></button>
                        <button type="button" aria-label="Vàng Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #efd600 50%, #fffbcc 50%);"></button>
                        <button type="button" aria-label="Đen và Vàng Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #000000 50%, #ffd166 50%);"></button>
                        <button type="button" aria-label="Hồng và Vàng Kem"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #ffc0cb 50%, #ffd700 50%);"></button>
                        <button type="button" aria-label="Nâu và Be"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #d2691e 50%, #f4a460 50%);"></button>
                        <button type="button" aria-label="Nâu Đậm và Đồng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #261c15 50%, #b5835a 50%);"></button>
                        <button type="button" aria-label="Bạc Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #cfcfcf 50%, #f4f4f5 50%);"></button>
                        <button type="button" aria-label="Tím và Xanh"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #a78bfa 50%, #38bdf8 50%);"></button>
                        <button type="button" aria-label="Vàng và Đỏ"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fde047 50%, #ef4444 50%);"></button>
                        <button type="button" aria-label="Cam và Đen"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #f97316 50%, #000000 50%);"></button>
                        <button type="button" aria-label="Hồng và Vàng Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fb7185 50%, #fde68a 50%);"></button>
                        <button type="button" aria-label="Đỏ và Navy"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #ef4444 50%, #111827 50%);"></button>
                        <button type="button" aria-label="Đỏ và Vàng Chanh"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #ff0000 50%, #ffd60a 50%);"></button>
                        <button type="button" aria-label="Hồng Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fecdd3 50%, #fca5a5 50%);"></button>
                        <button type="button" aria-label="Vàng Pastel và Hồng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fef08a 50%, #fb7185 50%);"></button>
                        <button type="button" aria-label="Vàng và Trắng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fde68a 50%, #ffffff 50%);"></button>
                        <button type="button" aria-label="Vàng và Xám"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fde047 50%, #d1d5db 50%);"></button>
                        <button type="button" aria-label="Xám và Đen"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #6b7280 50%, #111827 50%);"></button>
                        <button type="button" aria-label="Đen và Vàng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #111827 50%, #fbbf24 50%);"></button>
                        <button type="button" aria-label="Đỏ và Cam"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #dc2626 50%, #f97316 50%);"></button>
                        <button type="button" aria-label="Nâu và Vàng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #92400e 50%, #fbbf24 50%);"></button>
                        <button type="button" aria-label="Đen và Đồng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #111827 50%, #b45309 50%);"></button>
                        <button type="button" aria-label="Vàng Đậm và Trắng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #eab308 50%, #f9fafb 50%);"></button>
                        <button type="button" aria-label="Xám Đậm và Xanh Rêu"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #4b5563 50%, #2f3e46 50%);"></button>
                        <button type="button" aria-label="Xanh Biển"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #5dade2 50%, #0ea5e9 50%);"></button>
                        <button type="button" aria-label="Xanh Khói"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #94a3b8 50%, #64748b 50%);"></button>
                        <button type="button" aria-label="Xanh Than và Vàng Đồng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #475569 50%, #d9a441 50%);"></button>
                        <button type="button" aria-label="Xanh Đen và Đỏ"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #0f172a 50%, #ef4444 50%);"></button>
                        <button type="button" aria-label="Nâu Đậm và Gỗ Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #b45309 50%, #d4a373 50%);"></button>
                        <button type="button" aria-label="Đỏ Cam và Vàng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fe4a49 50%, #fed766 50%);"></button>
                        <button type="button" aria-label="Vàng Pastel"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fef08a 50%, #facc15 50%);"></button>
                        <button type="button" aria-label="Xanh Rêu và Xanh Ngọc"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #264653 50%, #2a9d8f 50%);"></button>
                        <button type="button" aria-label="Xanh Xám và Bạc"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #4a5568 50%, #94a3b8 50%);"></button>
                        <button type="button" aria-label="Xám và Bạc"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #6b7280 50%, #9ca3af 50%);"></button>
                        <button type="button" aria-label="Xanh Olive và Xanh Nhạt"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #556b2f 50%, #a3b18a 50%);"></button>
                        <button type="button" aria-label="Đỏ Đậm và Xám"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #b91c1c 50%, #9ca3af 50%);"></button>
                        <button type="button" aria-label="Be và Kem"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #d1b89b 50%, #f1dbc9 50%);"></button>
                        <button type="button" aria-label="Cam và Đỏ"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #f97316 50%, #ef4444 50%);"></button>
                        <button type="button" aria-label="Vàng Nhạt và Vàng Đồng"
                            class="w-10 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md"
                            style="background: linear-gradient(135deg, #fef08a 50%, #f2cc8f 50%);"></button>
                    </div>
                </div>

                <!-- Thiết Kế Ve Mũi -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">THIẾT KẾ VE MŨI</h3>
                    <div class="space-y-2">
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Có
                            Ve Mũi <span class="text-gray-500">(1292)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Không
                            Ve Mũi <span class="text-gray-500">(1116)</span></button>
                    </div>
                </div>

                <!-- Chất Liệu Gọng -->
                <div>
                    <h3 class="font-bold text-gray-800 mb-3">CHẤT LIỆU GỌNG</h3>
                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Carbon
                            <span class="text-gray-500">(2)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Hợp
                            Kim <span class="text-gray-500">(1)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Hợp
                            Kim Nhôm <span class="text-gray-500">(8)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Kim
                            Loại <span class="text-gray-500">(532)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Kim
                            Loại Và Nhựa <span class="text-gray-500">(1008)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Mạ
                            Vàng 23kt <span class="text-gray-500">(12)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Nhựa
                            <span class="text-gray-500">(782)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Nhựa
                            Acetate <span class="text-gray-500">(1046)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Nhựa
                            Dẻo Hàn Quốc <span class="text-gray-500">(268)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Titanium
                            <span class="text-gray-500">(561)</span></button>
                    </div>
                </div>

            </div>
        </aside>

        <!-- Mobile Filter Sidebar -->
        <div id="mobile-filter-sidebar"
            class="fixed inset-y-0 left-0 w-80 bg-white shadow-2xl z-[70] transform -translate-x-full transition-transform duration-300 lg:hidden overflow-y-auto">
            <div class="p-4">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6 pb-4 border-b">
                    <h2 class="text-lg font-bold text-gray-800">Bộ Lọc</h2>
                    <div class="flex gap-3">
                        <button class="text-gray-600 hover:text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        <button id="close-mobile-filter" class="text-gray-600 hover:text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tìm Theo Giá -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">TÌM THEO GIÁ</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all">Dưới
                            500k</button>
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all">500k
                            - 1 Triệu</button>
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all">1
                            - 3 Triệu</button>
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all">3
                            - 5 Triệu</button>
                        <button
                            class="px-3 py-2 bg-gray-100 rounded text-xs font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all col-span-2">>
                            5 Triệu</button>
                    </div>
                </div>

                <!-- Chủng Loại -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">CHỦNG LOẠI</h3>
                    <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-0.5 sm:gap-1">
                        <button type="button" aria-label="Đen và Trắng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #000000 50%, #ffffff 50%);"></button>
                        <button type="button" aria-label="Đen và Hồng Pastel"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #000000 50%, #f3d2ff 50%);"></button>
                        <button type="button" aria-label="Xanh Navy và Đen"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #0b1b4f 50%, #1a2c68 50%);"></button>
                        <button type="button" aria-label="Đỏ và Đen"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #d62828 50%, #000000 50%);"></button>
                        <button type="button" aria-label="Vàng và Đen"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #ffc300 50%, #000000 50%);"></button>
                        <button type="button" aria-label="Xám Đậm và Xám Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #1c1c1c 50%, #4a4a4a 50%);"></button>
                        <button type="button" aria-label="Đen và Tím Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #050505 50%, #c084f5 50%);"></button>
                        <button type="button" aria-label="Đen và Hồng Neon"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #111111 50%, #f9a8d4 50%);"></button>
                        <button type="button" aria-label="Đen và Vàng Chanh"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #000000 50%, #ffcf56 50%);"></button>
                        <button type="button" aria-label="Đen và Xanh Lá"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #000000 50%, #7cfc00 50%);"></button>
                        <button type="button" aria-label="Xanh Lam và Đen"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #3b82f6 50%, #020617 50%);"></button>
                        <button type="button" aria-label="Trắng và Xám Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #f7f7f7 50%, #b5b5b5 50%);"></button>
                        <button type="button" aria-label="Xám Bạc và Trắng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #e0e0e0 50%, #fdfdfd 50%);"></button>
                        <button type="button" aria-label="Tím Gradient"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #c084f5 0%, #8b5cf6 100%);"></button>
                        <button type="button" aria-label="Hồng và Vàng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #f472b6 50%, #facc15 50%);"></button>
                        <button type="button" aria-label="Tím và Vàng Đậm"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #7c3aed 50%, #fbbf24 50%);"></button>
                        <button type="button" aria-label="Tím và Đỏ"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #9333ea 50%, #f43f5e 50%);"></button>
                        <button type="button" aria-label="Hồng Phấn"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fbcfe8 50%, #fee2f2 50%);"></button>
                        <button type="button" aria-label="Nâu Gỗ"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #8b5a2b 50%, #d9a066 50%);"></button>
                        <button type="button" aria-label="Cam và Nâu Đậm"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #ffb347 50%, #6b1d1d 50%);"></button>
                        <button type="button" aria-label="Đồng và Be"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #cd7f32 50%, #f7e7ce 50%);"></button>
                        <button type="button" aria-label="Xanh Da Trời Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #38bdf8 50%, #e0f2fe 50%);"></button>
                        <button type="button" aria-label="Xanh Lá Đậm"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #7fbf7f 50%, #4c7a4c 50%);"></button>
                        <button type="button" aria-label="Xanh Ngọc và Xanh Dương Đậm"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #13505b 50%, #0b7285 50%);"></button>
                        <button type="button" aria-label="Xanh Lục và Kem"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #146356 50%, #f7f7f2 50%);"></button>
                        <button type="button" aria-label="Xanh Navy Đậm"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #0f3d6c 50%, #172554 50%);"></button>
                        <button type="button" aria-label="Vàng Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #efd600 50%, #fffbcc 50%);"></button>
                        <button type="button" aria-label="Đen và Vàng Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #000000 50%, #ffd166 50%);"></button>
                        <button type="button" aria-label="Hồng và Vàng Kem"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #ffc0cb 50%, #ffd700 50%);"></button>
                        <button type="button" aria-label="Nâu và Be"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #d2691e 50%, #f4a460 50%);"></button>
                        <button type="button" aria-label="Nâu Đậm và Đồng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #261c15 50%, #b5835a 50%);"></button>
                        <button type="button" aria-label="Bạc Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #cfcfcf 50%, #f4f4f5 50%);"></button>
                        <button type="button" aria-label="Tím và Xanh"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #a78bfa 50%, #38bdf8 50%);"></button>
                        <button type="button" aria-label="Vàng và Đỏ"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fde047 50%, #ef4444 50%);"></button>
                        <button type="button" aria-label="Cam và Đen"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #f97316 50%, #000000 50%);"></button>
                        <button type="button" aria-label="Hồng và Vàng Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fb7185 50%, #fde68a 50%);"></button>
                        <button type="button" aria-label="Đỏ và Navy"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #ef4444 50%, #111827 50%);"></button>
                        <button type="button" aria-label="Đỏ và Vàng Chanh"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #ff0000 50%, #ffd60a 50%);"></button>
                        <button type="button" aria-label="Hồng Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fecdd3 50%, #fca5a5 50%);"></button>
                        <button type="button" aria-label="Vàng Pastel và Hồng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fef08a 50%, #fb7185 50%);"></button>
                        <button type="button" aria-label="Vàng và Trắng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fde68a 50%, #ffffff 50%);"></button>
                        <button type="button" aria-label="Vàng và Xám"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fde047 50%, #d1d5db 50%);"></button>
                        <button type="button" aria-label="Xám và Đen"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #6b7280 50%, #111827 50%);"></button>
                        <button type="button" aria-label="Đen và Vàng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #111827 50%, #fbbf24 50%);"></button>
                        <button type="button" aria-label="Đỏ và Cam"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #dc2626 50%, #f97316 50%);"></button>
                        <button type="button" aria-label="Nâu và Vàng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #92400e 50%, #fbbf24 50%);"></button>
                        <button type="button" aria-label="Đen và Đồng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #111827 50%, #b45309 50%);"></button>
                        <button type="button" aria-label="Vàng Đậm và Trắng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #eab308 50%, #f9fafb 50%);"></button>
                        <button type="button" aria-label="Xám Đậm và Xanh Rêu"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #4b5563 50%, #2f3e46 50%);"></button>
                        <button type="button" aria-label="Xanh Biển"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #5dade2 50%, #0ea5e9 50%);"></button>
                        <button type="button" aria-label="Xanh Khói"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #94a3b8 50%, #64748b 50%);"></button>
                        <button type="button" aria-label="Xanh Than và Vàng Đồng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #475569 50%, #d9a441 50%);"></button>
                        <button type="button" aria-label="Xanh Đen và Đỏ"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #0f172a 50%, #ef4444 50%);"></button>
                        <button type="button" aria-label="Nâu Đậm và Gỗ Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #b45309 50%, #d4a373 50%);"></button>
                        <button type="button" aria-label="Đỏ Cam và Vàng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fe4a49 50%, #fed766 50%);"></button>
                        <button type="button" aria-label="Vàng Pastel"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fef08a 50%, #facc15 50%);"></button>
                        <button type="button" aria-label="Xanh Rêu và Xanh Ngọc"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #264653 50%, #2a9d8f 50%);"></button>
                        <button type="button" aria-label="Xanh Xám và Bạc"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #4a5568 50%, #94a3b8 50%);"></button>
                        <button type="button" aria-label="Xám và Bạc"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #6b7280 50%, #9ca3af 50%);"></button>
                        <button type="button" aria-label="Xanh Olive và Xanh Nhạt"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #556b2f 50%, #a3b18a 50%);"></button>
                        <button type="button" aria-label="Đỏ Đậm và Xám"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #b91c1c 50%, #9ca3af 50%);"></button>
                        <button type="button" aria-label="Be và Kem"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #d1b89b 50%, #f1dbc9 50%);"></button>
                        <button type="button" aria-label="Cam và Đỏ"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #f97316 50%, #ef4444 50%);"></button>
                        <button type="button" aria-label="Vàng Nhạt và Vàng Đồng"
                            class="w-16 h-10 rounded-md border border-gray-200 shadow-sm bg-cover bg-center transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-1 hover:-translate-y-0.5 hover:shadow-md active:scale-95"
                            style="background: linear-gradient(135deg, #fef08a 50%, #f2cc8f 50%);"></button>
                    </div>
                </div>

                <!-- Thiết Kế Ve Mũi -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">THIẾT KẾ VE MŨI</h3>
                    <div class="space-y-2">
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Có
                            Ve Mũi <span class="text-gray-500">(1292)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Không
                            Ve Mũi <span class="text-gray-500">(1116)</span></button>
                    </div>
                </div>

                <!-- Chất Liệu Gọng -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3">CHẤT LIỆU GỌNG</h3>
                    <div class="space-y-2">
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Carbon
                            <span class="text-gray-500">(2)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Hợp
                            Kim <span class="text-gray-500">(1)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Hợp
                            Kim Nhôm <span class="text-gray-500">(8)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Kim
                            Loại <span class="text-gray-500">(532)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Kim
                            Loại Và Nhựa <span class="text-gray-500">(1008)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Mạ
                            Vàng 23kt <span class="text-gray-500">(12)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Nhựa
                            <span class="text-gray-500">(782)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Nhựa
                            Acetate <span class="text-gray-500">(1046)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Nhựa
                            Dẻo Hàn Quốc <span class="text-gray-500">(268)</span></button>
                        <button
                            class="w-full px-3 py-2 bg-gray-100 rounded text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-600 border border-transparent transition-all text-left">Titanium
                            <span class="text-gray-500">(561)</span></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overlay for Mobile Filter -->
        <div id="mobile-filter-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-[65] hidden lg:hidden"></div>

        <!-- Products Grid -->
        <div class="flex-1">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4">

                <!-- Product Card 1 -->
                <div
                    class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="relative overflow-hidden">
                        <span
                            class="discount-badge absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-10">-20%</span>
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522921-Trong_Kinh_Essilor_TransitionsGenS_HD_Graphite_Green_1.jpg"
                            alt="Product"
                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                            alt="Product - Hover"
                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem]">Gọng kính cận
                            chính hãng BENHILL BHTR 6796 C8</h3>
                        <p class="text-xs text-gray-500 mb-2">BENHILL</p>
                        <div class="mb-3 text-right">
                            <p class="text-red-600 font-bold text-lg leading-tight">240.000 VNĐ</p>
                            <p class="text-xs text-gray-400 line-through mt-0.5">300.000 VNĐ</p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                Thêm vào giỏ hàng
                            </button>
                            <button
                                class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div
                    class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="relative overflow-hidden">
                        <span
                            class="discount-badge absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-10">-20%</span>
                        <img src="https://matkinhsaigon.com.vn/img/product/1746521856-Trong_Kinh_Essilor_TransitionsGenS_HD_1.jpg"
                            alt="Product"
                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                            alt="Product - Hover"
                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem]">Gọng kính cận
                            chính hãng BENHILL BHTR 6796 C1</h3>
                        <p class="text-xs text-gray-500 mb-2">BENHILL</p>
                        <div class="mb-3 text-right">
                            <p class="text-red-600 font-bold text-lg leading-tight">240.000 VNĐ</p>
                            <p class="text-xs text-gray-400 line-through mt-0.5">300.000 VNĐ</p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                Thêm vào giỏ hàng
                            </button>
                            <button
                                class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div
                    class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="relative overflow-hidden">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746346945-Trong_Kinh_Essilor_TransitionsGenS_4.jpg"
                            alt="Product"
                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                            alt="Product - Hover"
                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem]">Gọng kính cận
                            chính hãng BENHILL BHTR 6796 C5</h3>
                        <p class="text-xs text-gray-500 mb-2">BENHILL</p>
                        <div class="mb-3 text-right">
                            <p class="text-red-600 font-bold text-lg leading-tight">240.000 VNĐ</p>
                            <p class="text-xs text-gray-400 invisible mt-0.5">&#8203;</p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                Thêm vào giỏ hàng
                            </button>
                            <button
                                class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 4 -->
                <div
                    class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="relative overflow-hidden">
                        <span
                            class="discount-badge absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-10">-15%</span>
                        <img src="https://matkinhsaigon.com.vn/img/product/1741850921-Đổi_Màu_HoGa_161_Fashion_Khói_1.jpg"
                            alt="Product"
                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                            alt="Product - Hover"
                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem]">Gọng kính cận
                            chính hãng BENHILL BHTR 6796 C2</h3>
                        <p class="text-xs text-gray-500 mb-2">BENHILL</p>
                        <div class="mb-3 text-right">
                            <p class="text-red-600 font-bold text-lg leading-tight">255.000 VNĐ</p>
                            <p class="text-xs text-gray-400 line-through mt-0.5">300.000 VNĐ</p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                Thêm vào giỏ hàng
                            </button>
                            <button
                                class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 5 -->
                <div
                    class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="relative overflow-hidden">
                        <span
                            class="discount-badge absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-10">-20%</span>
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522921-Trong_Kinh_Essilor_TransitionsGenS_HD_Graphite_Green_1.jpg"
                            alt="Product"
                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                            alt="Product - Hover"
                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem]">Gọng kính cận
                            chính hãng BENHILL BHTR 6796 C9</h3>
                        <p class="text-xs text-gray-500 mb-2">BENHILL</p>
                        <div class="mb-3 text-right">
                            <p class="text-red-600 font-bold text-lg leading-tight">240.000 VNĐ</p>
                            <p class="text-xs text-gray-400 line-through mt-0.5">300.000 VNĐ</p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                Thêm vào giỏ hàng
                            </button>
                            <button
                                class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 6 -->
                <div
                    class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="relative overflow-hidden">
                        <span
                            class="discount-badge absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-10">-20%</span>
                        <img src="https://matkinhsaigon.com.vn/img/product/1746521856-Trong_Kinh_Essilor_TransitionsGenS_HD_1.jpg"
                            alt="Product"
                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                            alt="Product - Hover"
                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem]">Gọng kính cận
                            chính hãng BENHILL BHTR 6796 C3</h3>
                        <p class="text-xs text-gray-500 mb-2">BENHILL</p>
                        <div class="mb-3 text-right">
                            <p class="text-red-600 font-bold text-lg leading-tight">240.000 VNĐ</p>
                            <p class="text-xs text-gray-400 line-through mt-0.5">300.000 VNĐ</p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                Thêm vào giỏ hàng
                            </button>
                            <button
                                class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 7 -->
                <div
                    class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="relative overflow-hidden">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746346945-Trong_Kinh_Essilor_TransitionsGenS_4.jpg"
                            alt="Product"
                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                            alt="Product - Hover"
                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem]">Gọng kính cận
                            chính hãng BENHILL BHTR 6796 C4</h3>
                        <p class="text-xs text-gray-500 mb-2">BENHILL</p>
                        <div class="mb-3 text-right">
                            <p class="text-red-600 font-bold text-lg leading-tight">240.000 VNĐ</p>
                            <p class="text-xs text-gray-400 invisible mt-0.5">&#8203;</p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                Thêm vào giỏ hàng
                            </button>
                            <button
                                class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 8 -->
                <div
                    class="product-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <div class="relative overflow-hidden">
                        <span
                            class="discount-badge absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-10">-15%</span>
                        <img src="https://matkinhsaigon.com.vn/img/product/1741850921-Đổi_Màu_HoGa_161_Fashion_Khói_1.jpg"
                            alt="Product"
                            class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                        <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                            alt="Product - Hover"
                            class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                    </div>
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 min-h-[2.5rem]">Gọng kính cận
                            chính hãng BENHILL BHTR 6796 C6</h3>
                        <p class="text-xs text-gray-500 mb-2">BENHILL</p>
                        <div class="mb-3 text-right">
                            <p class="text-red-600 font-bold text-lg leading-tight">255.000 VNĐ</p>
                            <p class="text-xs text-gray-400 line-through mt-0.5">300.000 VNĐ</p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                Thêm vào giỏ hàng
                            </button>
                            <button
                                class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <nav class="flex gap-2">
                    <button
                        class="px-4 py-2 bg-gray-100 rounded hover:bg-red-600 hover:text-white transition-colors">«</button>
                    <button class="px-4 py-2 bg-red-600 text-white rounded">1</button>
                    <button
                        class="px-4 py-2 bg-gray-100 rounded hover:bg-red-600 hover:text-white transition-colors">2</button>
                    <button
                        class="px-4 py-2 bg-gray-100 rounded hover:bg-red-600 hover:text-white transition-colors">3</button>
                    <button
                        class="px-4 py-2 bg-gray-100 rounded hover:bg-red-600 hover:text-white transition-colors">...</button>
                    <button
                        class="px-4 py-2 bg-gray-100 rounded hover:bg-red-600 hover:text-white transition-colors">201</button>
                    <button
                        class="px-4 py-2 bg-gray-100 rounded hover:bg-red-600 hover:text-white transition-colors">»</button>
                </nav>
            </div>
        </div>

    </div>

</main>
@endsection
