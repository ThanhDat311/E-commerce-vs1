@props([
    'title',
    'icon' => null,
    'open' => false,
])

<details @if($open) open @endif class="group">
    <summary class="flex items-center justify-between px-4 py-3 cursor-pointer rounded-lg text-gray-300 hover:text-white hover:bg-gray-700/50 transition-colors">
        <span class="inline-flex items-center gap-3 text-sm font-medium">
            @if($icon)
                <i class="fas fa-{{ $icon }} w-4 text-center"></i>
            @endif
            <span class="truncate">{{ $title }}</span>
        </span>

        <i class="fas fa-chevron-down text-xs transition-transform group-open:rotate-180"></i>
    </summary>

    <div class="mt-2 ml-4 space-y-1">
        {{ $slot }}
    </div>
</details>

