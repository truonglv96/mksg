@props([
    'paginator' => null
])

@if($paginator && ($paginator->hasPages() || $paginator->total() > 0))
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in mb-6">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-600">
            Hiển thị <span class="font-semibold text-gray-900">{{ $paginator->firstItem() ?? 0 }}</span> đến <span class="font-semibold text-gray-900">{{ $paginator->lastItem() ?? 0 }}</span> của <span class="font-semibold text-gray-900">{{ number_format($paginator->total()) }}</span> kết quả
        </div>
        <div class="flex items-center gap-2">
            @if($paginator->onFirstPage())
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-smooth" disabled>
                    <i class="fas fa-chevron-left mr-2"></i>Trước
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-smooth">
                    <i class="fas fa-chevron-left mr-2"></i>Trước
                </a>
            @endif
            
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
            @endphp
            
            @if($startPage > 1)
                <a href="{{ $paginator->url(1) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-smooth">1</a>
                @if($startPage > 2)
                    <span class="px-2 text-gray-400">...</span>
                @endif
            @endif
            
            @for($page = $startPage; $page <= $endPage; $page++)
                @if($page == $currentPage)
                    <span class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium shadow-md" style="background-color: #0284c7; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);">{{ $page }}</span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-smooth">{{ $page }}</a>
                @endif
            @endfor
            
            @if($endPage < $lastPage)
                @if($endPage < $lastPage - 1)
                    <span class="px-2 text-gray-400">...</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-smooth">{{ $lastPage }}</a>
            @endif
            
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-smooth">
                    Sau<i class="fas fa-chevron-right ml-2"></i>
                </a>
            @else
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-smooth" disabled>
                    Sau<i class="fas fa-chevron-right ml-2"></i>
                </button>
            @endif
        </div>
    </div>
</div>
@endif

