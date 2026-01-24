<!-- Coupon Input Component with AJAX Validation -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Have a coupon code?</h3>

    <div x-data="couponHandler()" class="space-y-4">
        <!-- Coupon Input Form -->
        <form @submit.prevent="applyCoupon()" class="flex gap-3">
            <input
                type="text"
                x-model="couponCode"
                :disabled="loading || couponApplied"
                placeholder="Enter coupon code"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
                @keyup="clearError()">
            <button
                type="submit"
                :disabled="loading || couponApplied || !couponCode.trim()"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition">
                <span x-show="!loading">Apply</span>
                <span x-show="loading" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Validating...
                </span>
            </button>
        </form>

        <!-- Error Message -->
        <div x-show="error" x-cloak class="p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm flex items-start gap-2">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span x-text="error"></span>
        </div>

        <!-- Success Message -->
        <div x-show="couponApplied" x-cloak class="p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm flex items-start justify-between">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-semibold" x-text="appliedMessage"></p>
                    <p class="text-xs mt-1">
                        <span>Code: <strong x-text="couponCode" class="uppercase"></strong></span>
                        <span class="mx-2">•</span>
                        <span>You saved: <strong x-text="'$' + discountAmount.toFixed(2)"></strong></span>
                    </p>
                </div>
            </div>
            <button
                type="button"
                @click="removeCoupon()"
                class="text-green-600 hover:text-green-800 font-semibold">
                Remove
            </button>
        </div>

        <!-- Discount Summary (when coupon applied) -->
        <div x-show="couponApplied" x-cloak class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex justify-between items-center mb-3">
                <span class="text-gray-700">Subtotal:</span>
                <span class="font-semibold" x-text="'$' + subtotal.toFixed(2)"></span>
            </div>
            <div class="flex justify-between items-center text-green-600 font-semibold mb-3 border-t pt-3">
                <span>Discount (<span x-text="couponCode.toUpperCase()"></span>):</span>
                <span x-text="'-$' + discountAmount.toFixed(2)"></span>
            </div>
            <div class="flex justify-between items-center text-lg font-bold border-t pt-3 text-blue-600">
                <span>New Total:</span>
                <span x-text="'$' + finalTotal.toFixed(2)"></span>
            </div>
        </div>
    </div>
</div>

<script>
    function couponHandler() {
        return {
            couponCode: '',
            loading: false,
            error: '',
            couponApplied: false,
            appliedMessage: '',
            subtotal: 0,
            discountAmount: 0,
            finalTotal: 0,

            async applyCoupon() {
                if (!this.couponCode.trim()) {
                    this.error = 'Please enter a coupon code';
                    return;
                }

                this.loading = true;
                this.error = '';

                try {
                    const response = await fetch('/api/v1/coupons/validate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || ''),
                        },
                        body: JSON.stringify({
                            code: this.couponCode.toUpperCase(),
                            order_total: this.getCartTotal(),
                        }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.couponApplied = true;
                        this.appliedMessage = data.message;
                        this.subtotal = data.data.original_total;
                        this.discountAmount = data.data.discount_amount;
                        this.finalTotal = data.data.final_total;

                        // Dispatch event to update cart total
                        window.dispatchEvent(new CustomEvent('coupon-applied', {
                            detail: {
                                code: this.couponCode.toUpperCase(),
                                discountAmount: this.discountAmount,
                                finalTotal: this.finalTotal,
                            }
                        }));
                    } else {
                        this.error = data.message || 'Invalid coupon code';
                    }
                } catch (error) {
                    this.error = 'An error occurred. Please try again.';
                    console.error('Coupon validation error:', error);
                } finally {
                    this.loading = false;
                }
            },

            removeCoupon() {
                this.couponApplied = false;
                this.couponCode = '';
                this.discountAmount = 0;
                this.error = '';
                this.appliedMessage = '';

                window.dispatchEvent(new CustomEvent('coupon-removed'));
            },

            clearError() {
                this.error = '';
            },

            getCartTotal() {
                // This should be fetched from the cart total displayed on page
                // Using a data attribute or from the DOM
                const totalElement = document.querySelector('[data-cart-total]');
                return parseFloat(totalElement?.textContent || '0');
            }
        };
    }
</script>