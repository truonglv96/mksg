@props(['title' => null, 'action' => null, 'actionLabel' => null, 'actionRoute' => null])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 {{ $attributes->get('class') }}">
    @if($title || $action)
    <div class="flex items-center justify-between mb-4">
        @if($title)
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        @endif
        @if($action || $actionRoute)
            @if($actionRoute)
                <a href="{{ $actionRoute }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                    {{ $actionLabel ?? 'Xem thêm' }}
                    <i class="fas fa-arrow-right ml-1"></i>
                </a>
            @else
                {{ $action }}
            @endif
        @endif
    </div>
    @endif
    
    {{ $slot }}
</div>

