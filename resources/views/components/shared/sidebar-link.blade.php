@props([
    'href' => null,
    'active' => false,
    'hasSubmenu' => false,
    'submenuOpen' => false,
])

@if($hasSubmenu)
    <div x-data="{ open: {{ $submenuOpen ? 'true' : 'false' }} }" class="space-y-1">
        <button @click="open = !open" type="button"
                class="group flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200
                       {{ $active ? 'text-white bg-white/10' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
            <span class="flex items-center gap-3">
                @if(isset($icon))
                    <span class="flex-shrink-0 w-5 h-5 {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-slate-300' }}">
                        {{ $icon }}
                    </span>
                @endif
                <span>{{ $label ?? $slot }}</span>
            </span>
            <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-90' : ''"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <div x-show="open" x-collapse x-cloak class="ml-4 pl-4 border-l border-slate-700 space-y-0.5">
            {{ $submenu }}
        </div>
    </div>
@else
    <a href="{{ $href }}"
       {{ $attributes->merge(['class' =>
           'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200 ' .
           ($active
               ? 'text-white bg-blue-600/90 shadow-sm shadow-blue-500/20'
               : 'text-slate-300 hover:text-white hover:bg-white/5')
       ]) }}>
        @if(isset($icon))
            <span class="flex-shrink-0 w-5 h-5 {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-slate-300' }}">
                {{ $icon }}
            </span>
        @endif
        <span>{{ $slot }}</span>
    </a>
@endif
