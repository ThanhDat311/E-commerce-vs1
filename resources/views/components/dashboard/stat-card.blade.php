@props([
    'title',
    'value',
    'trend' => null,
    'trendUp' => true
])

<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow rounded-lg p-5']) }}>
    <div class="flex items-center">
        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3 icon-container">
            <!-- Icon Slot -->
            {{ $slot }}
        </div>
        <div class="ml-5 w-0 flex-1">
            <dl>
                <dt class="text-sm font-medium text-gray-500 truncate">
                    {{ $title }}
                </dt>
                <dd>
                    <div class="text-lg font-medium text-gray-900">
                        {{ $value }}
                    </div>
                </dd>
            </dl>
        </div>
    </div>
    
    @if($trend !== null)
        <div class="mt-4">
            <div class="{{ $trendUp ? 'text-green-600' : 'text-red-600' }} text-sm font-semibold inline-flex items-baseline">
                @if($trendUp)
                    <svg class="self-center flex-shrink-0 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                @else
                    <svg class="self-center flex-shrink-0 h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l1.293-1.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                @endif
                <span class="ml-1">{{ $trend }}%</span>
                <span class="ml-1 text-gray-400 font-normal">from last month</span>
            </div>
        </div>
    @endif
</div>
