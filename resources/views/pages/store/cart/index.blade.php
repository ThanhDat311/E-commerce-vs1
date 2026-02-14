<x-base-layout>
    <x-store.navbar />
    <div class="bg-gray-50 min-h-screen pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

            @if(empty($cartItems) || count($cartItems) === 0)
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                    <p class="mt-1 text-sm text-gray-500">Start shopping to fill it up!</p>
                    <div class="mt-6">
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            @else
                <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start" 
                     x-data="{ 
                        cartItems: {{ json_encode($cartItems) }},
                        subTotal: {{ $subTotal }},
                        discount: {{ $discount }},
                        shipping: {{ $shipping }},
                        couponCode: '',
                        
                        get total() {
                            return this.subTotal + this.shipping - this.discount;
                        },

                        formatMoney(amount) {
                            return '$' + parseFloat(amount).toFixed(2);
                        },

                        updateQuantity(id, newQty) {
                            if (newQty < 1) return;
                            
                            // Finds the item and update optimized first for UI responsiveness
                            const itemIndex = this.cartItems.findIndex(i => i.id === id);
                            if (itemIndex > -1) {
                                this.cartItems[itemIndex].quantity = newQty;
                            }

                            // Perform AJAX request
                            fetch(`/cart/update/${id}`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                },
                                body: JSON.stringify({ quantity: newQty })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    this.subTotal = data.subtotal;
                                    this.discount = data.discount;
                                    this.shipping = data.shipping;
                                    // Update item subtotal if needed
                                    // this.cartItems[itemIndex].subtotal = data.item_total; 
                                } else {
                                    alert(data.message);
                                }
                            })
                            .catch(error => console.error('Error:', error));
                        },

                        removeItem(id) {
                            if(!confirm('Are you sure you want to remove this item?')) return;
                            window.location.href = `/cart/remove/${id}`;
                        }
                     }"
                >
                    <!-- Cart Items Section -->
                    <section class="lg:col-span-8">
                         <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                            <!-- Header -->
                             <div class="p-4 border-b border-gray-200 bg-gray-50 flex items-center">
                                <input type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-medium text-gray-700">Select All ({{ count($cartItems) }} Items)</span>
                            </div>

                            <!-- List -->
                            <ul role="list" class="divide-y divide-gray-200">
                                <template x-for="item in cartItems" :key="item.id">
                                    <li class="p-6 flex flex-col sm:flex-row sm:items-center">
                                         <div class="flex items-center mb-4 sm:mb-0">
                                            <input type="checkbox" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded mr-4">
                                            <div class="flex-shrink-0 w-24 h-24 border border-gray-200 rounded-md overflow-hidden">
                                                <img :src="item.image_url" :alt="item.name" class="w-full h-full object-center object-cover">
                                            </div>
                                        </div>

                                        <div class="flex-1 ml-0 sm:ml-6 flex flex-col justify-between">
                                            <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                                <div>
                                                    <div class="flex justify-between">
                                                        <h3 class="text-base font-semibold text-gray-900">
                                                            <a :href="`/product/${item.slug}`" x-text="item.name"></a>
                                                        </h3>
                                                    </div>
                                                    <div class="mt-1 flex text-sm">
                                                        <p class="text-gray-500">Color: Midnight Black</p> <!-- Placeholder -->
                                                        <p class="ml-4 pl-4 border-l border-gray-200 text-gray-500">Size: Standard</p> <!-- Placeholder -->
                                                    </div>
                                                    <div class="mt-2 flex items-baseline">
                                                        <p class="text-lg font-bold text-indigo-600" x-text="formatMoney(item.price)"></p>
                                                        <p x-show="item.original_price > item.price" class="ml-2 text-sm text-gray-500 line-through" x-text="formatMoney(item.original_price)"></p>
                                                    </div>
                                                </div>

                                                <div class="mt-4 sm:mt-0 sm:pr-9">
                                                    <!-- Quantity -->
                                                     <div class="flex items-center border border-gray-300 rounded-md w-32">
                                                        <button @click="updateQuantity(item.id, item.quantity - 1)" class="px-3 py-1 text-gray-600 hover:bg-gray-100 border-r border-gray-300 h-full">−</button>
                                                        <input type="number" readonly class="w-full text-center border-none focus:ring-0 p-1 text-gray-900 font-medium" x-model="item.quantity">
                                                        <button @click="updateQuantity(item.id, item.quantity + 1)" class="px-3 py-1 text-gray-600 hover:bg-gray-100 border-l border-gray-300 h-full">+</button>
                                                    </div>

                                                    <div class="absolute top-0 right-0">
                                                        <button @click="removeItem(item.id)" type="button" class="-m-2 p-2 inline-flex text-gray-400 hover:text-gray-500">
                                                            <span class="sr-only">Remove</span>
                                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </section>

                    <!-- Order Summary -->
                    <section class="lg:col-span-4 mt-8 lg:mt-0">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-6">Order Summary</h2>

                            <dl class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm text-gray-600">Subtotal</dt>
                                    <dd class="text-sm font-medium text-gray-900" x-text="formatMoney(subTotal)"></dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm text-gray-600">Shipping Estimate</dt>
                                    <dd class="text-sm font-medium text-green-600" x-text="shipping > 0 ? formatMoney(shipping) : 'Free'"></dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm text-gray-600">Tax Estimate</dt>
                                    <dd class="text-sm font-medium text-gray-900">$0.00</dd> <!-- Tax logic to be implemented -->
                                </div>
                                
                                <template x-if="discount > 0">
                                    <div class="flex items-center justify-between text-green-600">
                                        <dt class="text-sm">Discount</dt>
                                        <dd class="text-sm font-medium" x-text="'-' + formatMoney(discount)"></dd>
                                    </div>
                                </template>

                                <div class="border-t border-gray-200 pt-4 flex items-center justify-between">
                                    <dt class="text-base font-bold text-gray-900">Total</dt>
                                    <dd class="text-base font-bold text-indigo-600" x-text="formatMoney(total)"></dd>
                                </div>
                            </dl>

                            <div class="mt-6">
                                <a href="{{ route('cart.checkout') }}" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 flex justify-center items-center">
                                    Checkout <span class="ml-2">→</span>
                                </a>
                            </div>

                            <div class="mt-6 border-t border-gray-200 pt-6" x-data="{ showCoupon: false }">
                                <div @click="showCoupon = !showCoupon" class="flex items-center space-x-2 text-sm text-gray-500 hover:text-gray-900 cursor-pointer">
                                    <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                    <span>Add Coupon Code</span>
                                </div>
                                <!-- Coupon Form -->
                                <form action="{{ route('cart.index') }}" method="GET" class="mt-4 flex space-x-2" x-show="showCoupon" x-transition>
                                    <input type="text" name="coupon" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Promo code">
                                    <button type="submit" class="bg-gray-200 rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300">Apply</button>
                                </form>
                            </div>

                             <div class="mt-8 flex justify-center space-x-2 opacity-50">
                                <div class="h-8 w-12 bg-gray-200 rounded"></div> <!-- VISA placeholder -->
                                <div class="h-8 w-12 bg-gray-200 rounded"></div> <!-- MC placeholder -->
                                <div class="h-8 w-12 bg-gray-200 rounded"></div> <!-- PP placeholder -->
                             </div>
                             
                             <p class="mt-4 text-xs text-center text-gray-400">
                                 By placing your order, you agree to our Terms of Service and Privacy Policy.
                             </p>
                        </div>
                    </section>
                </div>

                <!-- Related Products Section -->
                <section class="mt-24">
                     <h2 class="text-2xl font-bold text-gray-900 mb-6">You Might Also Like</h2>
                     
                     <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-4 xl:gap-x-8">
                        <!-- Placeholder Related Products -->
                        @for($i = 0; $i < 4; $i++)
                        <div class="group relative">
                            <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                <img src="{{ asset('img/product-'.($i+1).'.png') }}" alt="Product Image" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="#">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            Product Name {{ $i + 1 }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">Black</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">$35.00</p>
                            </div>
                        </div>
                        @endfor
                     </div>
                </section>
            @endif
        </div>
    </div>
    <x-store.footer />
</x-base-layout>
