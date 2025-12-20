@extends('web.master')

@section('title', $title ?? config('texts.page_title_default'))

@section('content')
<main class="container mx-auto px-4 py-8">
    {{-- Breadcrumb Component --}}
    @include('web.partials.breadcrumb')

    {{-- Page Header Section --}}
    <section class="mb-10">
        <div class="relative overflow-hidden bg-gradient-to-br from-red-50 via-white to-blue-50 rounded-3xl shadow-xl border border-red-100">
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
            <div class="relative p-8 md:p-12 lg:p-16">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-sm rounded-full text-sm font-semibold text-red-600 mb-6 shadow-sm border border-red-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>{{ config('texts.page_badge') }}</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        {{ $page->title ?? $page->name }}
                    </h1>
                    @if($page->description)
                    <p class="text-gray-600 text-lg md:text-xl leading-relaxed max-w-3xl mx-auto">
                        {{ $page->description }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Page Content Section --}}
    @if($page->content || $page->image)
    <section class="mb-12">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            @if($page->image)
            <div class="relative w-full aspect-video overflow-hidden bg-gray-100">
                <img src="{{ $page->getImage() }}" 
                     alt="{{ $page->title ?? $page->name }}" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            </div>
            @endif
            
            @if($page->content)
            <div class="p-8 md:p-12 lg:p-16">
                <div class="max-w-4xl mx-auto prose prose-lg md:prose-xl max-w-none 
                    prose-headings:text-gray-900 prose-headings:font-bold prose-headings:leading-tight
                    prose-h1:text-4xl prose-h2:text-3xl prose-h3:text-2xl
                    prose-p:text-gray-700 prose-p:leading-relaxed prose-p:text-base md:prose-p:text-lg
                    prose-a:text-red-600 prose-a:no-underline hover:prose-a:underline prose-a:font-medium
                    prose-strong:text-gray-900 prose-strong:font-bold
                    prose-ul:text-gray-700 prose-ol:text-gray-700
                    prose-li:leading-relaxed prose-li:my-3
                    prose-blockquote:border-l-4 prose-blockquote:border-red-600 prose-blockquote:pl-6 prose-blockquote:italic prose-blockquote:text-gray-600
                    prose-code:text-red-600 prose-code:bg-red-50 prose-code:px-2 prose-code:py-1 prose-code:rounded
                    prose-pre:bg-gray-900 prose-pre:text-gray-100
                    prose-img:rounded-xl prose-img:shadow-lg prose-img:my-8
                    prose-table:w-full prose-table:border-collapse
                    prose-th:bg-gray-100 prose-th:p-4 prose-th:text-left prose-th:font-bold prose-th:text-gray-900
                    prose-td:p-4 prose-td:border-t prose-td:border-gray-200">
                    {!! $page->content !!}
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Back Button Section --}}
    <section class="mb-8">
        <div class="flex justify-center">
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center gap-3 px-6 py-3 bg-gray-100 hover:bg-red-600 text-gray-700 hover:text-white rounded-xl font-semibold transition-all duration-300 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span>{{ config('texts.page_back') }}</span>
            </a>
        </div>
    </section>
</main>

<style>
    .bg-grid-pattern {
        background-image: 
            linear-gradient(to right, rgba(0,0,0,0.05) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(0,0,0,0.05) 1px, transparent 1px);
        background-size: 20px 20px;
    }
</style>
@endsection

