@php
    // Extract variables with defaults for @include usage
    $name = $name ?? '';
    $label = $label ?? '';
    $value = $value ?? null;
    $oldValue = $oldValue ?? null;
    $options = $options ?? [];
    $required = $required ?? false;
    $placeholder = $placeholder ?? 'Chọn...';
    $class = $class ?? '';
    $helpText = $helpText ?? null;
    $attributes = $attributes ?? [];
    
    $currentValue = $oldValue ?? old($name, $value ?? '');
    $selectClass = 'w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-smooth bg-white appearance-none cursor-pointer ' . $class;
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
    
    <div class="relative">
        <select name="{{ $name }}" 
                class="{{ $selectClass }}@error($name) border-red-500 @enderror"
                @foreach($attributes as $key => $val)
                    {{ $key }}="{{ $val }}"
                @endforeach>
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $currentValue == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <i class="fas fa-chevron-down text-gray-400"></i>
        </div>
    </div>
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    
    @if($helpText)
        <p class="mt-1 text-xs text-gray-500">{{ $helpText }}</p>
    @endif
</div>

