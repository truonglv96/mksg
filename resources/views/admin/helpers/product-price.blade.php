@props([
    'priceSale' => null,
    'priceOriginal' => null,
    'showDiscount' => true,
    'size' => 'lg' // sm, md, lg, xl
])

@php
    $priceSale = $priceSale ?? 0;
    $priceOriginal = $priceOriginal ?? 0;
    $discount = 0;
    
    if ($priceOriginal > 0 && $priceSale < $priceOriginal && $priceSale > 0) {
        $discount = round((($priceOriginal - $priceSale) / $priceOriginal) * 100);
    }
    
    // Size classes
    $priceClass = match($size) {
        'sm' => 'text-sm',
        'md' => 'text-lg',
        'lg' => 'text-2xl',
        'xl' => 'text-3xl',
        default => 'text-2xl'
    };
    
    $originalPriceClass = match($size) {
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-xs',
        'xl' => 'text-sm',
        default => 'text-xs'
    };
@endphp

<div>
    @if($priceSale > 0)
        <p class="{{ $priceClass }} font-bold text-primary-600">₫{{ number_format($priceSale, 0, ',', '.') }}</p>
    @endif
    
    @if($priceOriginal > 0 && $priceSale < $priceOriginal && $priceSale > 0)
        <p class="{{ $originalPriceClass }} text-gray-400 line-through">₫{{ number_format($priceOriginal, 0, ',', '.') }}</p>
        @if($showDiscount && $discount > 0)
            <div class="text-xs text-green-600 font-medium mt-1">
                <i class="fas fa-percent mr-1"></i>Giảm {{ $discount }}%
            </div>
        @endif
    @elseif($priceOriginal > 0 && $priceSale == 0)
        <p class="{{ $priceClass }} font-bold text-primary-600">₫{{ number_format($priceOriginal, 0, ',', '.') }}</p>
    @elseif($priceSale == 0 && $priceOriginal == 0)
        <p class="text-sm text-gray-400">Chưa có giá</p>
    @endif
</div>

