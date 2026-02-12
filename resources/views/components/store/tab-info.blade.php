@props(['product'])

<div x-data="{ activeTab: 'description' }" class="mt-16">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button 
                @click="activeTab = 'description'"
                :class="activeTab === 'description' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
            >
                Description
            </button>
            <button 
                @click="activeTab = 'specs'"
                :class="activeTab === 'specs' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
            >
                Specifications
            </button>
            <button 
                @click="activeTab = 'reviews'"
                :class="activeTab === 'reviews' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
            >
                Reviews ({{ $product->reviews_count ?? 0 }})
            </button>
        </nav>
    </div>

    <!-- Tab Contents -->
    <div class="py-8">
        <!-- Description -->
        <div x-show="activeTab === 'description'" x-transition.opacity>
            <div class="prose prose-indigo max-w-none text-gray-600">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>

        <!-- Specifications -->
        <div x-show="activeTab === 'specs'" x-transition.opacity class="hidden">
            <div class="max-w-xl">
                <dl class="divide-y divide-gray-200">
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                        <dd class="text-sm text-gray-900">{{ $product->sku }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Brand</dt>
                        <dd class="text-sm text-gray-900">{{ $product->vendor->name ?? 'Zentro' }}</dd>
                    </div>
                    <div class="py-3 flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Stock Status</dt>
                        <dd class="text-sm text-gray-900">
                            @if($product->stock_quantity > 0)
                                <span class="text-green-600">In Stock ({{ $product->stock_quantity }})</span>
                            @else
                                <span class="text-red-600">Out of Stock</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Reviews -->
        <div x-show="activeTab === 'reviews'" x-transition.opacity class="hidden">
            @if($product->reviews && $product->reviews->isNotEmpty())
                <div class="space-y-6">
                    @foreach($product->reviews as $review)
                    <div class="flex space-x-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full bg-gray-300" src="{{ $review->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($review->user->name) }}" alt="">
                        </div>
                        <div>
                            <div class="flex items-center mb-1">
                                <h4 class="text-sm font-bold text-gray-900 mr-2">{{ $review->user->name }}</h4>
                                <div class="flex text-yellow-400">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="h-4 w-4 {{ $i < $review->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                            <span class="text-xs text-gray-400 mt-1 block">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <p>No reviews yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
