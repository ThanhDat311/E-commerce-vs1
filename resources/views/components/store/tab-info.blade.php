@props(['product'])

<div id="product-tabs-container" x-data="{ 
    activeTab: 'description',
    init() {
        window.addEventListener('switch-tab', (e) => {
            this.activeTab = e.detail.tab;
            if (e.detail.scroll) {
                this.$nextTick(() => {
                    const el = document.getElementById('product-tabs-container');
                    if (el) {
                        const offset = 100; // Offset for fixed navbar
                        const bodyRect = document.body.getBoundingClientRect().top;
                        const elementRect = el.getBoundingClientRect().top;
                        const elementPosition = elementRect - bodyRect;
                        const offsetPosition = elementPosition - offset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            }
        });
    }
}" class="mt-16">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button 
                @click="activeTab = 'description'"
                :class="activeTab === 'description' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200"
            >
                Description
            </button>
            <button 
                @click="activeTab = 'specs'"
                :class="activeTab === 'specs' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200"
            >
                Specifications
            </button>
            <button 
                @click="activeTab = 'reviews'"
                :class="activeTab === 'reviews' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all duration-200"
            >
                Reviews <span class="ml-1 py-0.5 px-2.5 rounded-full text-xs font-medium" :class="activeTab === 'reviews' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900'">{{ $product->reviews_count ?? 0 }}</span>
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="py-8">
        <!-- Description -->
        <div x-show="activeTab === 'description'" x-transition.opacity>
            <div class="prose prose-indigo max-w-none text-gray-600 leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>

        <!-- Specifications -->
        <div x-show="activeTab === 'specs'" x-transition.opacity>
            <div class="max-w-2xl bg-white rounded-lg border border-gray-200 overflow-hidden">
                <dl class="divide-y divide-gray-200">
                    <div class="py-4 px-6 grid grid-cols-3 gap-4 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                        <dd class="text-sm text-gray-900 col-span-2 font-mono">{{ $product->sku }}</dd>
                    </div>
                    <div class="py-4 px-6 grid grid-cols-3 gap-4 bg-white">
                        <dt class="text-sm font-medium text-gray-500">Brand</dt>
                        <dd class="text-sm text-gray-900 col-span-2">{{ $product->vendor->name ?? 'Zentro' }}</dd>
                    </div>
                    <div class="py-4 px-6 grid grid-cols-3 gap-4 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Stock Status</dt>
                        <dd class="text-sm text-gray-900 col-span-2">
                            @if($product->stock_quantity > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    In Stock ({{ $product->stock_quantity }})
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Out of Stock
                                </span>
                            @endif
                        </dd>
                    </div>
                    <!-- Add more dynamic specs here if available in the database -->
                     <div class="py-4 px-6 grid grid-cols-3 gap-4 bg-white">
                        <dt class="text-sm font-medium text-gray-500">Dimensions</dt>
                        <dd class="text-sm text-gray-900 col-span-2">N/A</dd>
                    </div>
                     <div class="py-4 px-6 grid grid-cols-3 gap-4 bg-gray-50">
                        <dt class="text-sm font-medium text-gray-500">Weight</dt>
                        <dd class="text-sm text-gray-900 col-span-2">N/A</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Reviews -->
        <div x-show="activeTab === 'reviews'" x-transition.opacity id="product-reviews-section">

            {{-- Flash messages --}}
            @if(session('review_success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    {{ session('review_success') }}
                </div>
            @endif
            @if(session('review_error'))
                <div class="mb-6 rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-700">
                    {{ session('review_error') }}
                </div>
            @endif

            @if($product->reviews && $product->reviews->isNotEmpty())
                <div class="space-y-8 mb-10">
                    @foreach($product->reviews as $review)
                    <div class="flex space-x-4 p-6 bg-gray-50 rounded-xl">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-full border border-gray-200" src="{{ $review->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($review->user->name) }}" alt="">
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-bold text-gray-900">{{ $review->user->name }}</h4>
                                <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="h-4 w-4 {{ $i < $review->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="ml-2 text-xs font-medium text-gray-500">Verified Purchase</span>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300 mb-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Be the first to share your thoughts on this product.</p>
                </div>
            @endif

            {{-- Write a Review --}}
            <div class="border-t border-gray-200 pt-8">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Write a Review</h3>

                @auth
                    @php
                        $alreadyReviewed = $product->reviews->contains('user_id', auth()->id());
                        $hasPurchased = auth()->user()->orders()->where('order_status', 'delivered')->whereHas('orderItems', function($query) use ($product) {
                            $query->where('product_id', $product->id);
                        })->exists();
                    @endphp

                    @if($alreadyReviewed)
                        <div class="rounded-lg bg-indigo-50 border border-indigo-200 px-4 py-3 text-sm text-indigo-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            You have already submitted a review for this product. Thank you!
                        </div>
                    @elseif(!$hasPurchased)
                        <div class="rounded-xl bg-gray-50 border border-gray-200 px-6 py-5 text-sm text-gray-600 flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>
                                You must purchase and receive this product before you can write a review.
                            </span>
                        </div>
                    @else
                        <form action="{{ route('reviews.store', $product) }}" method="POST"
                              x-data="{ rating: 0, hovered: 0 }"
                              class="bg-gray-50 rounded-xl border border-gray-200 p-6 space-y-5">
                            @csrf

                            {{-- Star Rating --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating <span class="text-red-500">*</span></label>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button"
                                            @click="rating = {{ $i }}"
                                            @mouseenter="hovered = {{ $i }}"
                                            @mouseleave="hovered = 0"
                                            class="text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors"
                                            :class="(hovered >= {{ $i }} || (hovered === 0 && rating >= {{ $i }})) ? 'text-yellow-400' : 'text-gray-300'"
                                        >
                                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-500" x-text="rating > 0 ? rating + ' star' + (rating > 1 ? 's' : '') : 'Click to rate'"></span>
                                </div>
                                <input type="hidden" name="rating" :value="rating">
                                @error('rating')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Comment --}}
                            <div>
                                <label for="review-comment" class="block text-sm font-medium text-gray-700 mb-1">Your Review</label>
                                <textarea id="review-comment" name="comment" rows="4"
                                    placeholder="Share your experience with this product..."
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('comment') border-red-400 @enderror">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="submit"
                                    x-bind:disabled="rating === 0"
                                    class="inline-flex items-center px-5 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                    Submit Review
                                </button>
                                <span class="text-xs text-gray-400">You must select a star rating.</span>
                            </div>
                        </form>
                    @endif
                @else
                    <div class="rounded-xl bg-gray-50 border border-gray-200 px-6 py-5 text-sm text-gray-600 flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>
                            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Log in</a>
                            to share your review on this product.
                        </span>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
