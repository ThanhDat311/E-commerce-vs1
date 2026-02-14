<section id="saved-addresses">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Saved Addresses') }}
        </h2>
        <a href="{{ route('addresses.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
            + {{ __('Add New Address') }}
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($addresses as $address)
            <div class="border rounded-lg p-4 relative {{ $address->is_default ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-semibold text-gray-900">{{ $address->recipient_name ?? 'Home' }}</span>
                        @if ($address->is_default)
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                {{ __('Default') }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                         <a href="{{ route('addresses.index') }}" class="p-1 text-gray-400 hover:text-blue-600">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('addresses.destroy', $address->id) }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-gray-400 hover:text-red-600">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-3 text-sm text-gray-600 space-y-1">
                    <p>{{ $address->address_line1 }}</p>
                    <p>{{ $address->city }}, {{ $address->country }}</p>
                    <p class="mt-2 text-gray-900 font-medium">{{ $address->phone_contact }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-6 text-gray-500">
                {{ __('No saved addresses found.') }}
            </div>
        @endforelse
    </div>
</section>
