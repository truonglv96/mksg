@props([
    'product' => null,
])

@php
    $codeSp = $product && $product->code_sp ? $product->code_sp : null;
@endphp

<div class="flex items-center gap-2 flex-wrap">
    @if($codeSp)
        <span class="font-medium text-gray-700">Mã: {{ $codeSp }}</span>
    @endif
</div>

