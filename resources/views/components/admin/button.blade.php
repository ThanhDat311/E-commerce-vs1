@props(['variant' => 'primary', 'size' => 'md', 'disabled' => false, 'loading' => false, 'icon' => null, 'href' => null])

@php
$baseClasses = 'font-semibold transition-colors duration-200 inline-flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2';

$sizes = [
'sm' => 'px-3 py-1 text-sm',
'md' => 'px-4 py-2 text-base',
'lg' => 'px-6 py-3 text-lg',
];

$variants = [
'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed',
'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed',
'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed',
'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed',
'warning' => 'bg-orange-500 hover:bg-orange-600 text-white focus:ring-orange-500 disabled:opacity-50 disabled:cursor-not-allowed',
'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700 border border-gray-300 focus:ring-gray-500 disabled:opacity-50 disabled:cursor-not-allowed',
];

$buttonClass = "$baseClasses {$sizes[$size]} {$variants[$variant]}";
$disabledState = $disabled || $loading;
@endphp

@if($href)
<a href="{{ $href }}" @if($disabledState) onclick="return false;" @endif class="{{ $buttonClass }} no-underline">
    @if($loading)
    <i class="fas fa-spinner fa-spin"></i>
    @elseif($icon)
    <i class="fas fa-{{ $icon }}"></i>
    @endif
    {{ $slot }}
</a>
@else
<button
    type="button"
    @disabled($disabledState)
    {{ $attributes->merge(['class' => $buttonClass]) }}>
    @if($loading)
    <i class="fas fa-spinner fa-spin"></i>
    @elseif($icon)
    <i class="fas fa-{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>
@endif