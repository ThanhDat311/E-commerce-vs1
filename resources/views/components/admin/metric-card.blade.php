@props(['count' => 0, 'label' => '', 'variant' => 'red', 'icon' => null])

@php
$variantClasses = [
'red' => [
'bg' => 'bg-red-50',
'border' => 'border-red-600',
'text' => 'text-red-700',
'label' => 'text-gray-700',
'icon' => 'text-red-600',
'default_icon' => 'fa-exclamation-circle',
],
'orange' => [
'bg' => 'bg-orange-50',
'border' => 'border-orange-500',
'text' => 'text-orange-600',
'label' => 'text-gray-700',
'icon' => 'text-orange-500',
'default_icon' => 'fa-exclamation',
],
'yellow' => [
'bg' => 'bg-yellow-50',
'border' => 'border-yellow-500',
'text' => 'text-yellow-600',
'label' => 'text-gray-700',
'icon' => 'text-yellow-500',
'default_icon' => 'fa-info-circle',
],
'green' => [
'bg' => 'bg-green-50',
'border' => 'border-green-600',
'text' => 'text-green-700',
'label' => 'text-gray-700',
'icon' => 'text-green-600',
'default_icon' => 'fa-check-circle',
],
'blue' => [
'bg' => 'bg-blue-50',
'border' => 'border-blue-500',
'text' => 'text-blue-700',
'label' => 'text-gray-700',
'icon' => 'text-blue-600',
'default_icon' => 'fa-cubes',
],
];

$style = $variantClasses[$variant] ?? $variantClasses['blue'];
$displayIcon = $icon ?? $style['default_icon'];
@endphp

<div class="{{ $style['bg'] }} rounded-lg shadow-md p-6 border-l-4 {{ $style['border'] }}">
    <div class="flex justify-between items-start mb-2">
        <div>
            <p class="{{ $style['label'] }} text-sm font-semibold uppercase">{{ $label }}</p>
            <p class="text-3xl font-bold {{ $style['text'] }} mt-2">{{ $count }}</p>
        </div>
        <i class="fas {{ $displayIcon }} {{ $style['icon'] }} text-2xl opacity-20"></i>
    </div>
    {{ $slot }}
</div>