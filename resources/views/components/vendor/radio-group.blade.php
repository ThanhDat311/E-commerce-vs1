@props(['name', 'options', 'selected' => null])

<div class="flex items-center space-x-4">
    @foreach($options as $value => $label)
    <div class="flex items-center">
        <input type="radio" 
               id="{{ $name }}_{{ $value }}" 
               name="{{ $name }}" 
               value="{{ $value }}" 
               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
               {{ $selected == $value ? 'checked' : '' }}
               {{ $attributes }}>
        <label for="{{ $name }}_{{ $value }}" class="ml-2 block text-sm font-medium text-gray-700">
            {{ $label }}
        </label>
    </div>
    @endforeach
</div>
