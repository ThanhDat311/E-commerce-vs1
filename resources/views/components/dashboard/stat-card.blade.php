@props([
    'title',
    'value',
    'trend' => null,
    'trendUp' => true,
    'color' => 'blue',
])

@php
    $colorMap = [
        'blue'   => ['bg' => 'bg-blue-600', 'light' => 'bg-blue-50 text-blue-700'],
        'green'  => ['bg' => 'bg-emerald-600', 'light' => 'bg-emerald-50 text-emerald-700'],
        'amber'  => ['bg' => 'bg-amber-500', 'light' => 'bg-amber-50 text-amber-700'],
        'red'    => ['bg' => 'bg-red-600', 'light' => 'bg-red-50 text-red-700'],
        'purple' => ['bg' => 'bg-purple-600', 'light' => 'bg-purple-50 text-purple-700'],
        'cyan'   => ['bg' => 'bg-cyan-600', 'light' => 'bg-cyan-50 text-cyan-700'],
    ];
    $colors = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-start justify-between gap-4">
    <div class="min-w-0 flex-1">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">{{ $title }}</p>
        <p class="text-2xl font-bold text-gray-900 truncate">{{ $value }}</p>
        @if($trend)
            <div class="flex items-center gap-1 mt-2">
                @if($trendUp)
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    <span class="text-xs font-semibold text-emerald-600">{{ $trend }}</span>
                @else
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    <span class="text-xs font-semibold text-red-600">{{ $trend }}</span>
                @endif
            </div>
        @endif
    </div>
    <div class="flex-shrink-0 h-11 w-11 {{ $colors['bg'] }} rounded-xl flex items-center justify-center shadow-sm">
        {{ $slot }}
    </div>
</div>
