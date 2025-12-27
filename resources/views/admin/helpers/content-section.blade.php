@props([
    'name' => '',
    'label' => '',
    'fieldName' => null,
    'defaultName' => '',
    'hasTemplate' => false,
    'templateFunction' => null,
    'value' => null,
    'oldValue' => null,
    'nameValue' => null,
    'hiddenValue' => null,
    'textareaId' => null
])

@php
    $fieldName = $fieldName ?? $name;
    $textareaId = $textareaId ?? $name;
    $currentValue = $oldValue ?? old($name, $value ?? '');
    $currentName = $nameValue ?? old($fieldName . '_name', $defaultName);
    $isHidden = $hiddenValue ?? old($fieldName . '_hidden', false);
@endphp

<div class="content-section" name="{{ $name }}">
    <div class="flex items-center justify-between mb-2">
        <label class="block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
        <label class="flex items-center cursor-pointer">
            <input type="checkbox" 
                   name="{{ $fieldName }}_hidden" 
                   value="1"
                   {{ $isHidden ? 'checked' : '' }}
                   class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
            <span class="ml-2 text-xs text-gray-500">Ẩn</span>
        </label>
    </div>
    <div class="flex gap-2 mb-2">
        <input type="text" 
               name="{{ $fieldName }}_name" 
               value="{{ $currentName }}"
               placeholder="Tên section"
               class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
        @if($hasTemplate && $templateFunction)
            <button type="button" 
                    onclick="{{ $templateFunction }}()"
                    class="px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 whitespace-nowrap font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                    style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: white;"
                    onmouseover="this.style.background='linear-gradient(135deg, #0369a1 0%, #075985 100%)'"
                    onmouseout="this.style.background='linear-gradient(135deg, #0284c7 0%, #0369a1 100%)'"
                    title="Thêm nội dung mặc định">
                <i class="fas fa-magic text-sm" style="color: white;"></i>
                <span class="text-sm font-semibold" style="color: white;">Thêm mẫu</span>
            </button>
        @endif
    </div>
    <textarea name="{{ $name }}" 
              id="{{ $textareaId }}"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none min-h-[200px]">{{ $currentValue }}</textarea>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

