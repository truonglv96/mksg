<footer class="bg-gray-100 border-t pt-6 md:pt-8 pb-4 text-sm text-gray-700">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
        <div class="mb-4 md:mb-0">
            <h4 class="font-bold mb-3 text-base md:text-sm" style="color: #ed1c24;">CHÍNH SÁCH & QUY ĐỊNH</h4>
            <ul class="space-y-0.5">
                @if(isset($policies) && $policies->count() > 0)
                @foreach($policies as $index => $policy)
                <li><a href="{{ $policy->link }}" class="font-bold text-gray-900 hover:text-red-600 text-sm block py-0.5">{{ $index }}: {{ $policy->name }}</a></li>
                @endforeach
                @endif
            </ul>
        </div>
        <div class="mb-4 md:mb-0">
            <h4 class="font-bold mb-3 text-base md:text-sm" style="color: #ed1c24;">QUY ĐỊNH PHÁP LÝ</h4>
            <div class="font-bold text-gray-900 text-sm leading-relaxed">
                {!! $settings->legal_regulations !!}
            </div>
        </div>
        <div class="mb-4 md:mb-0">
            <h4 class="font-bold mb-3 text-base md:text-sm" style="color: #ed1c24;">KẾT NỐI VỚI CHÚNG TÔI</h4>
            <div class="flex space-x-3 mb-4 md:mb-4">
                <a href="{{ $settings->facebook }}" class="hover:opacity-80 transition-opacity"><img src="{{ $settings->getIconFB() }}" alt="Facebook" class="h-7 md:h-6 w-auto"></a>
                <a href="{{ $settings->youtube }}" class="hover:opacity-80 transition-opacity"><img src="{{ $settings->getIconYoutube() }}" alt="Youtube" class="h-7 md:h-6 w-auto"></a>
                <a href="{{ $settings->zalo }}" class="hover:opacity-80 transition-opacity"><img src="{{ $settings->getIconZalo() }}" alt="Zalo" class="h-7 md:h-6 w-auto"></a>
                <a href="{{ $settings->email }}" class="hover:opacity-80 transition-opacity"><img src="{{ $settings->getIconEmail() }}" alt="Email" class="h-7 md:h-6 w-auto"></a>
            </div>
            <h4 class="font-bold mb-3 text-base md:text-sm" style="color: #ed1c24;">THỜI GIAN LÀM VIỆC</h4>
            <div class="flex gap-2 md:gap-3 items-start">
                <div class="flex-shrink-0 pt-1">
                    <img src="{{ $settings->getIconTime() }}" alt="Time" class="h-6 w-6 md:h-6">
                </div>
                <div class="font-bold text-gray-900 text-sm leading-relaxed flex-1">
                    {!! $settings->work_time !!}
                </div>
            </div>
        </div>
        <div class="mb-4 md:mb-0">
            <h4 class="font-bold mb-3 text-base md:text-sm" style="color: #ed1c24;">BẢN ĐỒ</h4>
            <div class="w-full overflow-hidden rounded">
                {!! $settings->map !!}
            </div>
        </div>
    </div>
    <div class="mt-6 md:mt-8 pt-4 border-t border-gray-300">
        <div class="bg-red-800 py-3 px-4">
            <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-white text-xs md:text-sm text-center md:text-left">
                    {!! $settings->copyright !!}
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <img src="{{ asset('img/tmp/visa.png') }}" alt="VISA" class="h-6 md:h-8 w-auto">
                    <img src="{{ asset('img/tmp/master_card.png') }}" alt="MasterCard" class="h-6 md:h-8 w-auto">
                    <img src="{{ asset('img/tmp/american_express.png') }}" alt="American Express" class="h-6 md:h-8 w-auto">
                    <img src="{{ asset('img/tmp/paypal.png') }}" alt="PayPal" class="h-6 md:h-8 w-auto">
                </div>
            </div>
        </div>
    </div>
</footer>
