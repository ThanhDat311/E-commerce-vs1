@props(['color' => 'blue', 'percentage' => 50, 'label' => null, 'showPercentage' => true])

@php
$percentage = min(100, max(0, $percentage));
$colorClasses = [
'red' => 'bg-red-600',
'orange' => 'bg-orange-500',
'yellow' => 'bg-yellow-500',
'green' => 'bg-green-600',
'blue' => 'bg-blue-600',
];

$colorClass = $colorClasses[$color] ?? 'bg-blue-600';
$widthStyle = "width: {$percentage}%;";
@endphp

<div class="w-full">
    <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
        <div class="h-full rounded-full transition-all duration-300 {{ $colorClass }}" style="{{ $widthStyle }}"></div>
    </div>
    @if($showPercentage || $label)
    <div class="flex items-center justify-between mt-1">
        @if($label)
        <p class="text-xs text-gray-600">{{ $label }}</p>
        @endif
        @if($showPercentage)
        <p class="text-xs text-gray-500">{{ $percentage }}%</p>
        @endif
    </div>
    @endif
</div>