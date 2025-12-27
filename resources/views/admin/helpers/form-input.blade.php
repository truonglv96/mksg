@php
    // Extract variables with defaults for @include usage
    $name = $name ?? '';
    $label = $label ?? '';
    $type = $type ?? 'text';
    $value = $value ?? null;
    $oldValue = $oldValue ?? null;
    $placeholder = $placeholder ?? '';
    $required = $required ?? false;
    $min = $min ?? null;
    $max = $max ?? null;
    $step = $step ?? null;
    $class = $class ?? '';
    $helpText = $helpText ?? null;
    $attributes = $attributes ?? [];
    
    $currentValue = $oldValue ?? old($name, $value ?? '');
    $inputClass = 'w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth ' . $class;
@endphp

<div>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           value="{{ $currentValue }}"
           placeholder="{{ $placeholder }}"
           @if($min !== null) min="{{ $min }}" @endif
           @if($max !== null) max="{{ $max }}" @endif
           @if($step !== null) step="{{ $step }}" @endif
           class="{{ $inputClass }}@error($name) border-red-500 @enderror"
           @foreach($attributes as $key => $val)
               {{ $key }}="{{ $val }}"
           @endforeach>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    
    @if($helpText)
        <p class="mt-1 text-xs text-gray-500">{{ $helpText }}</p>
    @endif
</div>

