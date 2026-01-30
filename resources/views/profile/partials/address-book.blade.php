<section class="space-y-6">
    <header class="flex justify-between items-center">
        <div>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Address Book') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Manage your shipping and billing addresses.') }}
            </p>
        </div>
        <a href="{{ route('addresses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ __('Manage Addresses') }}
        </a>
    </header>

    @if($addresses->isEmpty())
        <div class="rounded-md bg-gray-50 p-4 border border-gray-200 text-center">
            <p class="text-sm text-gray-500 mb-3">{{ __('You have no saved addresses.') }}</p>
            <a href="{{ route('addresses.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Add a new address</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($addresses as $address)
                <div class="border rounded-lg p-4 relative {{ $address->is_default ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                    @if($address->is_default)
                        <span class="absolute top-2 right-2 inline-flex items-center rounded-full bg-indigo-100 px-2 py-1 text-xs font-medium text-indigo-700">Default</span>
                    @endif
                    
                    <h3 class="font-medium text-gray-900">{{ $address->name ?? $user->name }}</h3>
                    <div class="mt-2 text-sm text-gray-500">
                        <p>{{ $address->phone }}</p>
                        <p>{{ $address->address_line_1 }}</p>
                        @if($address->address_line_2)
                            <p>{{ $address->address_line_2 }}</p>
                        @endif
                        <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
