@props([
    'isActive' => false,
    'size' => 'md' // sm, md, lg
])

@php
    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-xs',
        'lg' => 'px-4 py-1.5 text-sm',
        default => 'px-3 py-1 text-xs'
    };
    
    if ($isActive) {
        $badgeClass = $sizeClasses . ' font-semibold rounded-full bg-green-500 text-white shadow-lg status-badge active';
    } else {
        $badgeClass = $sizeClasses . ' font-semibold rounded-full bg-gray-500 text-white shadow-lg';
    }
@endphp

@if($isActive)
    <span class="{{ $badgeClass }}">
        <i class="fas fa-check-circle mr-1"></i>Đang bán
    </span>
@else
    <span class="{{ $badgeClass }}">
        <i class="fas fa-eye-slash mr-1"></i>Đã ẩn
    </span>
@endif

