@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
<nav class="mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
        </li>
        @foreach($breadcrumbs as $breadcrumb)
            <li class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                @if(isset($breadcrumb['url']) && !$loop->last)
                    <a href="{{ $breadcrumb['url'] }}" class="text-gray-500 hover:text-primary-600 transition-colors">
                        {{ $breadcrumb['label'] }}
                    </a>
                @else
                    <span class="text-gray-900 font-medium">{{ $breadcrumb['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif

