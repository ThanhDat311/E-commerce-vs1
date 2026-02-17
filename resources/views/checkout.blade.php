<x-base-layout>
    <x-store.navbar />
    <div class="bg-gray-50 min-h-screen pb-12" 
         x-data="{ 
            addresses: {{ json_encode($addresses) }},
            selectedAddressId: {{ $addresses->where('is_default', true)->first()?->id ?? $addresses->first()?->id ?? 'null' }},
            paymentMethod: 'card',
            shippingMethod: 'standard',
            baseSubtotal: {{ $subTotal }},
            baseShipping: {{ $shipping }},
            discount: {{ $discount }},
            
            get shippingCost() {
                return this.shippingMethod === 'express' ? 15.00 : 0.00;
            },

            get total() {
                return (this.baseSubtotal + this.shippingCost - this.discount).toFixed(2);
            },

            formatMoney(amount) {
                return '$' + parseFloat(amount).toFixed(2);
            },

            get selectedAddressData() {
                return this.addresses.find(a => a.id == this.selectedAddressId) || {};
            },

            get firstName() {
                const name = this.selectedAddressData.recipient_name || '';
                const parts = name.trim().split(' ');
                if (parts.length === 1) return name;
                return parts.slice(0, -1).join(' ');
            },

            get lastName() {
                const name = this.selectedAddressData.recipient_name || '';
                const parts = name.trim().split(' ');
                if (parts.length === 1) return name; 
                return parts.slice(-1).join(' ');
            },

            get phone() {
                return this.selectedAddressData.phone_contact || '';
            },

            get fullAddress() {
                const a = this.selectedAddressData;
                if (!a.address_line1) return '';
                // Construct full address string
                const parts = [a.address_line1, a.city, a.state, a.country];
                return parts.filter(Boolean).join(', ');
            }
         }"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li><a href="{{ route('cart.index') }}" class="text-gray-400 hover:text-gray-500">Cart</a></li>
                    <li>
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-900">Checkout</span>
                    </li>
                </ol>
            </nav>

            <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

            <form action="{{ route('checkout.process') }}" method="POST" class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start"
                  x-on:submit="if (!firstName || !lastName || !phone || !fullAddress) { $event.preventDefault(); alert('Please select a shipping address before placing your order.'); }">
                @csrf

                {{-- Validation Error Display --}}
                @if ($errors->any())
                <div class="lg:col-span-12 mb-6">
                    <div class="rounded-md bg-red-50 border border-red-200 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                                <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- Hidden Inputs for CheckoutRequest Validation -->
                <input type="hidden" name="first_name" :value="firstName">
                <input type="hidden" name="last_name" :value="lastName">
                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                <input type="hidden" name="phone" :value="phone">
                <input type="hidden" name="address" :value="fullAddress">
                
                <!-- Left Column -->
                <div class="lg:col-span-7 space-y-8">
                    
                    <!-- Shipping Address -->
                    <section aria-labelledby="shipping-heading" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 id="shipping-heading" class="text-lg font-medium text-gray-900">Shipping Address</h2>
                            <!-- Fixed Link -->
                            <a href="{{ route('addresses.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">+ Add New Address</a>
                        </div>

                        @if($addresses->isEmpty())
                            <div class="text-center py-4 bg-gray-50 rounded-md border-2 border-dashed border-gray-300">
                                <p class="text-sm text-gray-500">You have no saved addresses.</p>
                                <a href="{{ route('addresses.index') }}" class="mt-2 text-sm font-medium text-indigo-600 inline-block">Create one now</a>
                            </div>
                        @else
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @foreach($addresses as $address)
                                <div class="relative rounded-lg border p-4 shadow-sm flex cursor-pointer focus:outline-none"
                                     :class="selectedAddressId == {{ $address->id }} ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-gray-300'"
                                     @click="selectedAddressId = {{ $address->id }}">
                                    <div class="flex-1 flex">
                                        <div class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900 mb-1">{{ $address->recipient_name }} @if($address->is_default) <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full ml-2">Default</span> @endif</span>
                                            <span class="block text-sm text-gray-500">{{ $address->address_line1 }}</span>
                                            <span class="block text-sm text-gray-500">{{ $address->city }}, {{ $address->country }}</span>
                                            <span class="block text-sm font-medium text-gray-900 mt-2">{{ $address->phone_contact }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="h-5 w-5 text-indigo-600" x-show="selectedAddressId == {{ $address->id }}">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <!-- Changed name to address_id to match controller expectation IF controller uses it, but we are using hidden fields now. 
                                         However, keeping it doesn't hurt. But changed x-model to selectedAddressId -->
                                    <input type="radio" name="address_id" value="{{ $address->id }}" class="sr-only" x-model="selectedAddressId">
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <!-- Shipping Method -->
                    <section aria-labelledby="delivery-heading" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                         <h2 id="delivery-heading" class="text-lg font-medium text-gray-900 mb-6">Shipping Method</h2>
                         <div class="space-y-4">
                            <div class="relative bg-white border rounded-lg shadow-sm p-4 flex cursor-pointer focus:outline-none"
                                 :class="shippingMethod === 'standard' ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-gray-300'"
                                 @click="shippingMethod = 'standard'">
                                <div class="flex-1 flex justify-between">
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900">Standard Delivery</span>
                                        <span class="block text-sm text-gray-500">Delivery: 3-5 Business Days</span>
                                    </div>
                                    <span class="text-sm font-medium text-green-600">Free</span>
                                </div>
                                <div class="ml-4 h-5 w-5 text-indigo-600" x-show="shippingMethod === 'standard'">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                </div>
                                <input type="radio" name="shipping_method" value="standard" class="sr-only" x-model="shippingMethod">
                            </div>

                            <div class="relative bg-white border rounded-lg shadow-sm p-4 flex cursor-pointer focus:outline-none"
                                 :class="shippingMethod === 'express' ? 'border-indigo-500 ring-1 ring-indigo-500' : 'border-gray-300'"
                                 @click="shippingMethod = 'express'">
                                <div class="flex-1 flex justify-between">
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900">Express Delivery</span>
                                        <span class="block text-sm text-gray-500">Delivery: 1-2 Business Days</span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">$15.00</span>
                                </div>
                                <div class="ml-4 h-5 w-5 text-indigo-600" x-show="shippingMethod === 'express'">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                </div>
                                <input type="radio" name="shipping_method" value="express" class="sr-only" x-model="shippingMethod">
                            </div>
                         </div>
                    </section>
                    
                    <!-- Payment Method -->
                    <section aria-labelledby="payment-heading" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 id="payment-heading" class="text-lg font-medium text-gray-900 mb-6">Payment Method</h2>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                            <button type="button" @click="paymentMethod = 'card'" class="flex flex-col items-center justify-center border rounded-lg p-3 hover:bg-gray-50 focus:outline-none" :class="paymentMethod === 'card' ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700'">
                                <svg class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                <span class="text-sm font-medium">Card</span>
                            </button>
                            <button type="button" @click="paymentMethod = 'vnpay'" class="flex flex-col items-center justify-center border rounded-lg p-3 hover:bg-gray-50 focus:outline-none" :class="paymentMethod === 'vnpay' ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700'">
                                <svg class="h-6 w-6 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span class="text-sm font-medium">VNPay</span>
                            </button>
                            <button type="button" @click="paymentMethod = 'transfer'" class="flex flex-col items-center justify-center border rounded-lg p-3 hover:bg-gray-50 focus:outline-none" :class="paymentMethod === 'transfer' ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700'">
                                <svg class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" /></svg>
                                <span class="text-xs sm:text-sm font-medium text-center">Transfer</span>
                            </button>
                            <button type="button" @click="paymentMethod = 'cod'" class="flex flex-col items-center justify-center border rounded-lg p-3 hover:bg-gray-50 focus:outline-none" :class="paymentMethod === 'cod' ? 'border-indigo-500 ring-1 ring-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 text-gray-700'">
                                <svg class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span class="text-sm font-medium">COD</span>
                            </button>
                        </div>
                        <input type="hidden" name="payment_method" x-model="paymentMethod">

                        <!-- Card Details -->
                        <div x-show="paymentMethod === 'card'" class="space-y-4">
                            <div>
                                <label for="card-number" class="block text-sm font-medium text-gray-700">Card Number</label>
                                <div class="mt-1 relative">
                                    <input type="text" id="card-number" name="card_number" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="0000 0000 0000 0000">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="expiration-date" class="block text-sm font-medium text-gray-700">Expiration Date</label>
                                    <input type="text" id="expiration-date" name="expiration_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="MM / YY">
                                </div>
                                <div>
                                    <label for="cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                    <div class="mt-1 relative">
                                        <input type="text" id="cvv" name="cvv" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="123">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                             <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="card-name" class="block text-sm font-medium text-gray-700">Cardholder Name</label>
                                <input type="text" id="card-name" name="card_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Name on card">
                            </div>
                            <div class="flex items-center">
                                <input id="save-card" name="save_card" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="save-card" class="ml-2 block text-sm text-gray-900">Save this card for future purchases</label>
                            </div>
                        </div>

                         <div x-show="paymentMethod === 'vnpay'" class="text-center py-6 text-gray-500">
                             You will be redirected to VNPay gateway to complete your purchase.
                         </div>
                         <div x-show="paymentMethod === 'transfer'" class="text-center py-6 text-gray-500">
                             Bank transfer details will be provided after placing order.
                         </div>
                         <div x-show="paymentMethod === 'cod'" class="text-center py-6 text-gray-500">
                             Pay with cash upon delivery.
                         </div>
                    </section>
                </div>

                <!-- Right Column (Order Summary) -->
                <div class="lg:col-span-5 mt-8 lg:mt-0">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-lg font-medium text-gray-900 mb-6">Order Summary</h2>

                        <ul role="list" class="divide-y divide-gray-200 mb-6">
                            @foreach($cartItems as $item)
                            <li class="flex py-4">
                                <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover object-center">
                                </div>
                                <div class="ml-4 flex flex-1 flex-col">
                                    <div>
                                        <div class="flex justify-between text-base font-medium text-gray-900">
                                            <h3><a href="{{ route('shop.show', \App\Models\Product::find($item['id'])->slug) }}">{{ $item['name'] }}</a></h3>
                                            <p class="ml-4">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">Qty {{ $item['quantity'] }}</p>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        
                        <!-- Totals Section -->
                        <dl class="space-y-4 border-t border-gray-200 pt-6">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Subtotal</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ number_format($subTotal, 2) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-gray-600">Shipping</dt>
                                <dd class="text-sm font-medium text-green-600" x-text="shippingCost > 0 ? formatMoney(shippingCost) : 'Free'"></dd>
                            </div>
                             @if($discount > 0)
                            <div class="flex items-center justify-between text-green-600">
                                <dt class="text-sm">Discount</dt>
                                <dd class="text-sm font-medium">-{{ number_format($discount, 2) }}</dd>
                            </div>
                            @endif

                            <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                                <dt class="text-base font-bold text-gray-900">Total</dt>
                                <dd class="text-2xl font-bold text-blue-600" x-text="formatMoney(total)"></dd>
                            </div>
                        </dl>
                        
                        <!-- Submit Button -->
                         <div class="mt-6">
                            <button type="submit" class="w-full bg-blue-500 border border-transparent rounded-lg shadow-sm py-4 px-4 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex justify-center items-center h-14 transition-colors duration-200">
                                Place Order <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            </button>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-center space-x-2 text-sm text-gray-500">
                             <svg class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Secure Checkout</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <x-store.footer />
</x-base-layout>
