<section id="payment-methods">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Payment Methods') }}
        </h2>
        <button type="button" class="text-sm font-medium text-blue-600 hover:text-blue-500">
            + {{ __('Add New Card') }}
        </button>
    </div>

    <div class="space-y-4">
        <!-- Master Card -->
        <div class="border rounded-lg p-4 flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-4">
                <div class="h-10 w-16 bg-gray-100 rounded flex items-center justify-center text-gray-500 font-bold text-xs">
                    <!-- Icon placeholder -->
                    MASTERCARD
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Mastercard ending in 8845</h4>
                    <p class="text-xs text-gray-500">Expiry 06/2026</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded">Default</span>
                <button class="text-gray-400 hover:text-red-500">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Visa Card -->
        <div class="border rounded-lg p-4 flex items-center justify-between bg-white hover:bg-gray-50 transition-colors">
            <div class="flex items-center gap-4">
                 <div class="h-10 w-16 bg-gray-100 rounded flex items-center justify-center text-gray-500 font-bold text-xs">
                    <!-- Icon placeholder -->
                    VISA
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Visa ending in 1234</h4>
                    <p class="text-xs text-gray-500">Expiry 12/2024</p>
                </div>
            </div>
             <div class="flex items-center gap-3">
                <button class="text-sm font-medium text-gray-500 hover:text-blue-600">Set as default</button>
                <button class="text-gray-400 hover:text-red-500">
                     <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</section>
