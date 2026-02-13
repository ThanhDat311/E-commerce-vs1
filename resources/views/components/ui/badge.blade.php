@props([
    'variant' => 'info',
    'dot' => false,
])

@php
    $baseClasses = 'inline-flex items-center gap-1.5 rounded-full text-xs font-semibold leading-none';

    $variants = [
        'success' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
        'pending' => 'bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20',
        'danger' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20',
        'info' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20',
        'neutral' => 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10',
        'warning' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20',
    ];

    $dotColors = [
        'success' => 'bg-green-500',
        'pending' => 'bg-yellow-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-blue-500',
        'neutral' => 'bg-gray-400',
        'warning' => 'bg-amber-500',
    ];

    $classes = $baseClasses . ' px-2.5 py-1 ' . ($variants[$variant] ?? $variants['info']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dotColors[$variant] ?? $dotColors['info'] }}"></span>
    @endif
    {{ $slot }}
</span>
