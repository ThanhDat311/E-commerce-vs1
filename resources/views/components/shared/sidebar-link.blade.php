@props([
    'href',
    'active' => false,
    'icon' => null
])

@php
    $classes = $active
        ? 'group flex items-center px-4 py-3 text-sm font-medium text-white bg-gray-900 rounded-md transition-colors duration-200'
        : 'group flex items-center px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-700 rounded-md transition-colors duration-200';
    
    $iconClasses = $active
        ? 'mr-3 h-5 w-5 text-blue-500'
        : 'mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-300 transition-colors duration-200';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        @if(is_string($icon))
            {{-- Assuming usage of heroicons or similar via a component or svg --}}
            {{-- For now, passing the icon name might rely on a dynamic component or just slot --}}
            {{-- If icon is a string, we might expect it to be a raw SVG or component name. 
                 Given the user said "name component icon", we can try dynamic component if available 
                 or essentially use the slot for maximum flexibility as requested 'Sử dụng <slot /> linh hoạt' 
                 BUT the requirements say 'icon (string)'. 
                 Let's assume the user passes a raw SVG or we use a Blade UI Kit convention. 
                 
                 However, the prompt also says "Use <slot /> for icon or auxiliary content".
                 So I will prioritize the slot for the icon if 'icon' prop isn't strict. 
                 Actually, let's render the passed icon string as a component if it exists.
             --}}
             <x-dynamic-component :component="$icon" class="{{ $iconClasses }}" />
        @endif
    @else
        <div class="{{ $iconClasses }}">
             {{ $slot }}
        </div>
    @endif

    <span>{{ $slot }}</span>
    
    {{-- Allow a secondary slot? usually $slot captures all. 
         Let's separate: If $icon prop is provided, $slot is text. 
         If no $icon prop, maybe the first child is icon? 
         Let's stick to the prompt: "Props: ... icon (string)".
    --}}
</a>
