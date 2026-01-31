@props(['name', 'label', 'checked' => false])

<div class="flex items-center justify-between" x-data="{ on: {{ $checked ? 'true' : 'false' }} }">
    <span class="flex-grow flex flex-col" id="{{ $name }}-label">
        <span class="text-sm font-medium text-gray-900">{{ $label }}</span>
        @if(isset($description))
        <span class="text-sm text-gray-500">{{ $description }}</span>
        @endif
    </span>
    <button type="button" 
            class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
            :class="{ 'bg-indigo-600': on, 'bg-gray-200': !on }" 
            role="switch" 
            aria-checked="false" 
            @click="on = !on"
            :aria-labelledby="$name . '-label'">
        <span aria-hidden="true" 
              class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200" 
              :class="{ 'translate-x-5': on, 'translate-x-0': !on }"></span>
    </button>
    <input type="hidden" name="{{ $name }}" :value="on ? '1' : '0'">
</div>
