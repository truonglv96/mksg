<!-- Mobile Contact Bar - Fixed bottom, chỉ hiển thị trên mobile -->
<div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-sm border-t border-gray-200 shadow-2xl z-50 md:hidden">
    <div class="flex items-center justify-around py-2.5 px-2 max-w-screen-sm mx-auto">
        <!-- Tìm đường -->
        <a href="{{ $settings->google_maps_link ?? '#' }}" target="_blank" class="flex flex-col items-center justify-center flex-1 py-1 active:scale-95 transition-all duration-200 group">
            <div class="w-11 h-11 rounded-full bg-white flex items-center justify-center mb-1.5 shadow-md group-active:shadow-sm transition-all overflow-hidden">
                <img src="{{ asset('img/tmp/smt-icon-map.png') }}" alt="Tìm đường" class="w-full h-full object-cover">
            </div>
            <span class="text-[10px] text-gray-700 font-semibold leading-tight">Tìm đường</span>
        </a>

        <!-- Nhắn SMS -->
        <a href="sms:{{ $settings->phone ?? '' }}" class="flex flex-col items-center justify-center flex-1 py-1 active:scale-95 transition-all duration-200 group">
            <div class="w-11 h-11 rounded-full bg-white flex items-center justify-center mb-1.5 shadow-md group-active:shadow-sm transition-all overflow-hidden">
                <img src="{{ asset('img/tmp/smt-icon-sms.jpeg') }}" alt="Nhắn SMS" class="w-full h-full object-cover">
            </div>
            <span class="text-[10px] text-gray-700 font-semibold leading-tight">Nhắn SMS</span>
        </a>

        <!-- Gọi điện -->
        <a href="tel:{{ $settings->phone ?? '' }}" class="flex flex-col items-center justify-center flex-1 py-1 active:scale-95 transition-all duration-200 group">
            <div class="w-11 h-11 rounded-full bg-white flex items-center justify-center mb-1.5 shadow-lg group-active:shadow-md transition-all overflow-hidden">
                <img src="{{ asset('img/tmp/smt-icon-phone-white.png') }}" alt="Gọi điện" class="w-full h-full object-cover">
            </div>
            <span class="text-[10px] text-gray-700 font-semibold leading-tight">Gọi điện</span>
        </a>

        <!-- Messenger -->
        <a href="{{ $settings->messenger_link ?? '#' }}" target="_blank" class="flex flex-col items-center justify-center flex-1 py-1 active:scale-95 transition-all duration-200 group">
            <div class="w-11 h-11 rounded-full bg-white flex items-center justify-center mb-1.5 shadow-lg group-active:shadow-md transition-all overflow-hidden">
                <img src="{{ asset('img/tmp/smt-icon-messenger.png') }}" alt="Messenger" class="w-full h-full object-cover">
            </div>
            <span class="text-[10px] text-gray-700 font-semibold leading-tight">Messenger</span>
        </a>

        <!-- Chat Zalo -->
        <a href="{{ $settings->zalo_link ?? '#' }}" target="_blank" class="flex flex-col items-center justify-center flex-1 py-1 active:scale-95 transition-all duration-200 group">
            <div class="w-11 h-11 rounded-full bg-white flex items-center justify-center mb-1.5 shadow-lg group-active:shadow-md transition-all overflow-hidden">
                <img src="{{ asset('img/tmp/smt-icon-zalo-circle.png') }}" alt="Chat Zalo" class="w-full h-full object-cover">
            </div>
            <span class="text-[10px] text-gray-700 font-semibold leading-tight">Chat Zalo</span>
        </a>
    </div>
</div>

