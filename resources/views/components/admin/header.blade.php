@props(['title' => null, 'icon' => null, 'subtitle' => null, 'background' => 'orange'])

@php
$gradients = [
'orange' => 'from-orange-600 to-red-600',
'blue' => 'from-blue-600 to-blue-700',
'green' => 'from-green-600 to-teal-600',
'red' => 'from-red-600 to-pink-600',
'purple' => 'from-purple-600 to-blue-600',
];

$gradient = $gradients[$background] ?? $gradients['orange'];
@endphp

<div class="bg-gradient-to-r {{ $gradient }} text-white px-6 py-8 shadow-lg">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($icon)
                <i class="fas fa-{{ $icon }} text-3xl text-yellow-300"></i>
                @endif
                <div>
                    @if($title)
                    <h1 class="text-4xl font-bold">{{ $title }}</h1>
                    @endif
                    @if($subtitle)
                    <p class="text-orange-100 mt-1">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>