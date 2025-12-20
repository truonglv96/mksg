@extends('web.master')

@section('title', $title ?? config('texts.partner_index_title'))

@section('content')
<main class="container mx-auto px-4 py-8">
    {{-- Breadcrumb Component --}}
    @include('web.partials.breadcrumb')

    {{-- Hero Section --}}
    <section class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">{{ config('texts.partner_index_hero_title') }}</h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            {{ config('texts.partner_index_hero_desc') }}
        </p>
    </section>

    {{-- Partners Grid --}}
    @if(isset($partners) && $partners->count() > 0)
    <section class="mb-12">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-6">
            @foreach($partners as $partner)
            <a href="{{ route('partner.detail', ['alias' => $partner->alias]) }}" 
               class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
                <div class="aspect-square flex items-center justify-center p-6 bg-gray-50 group-hover:bg-white transition-colors">
                    @if($partner->logo)
                    <img src="{{ $partner->getImage() }}" 
                         alt="{{ $partner->name }}" 
                         class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                    @else
                    <div class="text-center">
                        <div class="text-3xl mb-2">🤝</div>
                        <p class="text-sm font-semibold text-gray-700">{{ $partner->name }}</p>
                    </div>
                    @endif
                </div>
                @if($partner->name)
                <div class="p-4 text-center border-t border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800 group-hover:text-red-600 transition-colors line-clamp-2">
                        {{ $partner->name }}
                    </h3>
                </div>
                @endif
            </a>
            @endforeach
        </div>
    </section>
    @else
    <div class="text-center py-12">
        <p class="text-gray-500 text-lg">{{ config('texts.partner_index_no_partners') }}</p>
    </div>
    @endif
</main>
@endsection

