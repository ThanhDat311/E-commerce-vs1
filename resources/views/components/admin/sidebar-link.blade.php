@props([
    'href',
    'active' => false,
    'icon' => null,
    'compact' => false,
])

@php
$base = 'inline-flex w-full items-center gap-3 rounded-lg text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500';
$padding = $compact ? 'px-3 py-2' : 'px-4 py-3';
$activeClasses = 'bg-blue-600/20 text-blue-200 border border-blue-500/30';
$inactiveClasses = 'text-gray-300 hover:bg-gray-700/50 hover:text-white';
$classes = trim("$base $padding " . ($active ? $activeClasses : $inactiveClasses));
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i class="fas fa-{{ $icon }} w-4 text-center"></i>
    @endif
    <span class="truncate">{{ $slot }}</span>
</a>

