<x-app-layout>
    <!-- Hero Section -->
    <x-store.hero />

    <!-- Featured Categories -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 mb-6">Browse by Category</h2>
            <div class="grid grid-cols-3 md:grid-cols-6 gap-6">
                @foreach($categories as $category)
                    <x-store.category-card :category="$category" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- Flash Sales -->
    <section class="py-12 bg-red-50" x-data="{
        timeLeft: {
            hours: 0,
            minutes: 0,
            seconds: 0
        },
        endTime: new Date().getTime() + 86400000, // 24 hours from now
        updateTimer() {
            const now = new Date().getTime();
            const distance = this.endTime - now;
            this.timeLeft.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            this.timeLeft.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            this.timeLeft.seconds = Math.floor((distance % (1000 * 60)) / 1000);
        }
    }" x-init="setInterval(() => updateTimer(), 1000)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Flash Sale</h2>
                    <!-- Countdown Timer -->
                    <div class="flex space-x-2 text-sm font-bold text-red-600 bg-red-100 px-3 py-1 rounded-full">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-text="String(timeLeft.hours).padStart(2, '0')"></span> :
                        <span x-text="String(timeLeft.minutes).padStart(2, '0')"></span> :
                        <span x-text="String(timeLeft.seconds).padStart(2, '0')"></span>
                    </div>
                </div>
                <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">View All Deals &rarr;</a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($flashSales as $product)
                    <x-store.product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- New Arrivals -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                 <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">New Arrivals</h2>
                  <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">View All &rarr;</a>
            </div>
           
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($newProducts as $product)
                    <x-store.product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>
    
    <!-- Best Sellers (Mocked/Static for visual balance if needed, or re-use arrivals/popular) -->
    <section class="py-12 bg-gray-50">
           <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                 <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Best Sellers</h2>
                  <div class="flex space-x-2">
                      <button class="p-2 rounded-full bg-gray-200 hover:bg-gray-300">
                          <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                      </button>
                       <button class="p-2 rounded-full bg-blue-500 hover:bg-blue-600 text-white">
                          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                      </button>
                  </div>
            </div>
           
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                @foreach($arrivals->take(5) as $product)
                     <x-store.product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

</x-app-layout>
