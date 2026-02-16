<x-base-layout>
    <x-store.navbar />

    <div class="bg-gray-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg border border-gray-100">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                    <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <h2 class="mt-2 text-3xl font-extrabold text-gray-900">Order Placed!</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Thank you for your purchase. Your order has been successfully placed.
                </p>
                
                @if(session('order_id'))
                    <div class="mt-6 inline-flex items-center px-4 py-2 rounded-full bg-gray-100 text-gray-800 text-sm font-medium">
                        Order #{{ session('order_id') }}
                    </div>
                @endif
            </div>

            <div class="mt-8 space-y-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                You will receive an email confirmation shortly with your order details and tracking information.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-3">
                @if(Auth::check())
                     <a href="{{ route('orders.show', session('order_id')) }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        View Order Details
                    </a>
                @else
                    <!-- Guest flow or generic link -->
                     @if(session('order_id'))
                        <a href="{{ route('orders.index') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            View Order History
                        </a>
                     @endif
                @endif
                
                <a href="{{ route('shop.index') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
    
    <x-store.footer />
</x-base-layout>
