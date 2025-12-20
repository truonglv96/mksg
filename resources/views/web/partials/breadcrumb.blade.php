@php
    $items = $breadcrumbItems ?? \App\Helpers\BreadcrumbHelper::build(get_defined_vars());
@endphp

<nav class="breadcrumb-nav mb-4 md:mb-6" aria-label="Breadcrumb">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 px-3 md:px-4 py-2.5 md:py-3">
        <ol class="flex items-center flex-wrap gap-0 text-xs md:text-sm text-gray-600 overflow-x-auto scrollbar-hide">
            @foreach($items as $index => $item)
                <li class="flex items-center flex-shrink-0">
                    @if($index > 0)
                        <svg class="w-3.5 h-3.5 md:w-4 md:h-4 mx-2 md:mx-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif
                    
                    @if($item['url'] ?? null)
                        <a href="{{ $item['url'] }}" 
                           class="inline-flex items-center gap-1.5 hover:text-red-600 transition-colors duration-200 whitespace-nowrap {{ $loop->last ? 'pointer-events-none cursor-default text-gray-700 font-medium' : 'text-gray-600' }}">
                            @if($item['icon'] ?? false)
                                <svg class="w-4 h-4 md:w-4.5 md:h-4.5 flex-shrink-0 text-gray-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            @endif
                            <span class="line-clamp-1 max-w-[200px] md:max-w-none capitalize">{{ $item['label'] }}</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap line-clamp-1 max-w-[200px] md:max-w-none text-gray-700 font-medium">
                            @if($item['icon'] ?? false)
                                <svg class="w-4 h-4 md:w-4.5 md:h-4.5 flex-shrink-0 text-gray-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            @endif
                            <span class="line-clamp-1 capitalize">{{ $item['label'] }}</span>
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</nav>

<style>
    /* Hide scrollbar cho breadcrumb trên mobile */
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    /* Smooth scroll cho breadcrumb */
    .breadcrumb-nav ol {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Truncate text dài trên mobile */
    .breadcrumb-nav .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 30px;
    }
    
    /* Line height cho breadcrumb items */
    .breadcrumb-nav a span,
    .breadcrumb-nav span span {
        line-height: 30px;
    }
    
    /* Capitalize text - chữ cái đầu viết hoa, còn lại viết thường */
    .breadcrumb-nav *,
    .breadcrumb-nav ol *,
    .breadcrumb-nav li *,
    .breadcrumb-nav a *,
    .breadcrumb-nav span *,
    .breadcrumb-nav .line-clamp-1,
    .breadcrumb-nav .capitalize {
        text-transform: capitalize !important;
    }
    
    /* Touch-friendly trên mobile */
    @media (max-width: 767px) {
        .breadcrumb-nav a {
            min-height: 32px;
            padding: 2px 4px;
        }
        
        .breadcrumb-nav li {
            min-height: 32px;
        }
    }
    
    /* Hiệu ứng hover tốt hơn */
    .breadcrumb-nav a:hover {
        text-decoration: none;
    }
    
    /* Shadow và border cho breadcrumb container */
    .breadcrumb-nav > div {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    /* Đảm bảo chevron separator không bị wrap */
    .breadcrumb-nav svg[aria-hidden="true"] {
        flex-shrink: 0;
    }
</style>

