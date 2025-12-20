<!-- Mobile Contact Bar - Fixed bottom, chỉ hiển thị trên mobile -->
<div class="fixed bottom-0 left-0 right-0 z-50 md:hidden" style="padding-bottom: env(safe-area-inset-bottom);">
    <!-- Glassmorphism Background - Tráng gương mờ -->
    <div class="absolute inset-0 bg-white/40 backdrop-blur-2xl border-t border-white/30 shadow-[0_-8px_32px_rgba(0,0,0,0.15)]"></div>
    
    <!-- Content -->
    <div class="relative flex items-center justify-around py-2.5 px-2 max-w-screen-sm mx-auto gap-0.5">
        @php
            // Lấy số điện thoại từ các trường có thể có
            $phone = $settings->phone ?? $settings->hotline ?? $settings->phone_number ?? '';
            $phoneClean = preg_replace('/[^0-9+]/', '', $phone);
            
            // Lấy link Google Maps
            $googleMapsLink = $settings->google_maps_link ?? $settings->google_map ?? $settings->map_link ?? '';
            
            // Lấy link Messenger (có thể là facebook messenger)
            $messengerLink = $settings->messenger_link ?? $settings->messenger ?? $settings->fb_messenger ?? $settings->facebook ?? '';
            
            // Lấy link Zalo
            $zaloLink = $settings->zalo_link ?? $settings->zalo ?? '';
        @endphp

        <!-- Tìm đường -->
        <a href="{{ $googleMapsLink ?: '#' }}" 
           data-action="maps"
           data-url="{{ $googleMapsLink }}"
           class="mobile-contact-btn flex flex-col items-center justify-center flex-1 py-1.5 px-1 rounded-lg active:scale-95 active:bg-white/30 transition-all duration-200 group min-w-0 {{ !$googleMapsLink ? 'opacity-50 cursor-not-allowed' : '' }}">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-1 shadow-md group-active:shadow-sm group-active:scale-95 transition-all">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <span class="text-[9px] text-gray-700 font-medium leading-tight text-center whitespace-nowrap overflow-hidden text-ellipsis max-w-full px-0.5">{{ config('texts.mobile_contact_find_way') }}</span>
        </a>

        <!-- Nhắn SMS -->
        <a href="{{ $phoneClean ? 'sms:' . $phoneClean : '#' }}" 
           data-action="sms"
           data-phone="{{ $phoneClean }}"
           class="mobile-contact-btn flex flex-col items-center justify-center flex-1 py-1.5 px-1 rounded-lg active:scale-95 active:bg-white/30 transition-all duration-200 group min-w-0 {{ !$phoneClean ? 'opacity-50 cursor-not-allowed' : '' }}">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center mb-1 shadow-md group-active:shadow-sm group-active:scale-95 transition-all">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <span class="text-[9px] text-gray-700 font-medium leading-tight text-center whitespace-nowrap overflow-hidden text-ellipsis max-w-full px-0.5">{{ config('texts.mobile_contact_sms') }}</span>
        </a>

        <!-- Gọi điện -->
        <a href="{{ $phoneClean ? 'tel:' . $phoneClean : '#' }}" 
           data-action="call"
           data-phone="{{ $phoneClean }}"
           class="mobile-contact-btn flex flex-col items-center justify-center flex-1 py-1.5 px-1 rounded-lg active:scale-95 active:bg-white/30 transition-all duration-200 group min-w-0 {{ !$phoneClean ? 'opacity-50 cursor-not-allowed' : '' }}">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center mb-1 shadow-lg group-active:shadow-md group-active:scale-95 transition-all ring-2 ring-red-100/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
            </div>
            <span class="text-[9px] text-gray-700 font-semibold leading-tight text-center whitespace-nowrap overflow-hidden text-ellipsis max-w-full px-0.5">{{ config('texts.mobile_contact_call') }}</span>
        </a>

        <!-- Messenger -->
        <a href="{{ $messengerLink ?: '#' }}" 
           data-action="messenger"
           data-url="{{ $messengerLink }}"
           target="_blank"
           class="mobile-contact-btn flex flex-col items-center justify-center flex-1 py-1.5 px-1 rounded-lg active:scale-95 active:bg-white/30 transition-all duration-200 group min-w-0 {{ !$messengerLink ? 'opacity-50 cursor-not-allowed' : '' }}">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center mb-1 shadow-md group-active:shadow-sm group-active:scale-95 transition-all">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <span class="text-[9px] text-gray-700 font-medium leading-tight text-center whitespace-nowrap overflow-hidden text-ellipsis max-w-full px-0.5">{{ config('texts.mobile_contact_messenger') }}</span>
        </a>

        <!-- Chat Zalo -->
        <a href="{{ $zaloLink ?: '#' }}" 
           data-action="zalo"
           data-url="{{ $zaloLink }}"
           target="_blank"
           class="mobile-contact-btn flex flex-col items-center justify-center flex-1 py-1.5 px-1 rounded-lg active:scale-95 active:bg-white/30 transition-all duration-200 group min-w-0 {{ !$zaloLink ? 'opacity-50 cursor-not-allowed' : '' }}">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-300 to-blue-400 flex items-center justify-center mb-1 shadow-md group-active:shadow-sm group-active:scale-95 transition-all">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
            <span class="text-[9px] text-gray-700 font-medium leading-tight text-center whitespace-nowrap overflow-hidden text-ellipsis max-w-full px-0.5">{{ config('texts.mobile_contact_zalo') }}</span>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactButtons = document.querySelectorAll('.mobile-contact-btn');
    
    contactButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const action = this.getAttribute('data-action');
            const url = this.getAttribute('data-url');
            const phone = this.getAttribute('data-phone');
            const href = this.getAttribute('href');
            
            // Kiểm tra nếu không có dữ liệu thì ngăn hành động
            if (action === 'maps' && (!url || url === '#')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            if ((action === 'sms' || action === 'call') && (!phone || phone === '')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            if ((action === 'messenger' || action === 'zalo') && (!url || url === '#')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Nếu có href hợp lệ và không phải '#', cho phép link hoạt động bình thường
            if (href && href !== '#') {
                // Xử lý đặc biệt cho Google Maps trên mobile
                if (action === 'maps' && url) {
                    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                    if (isMobile) {
                        // Nếu là link Google Maps, chuyển sang format app
                        if (url.includes('google.com/maps') || url.includes('maps.google.com')) {
                            e.preventDefault();
                            // Thử mở app trước, nếu không được thì mở web
                            const appUrl = url.replace(/\/maps\//, '/maps/place/').replace(/\/@/, '/place/');
                            window.location.href = appUrl;
                            return false;
                        }
                    }
                }
                
                // SMS và Call đã có href đúng format, không cần xử lý thêm
                // Messenger và Zalo sẽ mở trong tab mới (đã có target="_blank")
            }
        });
    });
});
</script>

