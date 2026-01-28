@props([
    'label',
])

<div class="pt-4 border-t border-gray-700/50">
    <p class="px-4 text-xs font-bold text-gray-400 uppercase mb-2">{{ $label }}</p>
    {{ $slot }}
</div>

