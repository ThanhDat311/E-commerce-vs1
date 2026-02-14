@props(['product'])

<div x-data="{ activeTab: 'description' }" class="mt-16">
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
        <div x-show="activeTab === 'specs'" x-transition.opacity class="hidden">
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
        <div x-show="activeTab === 'reviews'" x-transition.opacity class="hidden">
            @if($product->reviews && $product->reviews->isNotEmpty())
                <div class="space-y-8">
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
                <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No reviews yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Be the first to share your thoughts on this product.</p>
                </div>
            @endif
        </div>
    </div>
</div>
