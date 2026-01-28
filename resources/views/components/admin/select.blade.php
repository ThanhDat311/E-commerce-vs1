@props(['label' => null, 'name', 'required' => false, 'error' => null, 'options' => []])

@php
$selectClass = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors';
if ($error) {
$selectClass = 'w-full px-4 py-2 border border-red-500 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors';
}
@endphp

<div class="mb-4">
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
        <span class="text-red-600">*</span>
        @endif
    </label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => $selectClass]) }}>
        @forelse($options as $value => $text)
        <option value="{{ $value }}">{{ $text }}</option>
        @empty
        {{ $slot }}
        @endforelse
    </select>
    @if($error)
    <p class="text-red-600 text-sm mt-1">{{ $error }}</p>
    @endif
</div>