<!-- Flash Sale Badge Component -->
@if($product->is_on_sale && $product->sale_price)
<div class="relative">
    <!-- Badge -->
    <div class="absolute top-2 right-2 z-10 bg-gradient-to-r from-red-500 to-orange-500 text-white px-3 py-2 rounded-full shadow-lg flex items-center gap-1 text-sm font-bold">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M4.25 5.5a.75.75 0 00-.5.94l2.5 9.792A.75.75 0 007.75 17h4.5a.75.75 0 00.738-.558l2.5-9.792a.75.75 0 10-1.477-.306L11.89 15h-3.78L5.227 5.56a.75.75 0 00-.977-.06z" />
        </svg>
        <span>
            {{ intval($product->discount_percentage) }}% OFF
        </span>
    </div>

    <!-- Sale Price Display -->
    <div class="mt-2 space-y-1">
        <div class="flex items-baseline gap-2">
            <span class="text-2xl font-bold text-green-600">${{ number_format($product->sale_price, 2) }}</span>
            <span class="text-lg text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
        </div>
        <div class="text-sm text-gray-600">
            You save: <strong class="text-green-600">${{ number_format($product->price - $product->sale_price, 2) }}</strong>
        </div>
    </div>

    <!-- Countdown Timer -->
    <div x-data="countdownTimer('{{ $product->time_remaining }}')" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-xs text-red-700 font-semibold uppercase">Sale ends in:</p>
        <p class="text-lg font-bold text-red-600 mt-1" x-text="timeRemaining"></p>

        <!-- Progress bar -->
        <div class="mt-2 h-1 bg-red-200 rounded-full overflow-hidden">
            <div
                class="h-full bg-gradient-to-r from-red-500 to-orange-500 transition-all duration-1000"
                :style="{ 'width': progress + '%' }"></div>
        </div>
    </div>

    <!-- Quick Add to Cart Button -->
    <button
        @click="addToCart({{ $product->id }})"
        class="w-full mt-4 bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-lg transition shadow-lg hover:shadow-xl">
        Add to Cart - Limited Time!
    </button>
</div>

<script>
    function countdownTimer(initialSeconds) {
        return {
            totalSeconds: parseInt(initialSeconds),
            timeRemaining: '00h 00m 00s',
            progress: 100,
            totalDuration: parseInt(initialSeconds),

            init() {
                this.updateTimer();
                setInterval(() => this.updateTimer(), 1000);
            },

            updateTimer() {
                if (this.totalSeconds <= 0) {
                    this.timeRemaining = 'Sale ended';
                    this.progress = 0;
                    return;
                }

                const hours = Math.floor(this.totalSeconds / 3600);
                const minutes = Math.floor((this.totalSeconds % 3600) / 60);
                const seconds = this.totalSeconds % 60;

                this.timeRemaining = `${String(hours).padStart(2, '0')}h ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;

                this.progress = (this.totalSeconds / this.totalDuration) * 100;

                this.totalSeconds--;
            },

            addToCart(productId) {
                // Dispatch event to cart component or call cart API
                fetch('/api/v1/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Authorization': 'Bearer ' + (localStorage.getItem('api_token') || ''),
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Added to cart!');
                        } else {
                            alert(data.message || 'Error adding to cart');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        };
    }
</script>
@endif

<!-- Alternative: Simple Static Badge (when no countdown needed) -->
@if($product->is_on_sale && $product->sale_price && false)
<div class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold absolute top-2 right-2 shadow-lg">
    {{ intval($product->discount_percentage) }}% OFF
</div>
@endif