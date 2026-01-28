@props(['variant' => 'info', 'animated' => false, 'icon' => null])

@php
$variants = [
'critical' => 'bg-red-600 text-white',
'warning' => 'bg-orange-500 text-white',
'success' => 'bg-green-600 text-white',
'info' => 'bg-blue-600 text-white',
'neutral' => 'bg-gray-100 text-gray-800',
];

$iconMap = [
'critical' => 'exclamation-circle',
'warning' => 'exclamation',
'success' => 'check-circle',
'info' => 'info-circle',
'neutral' => 'circle',
];

$badgeClass = "inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {$variants[$variant]}";
$displayIcon = $icon ?? $iconMap[$variant];
$animationClass = $animated ? 'animate-pulse' : '';
@endphp

<span class="{{ $badgeClass }} {{ $animationClass }}">
    @if($displayIcon)
    <i class="fas fa-{{ $displayIcon }}"></i>
    @endif
    {{ $slot }}
</span>