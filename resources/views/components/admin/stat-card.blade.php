@props(['title' => null, 'subtitle' => null, 'stat' => null, 'trend' => null, 'icon' => null, 'iconBg' => 'blue'])

@php
$iconBgClasses = [
'red' => 'bg-red-50',
'orange' => 'bg-orange-50',
'green' => 'bg-green-50',
'blue' => 'bg-blue-50',
'yellow' => 'bg-yellow-50',
];

$iconColorClasses = [
'red' => 'text-red-600',
'orange' => 'text-orange-500',
'green' => 'text-green-600',
'blue' => 'text-blue-600',
'yellow' => 'text-yellow-500',
];
@endphp

<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            @if($title)
            <p class="text-gray-500 text-sm font-medium">{{ $title }}</p>
            @endif
            @if($stat)
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stat }}</p>
            @endif
            @if($subtitle)
            <p class="text-gray-600 text-sm mt-1">{{ $subtitle }}</p>
            @endif
            @if($trend)
            <p class="text-green-600 text-sm font-medium mt-1">
                <i class="fas fa-arrow-up"></i> {{ $trend }}
            </p>
            @endif
        </div>
        @if($icon)
        <div class="{{ $iconBgClasses[$iconBg] }} rounded-lg p-3 flex-shrink-0">
            <i class="fas fa-{{ $icon }} {{ $iconColorClasses[$iconBg] }} text-2xl"></i>
        </div>
        @endif
    </div>
    {{ $slot }}
</div>