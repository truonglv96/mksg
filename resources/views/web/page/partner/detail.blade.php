@extends('web.master')

@section('title', $title ?? config('texts.partner_detail_title'))

@section('content')
<main class="container mx-auto px-4 py-8">
    {{-- Breadcrumb Component --}}
    @include('web.partials.breadcrumb')

    {{-- Partner Header Section --}}
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-0">
                {{-- Content Section (Left - 3 columns) --}}
                <div class="lg:col-span-3 p-6 md:p-8 lg:p-10 bg-gradient-to-br from-gray-50 to-white">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        {{ $partner->name }}
                    </h1>
                    
                    @if($partner->description)
                    <div class="text-gray-700 text-base md:text-lg leading-relaxed mb-6">
                        {!! nl2br(e($partner->description)) !!}
                    </div>
                    @endif
                    
                    @if($partner->content)
                    <div class="text-gray-600 text-base leading-relaxed prose prose-sm max-w-none prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-blue-600">
                        {!! $partner->content !!}
                    </div>
                    @endif
                </div>
                
                {{-- Logo Section (Right - 2 columns) --}}
                @if($partner->logo)
                <div class="lg:col-span-2 p-6 md:p-8 lg:p-10 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                    <div class="w-full max-w-sm">
                        <div class="bg-white rounded-xl p-8 shadow-lg border border-blue-200">
                            <img src="{{ $partner->getImage() }}" 
                                 alt="{{ $partner->name }}" 
                                 class="w-full h-auto object-contain">
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Additional Information Section --}}
    @if(isset($partner->address) || isset($partner->phone) || isset($partner->email) || isset($partner->website))
    <section class="mb-12">
        <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ config('texts.partner_detail_contact_title') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($partner->address)
                <div class="flex items-start gap-3">
                    <div class="text-blue-600 mt-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">{{ config('texts.partner_detail_address') }}</h3>
                        <p class="text-gray-600">{{ $partner->address }}</p>
                    </div>
                </div>
                @endif

                @if($partner->phone)
                <div class="flex items-start gap-3">
                    <div class="text-blue-600 mt-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">{{ config('texts.partner_detail_phone') }}</h3>
                        <a href="tel:{{ $partner->phone }}" class="text-blue-600 hover:text-blue-800 transition-colors">{{ $partner->phone }}</a>
                    </div>
                </div>
                @endif

                @if($partner->email)
                <div class="flex items-start gap-3">
                    <div class="text-blue-600 mt-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">{{ config('texts.partner_detail_email') }}</h3>
                        <a href="mailto:{{ $partner->email }}" class="text-blue-600 hover:text-blue-800 transition-colors">{{ $partner->email }}</a>
                    </div>
                </div>
                @endif

                @if($partner->website)
                <div class="flex items-start gap-3">
                    <div class="text-blue-600 mt-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-1">{{ config('texts.partner_detail_website') }}</h3>
                        <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800 transition-colors break-all">{{ $partner->website }}</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Google Maps Section --}}
    @if(isset($partner->address) && $partner->address)
    <section class="mb-12">
        <div class="bg-white rounded-xl shadow-md p-6 md:p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ config('texts.partner_detail_location_title') }}</h2>
            <div class="w-full h-96 md:h-[500px] rounded-lg overflow-hidden border border-gray-200 shadow-inner bg-gray-100">
                @php
                    $encodedAddress = urlencode($partner->address);
                    // Sử dụng Google Maps Embed với place để hiển thị pin marker chính xác
                    $mapsUrl = "https://www.google.com/maps/embed/v1/place?key=&q={$encodedAddress}&zoom=17&language=vi&maptype=roadmap";
                @endphp
                <iframe 
                    src="https://www.google.com/maps?q={{ $encodedAddress }}&output=embed&hl=vi&z=17&t=m"
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full h-full">
                </iframe>
            </div>
            <div class="mt-4 flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <div class="text-red-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </div>
                    <p class="text-gray-600 text-sm font-medium">{{ $partner->address }}</p>
                </div>
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($partner->address) }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    {{ config('texts.partner_detail_view_map') }}
                </a>
            </div>
        </div>
    </section>
    @endif
</main>
@endsection

