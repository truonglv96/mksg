@extends('web.master')

@section('title', $title ?? config('texts.brand_index_title'))

@section('content')
<main class="container mx-auto px-4 py-8">
    {{-- Breadcrumb Component --}}
    @include('web.partials.breadcrumb')

    {{-- Hero Section --}}
    <section class="mb-12 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">{{ config('texts.brand_index_hero_title') }}</h1>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
            {{ config('texts.brand_index_hero_desc') }}
        </p>
    </section>

    {{-- Brands Grid --}}
    @if(isset($brands) && $brands->count() > 0)
    <section class="mb-12">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 md:gap-6">
            @foreach($brands as $brand)
            <a href="{{ route('brand.detail', ['alias' => $brand->alias]) }}" 
               class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-red-200">
                <div class="aspect-square flex items-center justify-center p-6 bg-gray-50 group-hover:bg-white transition-colors">
                    @if($brand->logo)
                    <img src="{{ $brand->getLogoBrand() }}" 
                         alt="{{ $brand->name }}" 
                         class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110">
                    @else
                    <div class="text-center">
                        <div class="text-3xl mb-2">👓</div>
                        <p class="text-sm font-semibold text-gray-700">{{ $brand->name }}</p>
                    </div>
                    @endif
                </div>
                @if($brand->name)
                <div class="p-4 text-center border-t border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800 group-hover:text-red-600 transition-colors line-clamp-2">
                        {{ $brand->name }}
                    </h3>
                </div>
                @endif
            </a>
            @endforeach
        </div>
    </section>

    {{-- Pagination --}}
    @if(method_exists($brands, 'hasPages') && $brands->hasPages())
    <div class="mt-8 flex justify-center">
        <nav class="flex gap-2 flex-wrap justify-center">
            @php
                $brands->appends(request()->query());
            @endphp
            @if($brands->onFirstPage())
            <span class="px-4 py-2 bg-gray-100 rounded text-gray-400 cursor-not-allowed">«</span>
            @else
            <a href="{{ $brands->previousPageUrl() }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">«</a>
            @endif

            @php
                $currentPage = $brands->currentPage();
                $lastPage = $brands->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
            @endphp

            @if($startPage > 1)
                <a href="{{ $brands->url(1) }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">1</a>
                @if($startPage > 2)
                <span class="px-4 py-2 bg-gray-100 rounded text-gray-400">...</span>
                @endif
            @endif

            @for($page = $startPage; $page <= $endPage; $page++)
                @if($page == $currentPage)
                <span class="px-4 py-2 text-white rounded" style="background-color: #ed1c24;">{{ $page }}</span>
                @else
                <a href="{{ $brands->url($page) }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">{{ $page }}</a>
                @endif
            @endfor

            @if($endPage < $lastPage)
                @if($endPage < $lastPage - 1)
                <span class="px-4 py-2 bg-gray-100 rounded text-gray-400">...</span>
                @endif
                <a href="{{ $brands->url($lastPage) }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">{{ $lastPage }}</a>
            @endif

            @if($brands->hasMorePages())
            <a href="{{ $brands->nextPageUrl() }}" class="px-4 py-2 bg-gray-100 rounded hover:bg-[#ed1c24] hover:text-white transition-colors">»</a>
            @else
            <span class="px-4 py-2 bg-gray-100 rounded text-gray-400 cursor-not-allowed">»</span>
            @endif
        </nav>
    </div>
    @endif
    @else
    <div class="text-center py-12">
        <p class="text-gray-500 text-lg">{{ config('texts.brand_index_no_brands') }}</p>
    </div>
    @endif
</main>
@endsection

