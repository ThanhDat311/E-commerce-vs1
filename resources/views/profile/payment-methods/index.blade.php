<x-base-layout>
    <x-store.navbar />

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-gray-500 text-sm">
                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                <span class="mx-2">&rsaquo;</span>
                <a href="{{ route('profile.edit') }}" class="hover:text-gray-900">Account</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900 font-medium">Payment Methods</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-8" x-data="paymentManager()">
                <!-- Sidebar -->
                <div class="w-full md:w-1/4 shrink-0 sticky top-4 self-start">
                    @include('profile.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 md:p-8">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Payment Methods</h2>
                            <button @click="openAddModal()"
                                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add New Card
                            </button>
                        </div>

                        <!-- Payment Cards -->
                        <div class="space-y-6">
                            @forelse($paymentMethods as $card)
                                <div class="border rounded-lg p-6 {{ $card->is_default ? 'border-orange-300 bg-orange-50/30' : 'border-gray-200' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center gap-4">
                                            <!-- Card Brand Icon -->
                                            <div class="w-14 h-10 flex items-center justify-center bg-gray-100 rounded-md">
                                                @if($card->card_brand === 'visa')
                                                    <span class="text-blue-700 font-bold text-sm italic">VISA</span>
                                                @elseif($card->card_brand === 'mastercard')
                                                    <span class="text-red-600 font-bold text-xs">MC</span>
                                                @elseif($card->card_brand === 'amex')
                                                    <span class="text-blue-500 font-bold text-xs">AMEX</span>
                                                @else
                                                    <span class="text-gray-500 font-bold text-xs uppercase">{{ $card->card_brand }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $card->masked_number }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $card->cardholder_name }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    Expires {{ str_pad($card->expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->expiry_year }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($card->is_default)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-700">Default</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center gap-4">
                                        <form action="{{ route('payment-methods.destroy', $card) }}" method="POST" onsubmit="return confirm('Remove this card?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Delete</button>
                                        </form>
                                        @unless($card->is_default)
                                            <form action="{{ route('payment-methods.default', $card) }}" method="POST" class="ml-auto">
                                                @csrf
                                                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Set as Default</button>
                                            </form>
                                        @endunless
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    <p class="text-sm font-medium">No payment methods added yet.</p>
                                    <p class="text-xs mt-1">Add a card to make checkout faster.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Add Card Modal -->
                <div x-show="showModal" x-cloak
                     class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-black/50" @click="closeModal()"></div>
                        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6 z-10">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-lg font-bold text-gray-900">Add New Card</h3>
                                <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <form action="{{ route('payment-methods.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cardholder Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="cardholder_name" required
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                           placeholder="Name on card">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Number <span class="text-red-500">*</span></label>
                                    <input type="text" name="card_number" required maxlength="19"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500"
                                           placeholder="0000 0000 0000 0000">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Month <span class="text-red-500">*</span></label>
                                        <select name="expiry_month" required
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
                                            @for($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Year <span class="text-red-500">*</span></label>
                                        <select name="expiry_year" required
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-orange-500 focus:border-orange-500">
                                            @for($y = date('Y'); $y <= date('Y') + 10; $y++)
                                                <option value="{{ $y }}">{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="hidden" name="is_default" value="0">
                                    <input type="checkbox" name="is_default" value="1" id="is_default_card"
                                           class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                                    <label for="is_default_card" class="text-sm text-gray-700">Set as primary payment method</label>
                                </div>
                                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                    <button type="button" @click="closeModal()"
                                            class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                        Add Card
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-store.footer />

    <script>
        function paymentManager() {
            return {
                showModal: false,
                openAddModal() { this.showModal = true; },
                closeModal() { this.showModal = false; }
            };
        }
    </script>
</x-base-layout>
