@extends('web.master')

@section('title', $title ?? 'Hệ Thống Cửa Hàng - Mắt Kính Sài Gòn')

@section('content')
<main class="container mx-auto px-4 py-8">
    {{-- Breadcrumb Component --}}
    @include('web.partials.breadcrumb')

    {{-- Hero Section --}}
    <section class="mb-12 text-center">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Hệ Thống Cửa Hàng</h1>
            <p class="text-lg text-gray-600">
                Tìm cửa hàng Mắt Kính Sài Gòn gần bạn nhất. Chúng tôi luôn sẵn sàng phục vụ bạn với chất lượng tốt nhất.
            </p>
        </div>
    </section>

    {{-- Contacts Grid --}}
    @if(isset($contacts) && $contacts->count() > 0)
    <section class="mb-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($contacts as $contact)
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
                {{-- Card Header with Icon --}}
                <div class="bg-gradient-to-br from-red-500 to-red-600 p-6" style="color: white;">
                    <div class="flex items-center gap-4" style="color: white;">
                        <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-xl font-bold mb-1 line-clamp-2" style="color: white !important;">{{ $contact->name }}</h3>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-6 space-y-4">
                    {{-- Phone Number --}}
                    @if($contact->phone)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-500 mb-1">Điện thoại</p>
                            @php
                                $formattedPhone = $contact->formatted_phone ?: $contact->phone;
                                // Đảm bảo có số 0 đầu nếu chưa có
                                if (!empty($formattedPhone) && substr($formattedPhone, 0, 1) !== '0') {
                                    $formattedPhone = '0' . preg_replace('/[^0-9]/', '', $formattedPhone);
                                }
                            @endphp
                            <a href="tel:{{ $formattedPhone }}" class="text-base font-semibold text-gray-900 hover:text-red-600 transition-colors break-all">
                                {{ $formattedPhone }}
                            </a>
                        </div>
                    </div>
                    @endif

                    {{-- Address --}}
                    @if($contact->address)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-500 mb-1">Địa chỉ</p>
                            <p class="text-base text-gray-900 leading-relaxed">{{ $contact->address }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Working Hours --}}
                    @if($contact->strart_time || $contact->end_time)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-500 mb-1">Giờ làm việc</p>
                            <p class="text-base font-medium text-gray-900">
                                @if($contact->strart_time && $contact->end_time)
                                    {{ $contact->strart_time }} - {{ $contact->end_time }}
                                @elseif($contact->strart_time)
                                    Từ {{ $contact->strart_time }}
                                @elseif($contact->end_time)
                                    Đến {{ $contact->end_time }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Card Footer with Call Button --}}
                @if($contact->phone)
                <div class="px-6 pb-6">
                    @php
                        $formattedPhoneForButton = $contact->formatted_phone ?: $contact->phone;
                        // Đảm bảo có số 0 đầu nếu chưa có
                        if (!empty($formattedPhoneForButton) && substr($formattedPhoneForButton, 0, 1) !== '0') {
                            $formattedPhoneForButton = '0' . preg_replace('/[^0-9]/', '', $formattedPhoneForButton);
                        }
                    @endphp
                    <a href="tel:{{ $formattedPhoneForButton }}" 
                       class="block w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-xl text-center transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Gọi ngay
                        </span>
                    </a>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @else
    <div class="text-center py-16 bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="max-w-md mx-auto">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Chưa có thông tin cửa hàng</h3>
            <p class="text-gray-600">Hiện tại chưa có cửa hàng nào được hiển thị. Vui lòng quay lại sau.</p>
        </div>
    </div>
    @endif

    {{-- Additional Information Section --}}
    <section class="mt-16 bg-gradient-to-br from-gray-50 to-white rounded-2xl shadow-lg border border-gray-100 p-8 md:p-12">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Liên hệ với chúng tôi</h2>
            <p class="text-lg text-gray-600 mb-8">
                Bạn có câu hỏi hoặc cần tư vấn? Đội ngũ của chúng tôi luôn sẵn sàng hỗ trợ bạn.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Hotline</h3>
                    <p class="text-gray-600 text-sm">Gọi ngay để được tư vấn</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Địa chỉ</h3>
                    <p class="text-gray-600 text-sm">Đến trực tiếp cửa hàng</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Giờ làm việc</h3>
                    <p class="text-gray-600 text-sm">Phục vụ tất cả các ngày</p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
