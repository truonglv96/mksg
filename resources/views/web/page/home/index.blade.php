@extends('web.master')

@section('title', 'Mắt Kính Sài Gòn - Trang chủ')

@section('content')
<!-- Banner Slider Full Màn Hình -->
<div class="swiper-container banner-slider">
    <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide">
            <img src="https://matkinhsaigon.com.vn/img/slider/1760154770-ZEISS_DuraVision_GoldUV_Baner.jpg"
                alt="Tròng Lọc Ánh Sáng Xanh ZEISS">
        </div>

        <!-- Slide 2 -->
        <div class="swiper-slide">
            <img src="https://matkinhsaigon.com.vn/img/slider/1688351415-Baner_Doi_Mau_ZEISS_PhotoFusionX_1.jpg"
                alt="Banner 2">
        </div>

        <!-- Slide 3 -->
        <div class="swiper-slide">
            <img src="https://matkinhsaigon.com.vn/img/slider/1641695991_img.png" alt="Banner 3">
        </div>
    </div>
    <!-- Pagination -->
    <!-- <div class="swiper-pagination"></div> -->
    <!-- Navigation -->
    <!-- <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div> -->
</div>

<main class="container mx-auto px-4 py-8">

    <section class="mb-12">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Danh Mục Nổi Bật</h2>
            <div class="h-1 bg-red-600 w-20"></div>
        </div>

        <!-- Desktop: Grid tĩnh 4 cột -->
        <div class="hidden md:grid md:grid-cols-4 gap-6">
            <div
                class="bg-purple-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300 cursor-pointer">
                <div class="text-5xl mb-3">👓</div>
                <h3 class="font-bold text-lg mb-2">GỌNG KÍNH</h3>
                <a href="#" class="text-sm text-gray-600 hover:text-red-600">Xem Ngay</a>
            </div>
            <div
                class="bg-amber-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300 cursor-pointer">
                <div class="text-5xl mb-3">🔵</div>
                <h3 class="font-bold text-lg mb-2">TRÒNG KÍNH</h3>
                <a href="#" class="text-sm text-gray-600 hover:text-red-600">Xem Ngay</a>
            </div>
            <div
                class="bg-pink-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300 cursor-pointer">
                <div class="text-5xl mb-3">🕶️</div>
                <h3 class="font-bold text-lg mb-2">KÍNH MÁT</h3>
                <a href="#" class="text-sm text-gray-600 hover:text-red-600">Xem Ngay</a>
            </div>
            <div
                class="bg-cyan-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300 cursor-pointer">
                <div class="text-5xl mb-3">🎁</div>
                <h3 class="font-bold text-lg mb-2">KHUYẾN MÃI</h3>
                <a href="#" class="text-sm text-gray-600 hover:text-red-600">Xem Ngay</a>
            </div>
        </div>

        <!-- Mobile: Swiper slider 2 cột -->
        <div class="md:hidden swiper-container categories-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div
                        class="bg-purple-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300">
                        <div class="text-5xl mb-3">👓</div>
                        <h3 class="font-bold text-lg mb-2">GỌNG KÍNH</h3>
                        <a href="#" class="text-sm text-gray-600 hover:text-red-600">Xem Ngay</a>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div
                        class="bg-amber-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300">
                        <div class="text-5xl mb-3">🔵</div>
                        <h3 class="font-bold text-lg mb-2">TRÒNG KÍNH</h3>
                        <a href="#" class="text-sm text-gray-600 hover:text-red-600">Xem Ngay</a>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div
                        class="bg-pink-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300">
                        <div class="text-5xl mb-3">🕶️</div>
                        <h3 class="font-bold text-lg mb-2">KÍNH MÁT</h3>
                        <a href="#" class="text-sm text-gray-600 hover:text-red-600">Xem Ngay</a>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div
                        class="bg-cyan-50 p-6 rounded-2xl text-center shadow hover:shadow-xl transition-all duration-300">
                        <div class="text-5xl mb-3">🎁</div>
                        <h3 class="font-bold text-lg mb-2">KHUYẾN MÃI</h3>
                        <a href="#" class="text-sm text-gray-600 hover:text-red-600">Xem Ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Sản Phẩm Mới Nhất</h2>
                <div class="h-1 bg-red-600 w-20"></div>
            </div>
            <div class="flex items-center space-x-6" id="product-tabs">
                <a data-category="trong-kinh"
                    class="product-tab text-sm font-medium text-gray-800 hover:text-red-600 transition-colors relative pb-1 border-b-2 border-red-600 active">TRÒNG
                    KÍNH</a>
                <a data-category="gong-kinh"
                    class="product-tab text-sm font-medium text-gray-800 hover:text-red-600 transition-colors relative pb-1 border-b-2 border-transparent hover:border-red-600">GỌNG
                    KÍNH</a>
                <a data-category="kinh-mat"
                    class="product-tab text-sm font-medium text-gray-800 hover:text-red-600 transition-colors relative pb-1 border-b-2 border-transparent hover:border-red-600">KÍNH
                    MẮT</a>
                <a data-category="contact-lens"
                    class="product-tab text-sm font-medium text-gray-800 hover:text-red-600 transition-colors relative pb-1 border-b-2 border-transparent hover:border-red-600">CONTACT
                    LENS</a>
            </div>
        </div>

        <!-- Container để chứa các nhóm sản phẩm -->
        <div class="product-container">
            <!-- TRÒNG KÍNH - 8 sản phẩm trong 1 group với Swiper -->
            <div class="product-group" data-category="trong-kinh">
                <div class="swiper-container category-swiper">
                    <div class="swiper-wrapper">
                        <!-- Product 1 -->
                        <div class="swiper-slide group">
                            <div
                                class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                                <div class="relative overflow-hidden">
                                    <img src="https://matkinhsaigon.com.vn/img/product/1746522921-Trong_Kinh_Essilor_TransitionsGenS_HD_Graphite_Green_1.jpg"
                                        alt="Sản phẩm 1"
                                        class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                                    <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                                        alt="Sản phẩm 1 - Hình 2"
                                        class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                                    <!-- Badge giảm giá góc phải trên -->
                                    <span
                                        class="discount-badge absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg">-4%</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-sm mb-1 line-clamp-2 min-h-[2.5rem]">TRÒNG KÍNH
                                        ĐỔI MÀU 1.59</h3>
                                    <div class="mb-3 text-right">
                                        <p class="text-red-600 font-bold text-lg leading-tight">2.990.000 VNĐ</p>
                                        <p class="text-xs text-gray-400 line-through mt-0.5">3.100.000 VNĐ</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                            Thêm vào giỏ hàng
                                        </button>
                                        <button
                                            class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="swiper-slide group">
                            <div
                                class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                                <div class="relative overflow-hidden">
                                    <img src="https://matkinhsaigon.com.vn/img/product/1746521856-Trong_Kinh_Essilor_TransitionsGenS_HD_1.jpg"
                                        alt="Sản phẩm 2"
                                        class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                                    <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                                        alt="Sản phẩm 2 - Hình 2"
                                        class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                                    <!-- Badge giảm giá góc phải trên -->
                                    <span
                                        class="discount-badge absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg">-6%</span>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-sm mb-1 line-clamp-2 min-h-[2.5rem]">TRÒNG KÍNH
                                        ĐỔI MÀU HÓA 1.61</h3>
                                    <div class="mb-3 text-right">
                                        <p class="text-red-600 font-bold text-lg leading-tight">1.890.000 VNĐ</p>
                                        <p class="text-xs text-gray-400 line-through mt-0.5">2.000.000 VNĐ</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                            Thêm vào giỏ hàng
                                        </button>
                                        <button
                                            class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 3 -->
                        <div class="swiper-slide group">
                            <div
                                class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                                <div class="relative overflow-hidden">
                                    <img src="https://matkinhsaigon.com.vn/img/product/1746346945-Trong_Kinh_Essilor_TransitionsGenS_4.jpg"
                                        alt="Sản phẩm 3"
                                        class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                                    <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                                        alt="Sản phẩm 3 - Hình 2"
                                        class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-sm mb-1 line-clamp-2 min-h-[2.5rem]">Tròng Kính
                                        Chống Bể Polycarbonate</h3>
                                    <div class="mb-3 text-right">
                                        <p class="text-red-600 font-bold text-lg leading-tight">990.000 VNĐ</p>
                                        <p class="text-xs text-gray-400 invisible mt-0.5">&#8203;</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                            Thêm vào giỏ hàng
                                        </button>
                                        <button
                                            class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 4 -->
                        <div class="swiper-slide group">
                            <div
                                class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                                <div class="relative overflow-hidden">
                                    <img src="https://matkinhsaigon.com.vn/img/product/1741850921-Đổi_Màu_HoGa_161_Fashion_Khói_1.jpg"
                                        alt="Sản phẩm 4"
                                        class="product-img-main w-full h-48 object-cover transition-opacity duration-300">
                                    <img src="https://matkinhsaigon.com.vn/img/product/1746522073-Trong_Kinh_Essilor_TransitionsGenS_HD_2.jpg"
                                        alt="Sản phẩm 4 - Hình 2"
                                        class="product-img-hover w-full h-48 object-cover transition-opacity duration-300 absolute top-0 left-0 opacity-0 group-hover:opacity-100">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-sm mb-1 line-clamp-2 min-h-[2.5rem]">Tròng Kính
                                        Chiết Suất 1.67 Cao Cấp</h3>
                                    <div class="mb-3 text-right">
                                        <p class="text-red-600 font-bold text-lg leading-tight">4.200.000 VNĐ</p>
                                        <p class="text-xs text-gray-400 invisible mt-0.5">&#8203;</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button
                                            class="flex-1 bg-red-600 text-white py-1.5 px-3 rounded text-xs font-medium hover:bg-red-700 transition-colors duration-200 min-h-[2.5rem] add-to-cart-btn">
                                            Thêm vào giỏ hàng
                                        </button>
                                        <button
                                            class="px-2 py-1.5 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Thêm 4 sản phẩm TRÒNG KÍNH nữa ở đây nếu cần (tổng 8 products) -->
                    </div>
                    <!-- Navigation buttons -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="mb-12">
        <div class="mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Tin Tức Mới Nhất</h2>
                <div class="h-1 bg-red-600 w-20"></div>
            </div>
        </div>

        <div class="swiper-container news-slider">
            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <div
                        class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                        <div class="relative">
                            <img src="https://matkinhsaigon.com.vn/img/news/1760165225-ZEISSDuraVisionGoldUV_HN1.jpg"
                                alt="Giới thiệu về ZEISS DuraVision Gold UV" class="w-full h-48 object-cover">
                        </div>
                        <div class="p-4">
                            <div
                                class="inline-block bg-red-50 text-red-600 text-xs font-medium px-3 py-1 rounded mb-2">
                                11/10/2025
                            </div>
                            <h3 class="font-bold text-base mb-2 line-clamp-2 min-h-[3rem]">Giới thiệu về ZEISS
                                DuraVision Gold UV</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">Định nghĩa lại sự xuất sắc trong công nghệ
                                phủ</p>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div
                        class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                        <div class="relative">
                            <img src="https://matkinhsaigon.com.vn/img/news/1760060544-ZEISS_DuraVision_GoldUV_1.jpg"
                                alt="Trải nghiệm về ZEISS DuraVision Gold UV" class="w-full h-48 object-cover">
                        </div>
                        <div class="p-4">
                            <div
                                class="inline-block bg-red-50 text-red-600 text-xs font-medium px-3 py-1 rounded mb-2">
                                10/10/2025
                            </div>
                            <h3 class="font-bold text-base mb-2 line-clamp-2 min-h-[3rem]">Trải nghiệm về ZEISS
                                DuraVision Gold UV</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">Tiêu chuẩn vàng mới, mở ra trải nghiệm thị
                                lực tuyệt vời</p>
                        </div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div
                        class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                        <div class="relative">
                            <img src="https://matkinhsaigon.com.vn/img/news/1747986442-Mksgvn_Carl_Zeiss_Vision.jpg"
                                alt="Đón tiếp đoàn đại biểu cấp cao Carl Zeiss Vision"
                                class="w-full h-48 object-cover">
                        </div>
                        <div class="p-4">
                            <div
                                class="inline-block bg-red-50 text-red-600 text-xs font-medium px-3 py-1 rounded mb-2">
                                23/05/2025
                            </div>
                            <h3 class="font-bold text-base mb-2 line-clamp-2 min-h-[3rem]">Đón tiếp đoàn đại biểu
                                cấp cao Carl Zeiss Vision</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">Mắt Kính Sài Gòn. Com tự hào là đơn vị đón
                                tiếp đoàn chuyên gia cấp cao Carl Zeiss Vision</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div
                        class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                        <div class="relative">
                            <img src="https://matkinhsaigon.com.vn/img/news/1746418125-transitions_gens_mk.jpg"
                                alt="3 chữ S tạo nên Transitions GEN S" class="w-full h-48 object-cover">
                        </div>
                        <div class="p-4">
                            <div
                                class="inline-block bg-red-50 text-red-600 text-xs font-medium px-3 py-1 rounded mb-2">
                                05/05/2025
                            </div>
                            <h3 class="font-bold text-base mb-2 line-clamp-2 min-h-[3rem]">3 chữ S tạo nên
                                Transitions GEN S</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">Tròng kính đổi màu mang đến trải nghiệm
                                thị giác hoàn hảo. Với 3 chữ S – Speed, Smart, Style, GEN S™</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div
                        class="border rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 bg-white">
                        <div class="relative">
                            <img src="https://matkinhsaigon.com.vn/uploads/news/nhung-con-so-ve-transitions-gen-s.jpg"
                                alt="Những con số nói về Transitions® GEN S" class="w-full h-48 object-cover">
                        </div>
                        <div class="p-4">
                            <div
                                class="inline-block bg-red-50 text-red-600 text-xs font-medium px-3 py-1 rounded mb-2">
                                25/04/2025
                            </div>
                            <h3 class="font-bold text-base mb-2 line-clamp-2 min-h-[3rem]">Những con số nói về
                                Transitions® GEN S</h3>
                            <p class="text-sm text-gray-600 line-clamp-2">Tròng kính Transitions® GEN S™ là dòng
                                tròng kính đổi màu nhanh nhất hiện nay</p>
                        </div>
                    </div>
                </div>


            </div>
            <!-- <div class="swiper-button-next hidden lg:block"></div>
            <div class="swiper-button-prev hidden lg:block"></div> -->
        </div>
    </section>

    <section class="my-4">
        <div class="swiper-container brands-slider">
            <div class="swiper-wrapper items-center">
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1620607559_img.png" alt="Furla"
                        class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1713587349-Logo-jill-stuart.png"
                        alt="Jill Stuart" class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1572940783_img.png" alt="Bvlgari"
                        class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1590996552_img.png" alt="Armani"
                        class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1591080181_img.png" alt="Bolon"
                        class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
                <!-- Duplicate để loop mượt hơn -->
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1620607559_img.png" alt="Furla"
                        class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1713587349-Logo-jill-stuart.png"
                        alt="Jill Stuart" class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1572940783_img.png" alt="Bvlgari"
                        class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1590996552_img.png" alt="Armani"
                        class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://matkinhsaigon.com.vn/img/brand/1591080181_img.png" alt="Bolon"
                        class="h-10 md:h-12  hover:opacity-100 transition">
                </div>
            </div>
        </div>
    </section>

</main>
@endsection
