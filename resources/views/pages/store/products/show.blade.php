<x-base-layout>
    @push('meta')
    <meta property="og:title" content="{{ $product->name }}" />
    <meta property="og:description" content="{{ Str::limit(strip_tags($product->description), 150) }}" />
    <meta property="og:image" content="{{ $product->image_url ? asset($product->image_url) : asset('img/no-image.png') }}" />
    <meta property="og:url" content="{{ route('shop.show', $product->slug) }}" />
    <meta property="og:type" content="product" />
    <meta property="product:price:amount" content="{{ $product->discount_price ?? $product->price }}" />
    <meta property="product:price:currency" content="USD" />
    @endpush

    <x-store.navbar />

    <div class="bg-white py-8 min-h-screen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-gray-500">Home</a>
                    </li>
                    <li>
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <a href="{{ route('shop.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Shop</a>
                    </li>
                    @if($product->category)
                    <li>
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <a href="{{ route('shop.index', ['category' => $product->category->slug ?? $product->category->id]) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $product->category->name }}</a>
                    </li>
                    @endif
                    <li>
                        <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-900" aria-current="page">{{ $product->name }}</span>
                    </li>
                </ol>
            </nav>

            <!-- Product Stats -->
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
                
                <!-- Gallery -->
                <x-store.product-gallery :product="$product" />

                <!-- Product Info -->
                <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0 lg:ml-10">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ $product->name }}</h1>
                    
                    <div class="mt-4 flex items-end">
                        <h2 class="sr-only">Product information</h2>
                        @if($product->discount_price)
                            <p class="text-4xl font-bold text-gray-900">${{ number_format($product->discount_price, 2) }}</p>
                            <p class="text-xl text-gray-500 line-through ml-4 mb-1">${{ number_format($product->price, 2) }}</p>
                             <span class="ml-4 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800 tracking-wide uppercase">
                                Save {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                            </span>
                        @else
                            <p class="text-4xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                        @endif
                    </div>

                    <!-- Reviews & Sold Count -->
                    <div class="mt-4">
                        <h3 class="sr-only">Reviews</h3>
                        <div class="flex items-center">
                            <div class="flex items-center">
                                @php $rating = $product->reviews_avg_rating ?? 0; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5 flex-shrink-0 {{ $i <= round($rating) ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="sr-only">{{ $rating }} out of 5 stars</p>
                            <a href="#" class="ml-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ $product->reviews_count ?? 0 }} reviews</a>
                            
                            @if($product->sold_count > 0)
                            <span class="mx-2 text-gray-300">|</span>
                            <span class="text-sm text-gray-500">{{ $product->sold_count }} sold</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="sr-only">Description</h3>
                        <div class="text-base text-gray-700 space-y-4">
                            <p>{{ Str::limit(strip_tags($product->description), 250) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center">
                        @if($product->stock_quantity > 0)
                            <div class="flex items-center text-green-700 bg-green-50 px-3 py-1 rounded-md text-sm font-medium">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                In stock ({{ $product->stock_quantity }} available)
                            </div>
                        @else
                            <div class="flex items-center text-red-700 bg-red-50 px-3 py-1 rounded-md text-sm font-medium">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Out of stock
                            </div>
                        @endif
                        
                        <div class="ml-4 text-sm text-gray-500">
                             SKU: <span class="font-medium text-gray-900">{{ $product->sku }}</span>
                        </div>
                    </div>

                    <!-- Add to Cart Section -->
                    <div class="mt-10 pt-10 border-t border-gray-200" x-data="{ quantity: 1, maxStock: {{ $product->stock_quantity }} }">
                        <div class="flex flex-col sm:flex-row sm:space-x-4">
                             <!-- Quantity Selector -->
                            <div class="mb-4 sm:mb-0 w-32">
                                <label for="quantity" class="sr-only">Quantity</label>
                                <div class="flex flex-row h-12 w-full rounded-lg relative bg-transparent mt-1">
                                    <button @click="quantity > 1 ? quantity-- : null" data-action="decrement" class=" bg-gray-200 text-gray-600 hover:text-gray-700 hover:bg-gray-300 h-full w-20 rounded-l cursor-pointer outline-none">
                                        <span class="m-auto text-2xl font-thin">−</span>
                                    </button>
                                    <input type="number" class="outline-none focus:outline-none text-center w-full bg-gray-100 font-semibold text-md hover:text-black focus:text-black  md:text-basecursor-default flex items-center text-gray-700  outline-none" name="custom-input-number" x-model="quantity" min="1" :max="maxStock">
                                    <button @click="quantity < maxStock ? quantity++ : null" data-action="increment" class="bg-gray-200 text-gray-600 hover:text-gray-700 hover:bg-gray-300 h-full w-20 rounded-r cursor-pointer outline-none">
                                        <span class="m-auto text-2xl font-thin">+</span>
                                    </button>
                                </div>
                            </div>

                            @if($product->stock_quantity > 0)
                            <form action="{{ route('cart.add', $product->id) }}" method="GET" class="flex-1 flex" >
                                <input type="hidden" name="quantity" :value="quantity">
                                <button type="submit" class="flex-1 bg-indigo-600 border border-transparent rounded-lg py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 shadow-sm transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Add to Cart
                                </button>
                            </form>
                            @else
                            <button type="button" disabled class="flex-1 bg-gray-200 border border-transparent rounded-lg py-3 px-8 flex items-center justify-center text-base font-medium text-gray-400 cursor-not-allowed">
                                Out of Stock
                            </button>
                            @endif

                            <button type="button" class="ml-4 py-3 px-4 rounded-lg flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 border border-gray-200 transition-colors duration-200">
                                <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span class="sr-only">Add to favorites</span>
                            </button>
                        </div>
                    </div>

                    <!-- Vendor Info -->
                    @if($product->vendor)
                    <div class="mt-8 border-t border-gray-200 pt-8">
                         <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img class="h-12 w-12 rounded-full bg-gray-300 border border-gray-200" src="{{ $product->vendor->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($product->vendor->name) }}" alt="">
                                <div class="ml-4">
                                    <p class="text-base font-bold text-gray-900">{{ $product->vendor->name }}</p>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Verified Seller
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Visit Store
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Tab Info -->
            <x-store.tab-info :product="$product" />

            <!-- Related Products -->
            @if($relatedProducts->isNotEmpty())
            <div class="mt-16 border-t border-gray-200 pt-16">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Customers also bought</h2>
                <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                    @foreach($relatedProducts as $related)
                        <x-store.product-card :product="$related" />
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
    
    <x-store.footer />
</x-base-layout>
