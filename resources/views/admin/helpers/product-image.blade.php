@props([
    'product' => null,
    'size' => 'medium', // small, medium, large
    'class' => '',
    'lazy' => true
])

@php
    $imageUrl = 'https://via.placeholder.com/300x300?text=Product';
    
    if ($product) {
        $firstImage = $product->images->first();
        if ($firstImage && !empty($firstImage->image)) {
            $imageUrl = asset('img/product/' . $firstImage->image);
        } elseif (!empty($product->url_img)) {
            $imageUrl = asset('img/product/' . $product->url_img);
        }
    }
    
    // Size classes
    $sizeClasses = match($size) {
        'small' => 'w-full h-full',
        'medium' => 'w-full h-48',
        'large' => 'w-full h-64',
        default => 'w-full h-48'
    };
    
    $placeholder = match($size) {
        'small' => 'https://via.placeholder.com/56',
        'medium' => 'https://via.placeholder.com/300x300?text=Product',
        'large' => 'https://via.placeholder.com/400x400?text=Product',
        default => 'https://via.placeholder.com/300x300?text=Product'
    };
@endphp

<img src="{{ $imageUrl }}" 
     alt="{{ $product->name ?? 'Product' }}" 
     class="{{ $sizeClasses }} object-cover {{ $class }}" 
     @if($lazy) loading="lazy" decoding="async" @endif
     onerror="this.src='{{ $placeholder }}'"
     style="aspect-ratio: 1 / 1; object-position: center;">

