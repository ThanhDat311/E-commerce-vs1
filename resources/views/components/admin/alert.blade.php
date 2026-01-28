@props(['status' => 'info', 'title' => null, 'dismissible' => false])

@php
$variants = [
'critical' => [
'bg' => 'bg-red-50',
'border' => 'border-red-200',
'icon' => 'fas fa-exclamation-triangle',
'iconColor' => 'text-red-600',
'textColor' => 'text-red-700',
'buttonColor' => 'hover:bg-red-100',
],
'warning' => [
'bg' => 'bg-orange-50',
'border' => 'border-orange-200',
'icon' => 'fas fa-exclamation',
'iconColor' => 'text-orange-600',
'textColor' => 'text-orange-700',
'buttonColor' => 'hover:bg-orange-100',
],
'success' => [
'bg' => 'bg-green-50',
'border' => 'border-green-200',
'icon' => 'fas fa-check-circle',
'iconColor' => 'text-green-600',
'textColor' => 'text-green-700',
'buttonColor' => 'hover:bg-green-100',
],
'info' => [
'bg' => 'bg-blue-50',
'border' => 'border-blue-200',
'icon' => 'fas fa-info-circle',
'iconColor' => 'text-blue-600',
'textColor' => 'text-blue-700',
'buttonColor' => 'hover:bg-blue-100',
],
];

$variant = $variants[$status] ?? $variants['info'];
@endphp

<div class="{{ $variant['bg'] }} rounded-lg p-4 border {{ $variant['border'] }} flex gap-3">
    <i class="{{ $variant['icon'] }} {{ $variant['iconColor'] }} text-xl flex-shrink-0 mt-0.5"></i>
    <div class="flex-1">
        @if($title)
        <h4 class="font-semibold {{ $variant['textColor'] }}">{{ $title }}</h4>
        @endif
        <p class="text-sm {{ $variant['textColor'] }} @if($title) mt-1 @endif">
            {{ $slot }}
        </p>
    </div>
    @if($dismissible)
    <button class="text-gray-400 hover:text-gray-600 flex-shrink-0 {{ $variant['buttonColor'] }} rounded p-1">
        <i class="fas fa-times"></i>
    </button>
    @endif
</div>