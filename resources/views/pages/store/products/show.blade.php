<x-base-layout>
    @push('meta')
    <meta property="og:title" content="{{ $product->name }}" />
    <meta property="og:description" content="{{ Str::limit(strip_tags($product->description), 150) }}" />
    <meta property="og:image" content="{{ $product->image_url ? Storage::url($product->image_url) : asset('img/no-image.png') }}" />
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
                <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $product->name }}</h1>
                    
                    <div class="mt-3">
                        <h2 class="sr-only">Product information</h2>
                        <div class="flex items-center">
                            @if($product->discount_price)
                                <p class="text-3xl text-gray-900">${{ number_format($product->discount_price, 2) }}</p>
                                <p class="text-xl text-gray-500 line-through ml-4">${{ number_format($product->price, 2) }}</p>
                            @else
                                <p class="text-3xl text-gray-900">${{ number_format($product->price, 2) }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews -->
                    <div class="mt-3">
                        <h3 class="sr-only">Reviews</h3>
                        <div class="flex items-center">
                            <div class="flex items-center">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="h-5 w-5 flex-shrink-0 {{ $i < 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="sr-only">4 out of 5 stars</p>
                            <a href="#" class="ml-3 text-sm font-medium text-indigo-600 hover:text-indigo-500">117 reviews</a>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="sr-only">Description</h3>
                        <div class="text-base text-gray-700 space-y-6">
                            <p>{{ Str::limit(strip_tags($product->description), 200) }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        @if($product->stock_quantity > 0)
                            <div class="flex items-center text-green-600">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                In stock ({{ $product->stock_quantity }} available)
                            </div>
                        @else
                            <div class="flex items-center text-red-600">
                                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Out of stock
                            </div>
                        @endif
                    </div>

                    <!-- Add to Cart -->
                    <div class="mt-10 flex sm:flex-col1">
                        @if($product->stock_quantity > 0)
                        <button type="submit" class="max-w-xs flex-1 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-indigo-500 sm:w-full">
                            Add to bag
                        </button>
                        @else
                        <button type="button" disabled class="max-w-xs flex-1 bg-gray-300 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white cursor-not-allowed sm:w-full">
                            Out of Stock
                        </button>
                        @endif

                        <button type="button" class="ml-4 py-3 px-3 rounded-md flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-500">
                            <svg class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="sr-only">Add to favorites</span>
                        </button>
                    </div>

                    <!-- Vendor Info -->
                    @if($product->vendor)
                    <div class="mt-8 border-t border-gray-200 pt-8">
                         <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full bg-gray-300" src="{{ $product->vendor->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($product->vendor->name) }}" alt="">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Sold by {{ $product->vendor->name }}</p>
                                <a href="#" class="text-xs text-indigo-600 hover:text-indigo-500">View Grid Store</a>
                            </div>
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
