<x-base-layout>
    <x-store.navbar />

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-gray-500 text-sm">
                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                <span class="mx-2">&rsaquo;</span>
                <a href="{{ route('profile.edit') }}" class="hover:text-gray-900">Account</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900 font-medium">Wishlist</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar -->
                <div class="w-full md:w-1/4 shrink-0 sticky top-4 self-start">
                    @include('profile.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">My Wishlist</h2>
                                <p class="text-sm text-gray-500 mt-1">{{ $wishlists->count() }} {{ Str::plural('item', $wishlists->count()) }}</p>
                            </div>
                        </div>

                        @if($wishlists->count() > 0)
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($wishlists as $wishlist)
                                    @if($wishlist->product)
                                        <div class="group border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                            <!-- Product Image -->
                                            <div class="relative aspect-square bg-gray-100">
                                                <a href="{{ route('shop.show', $wishlist->product->slug ?? $wishlist->product->id) }}">
                                                    <img src="{{ $wishlist->product->image_url ?? 'https://via.placeholder.com/300' }}"
                                                         alt="{{ $wishlist->product->name }}"
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                </a>
                                                <!-- Remove Button -->
                                                <form action="{{ route('wishlist.remove', $wishlist->product_id) }}" method="POST"
                                                      class="absolute top-2 right-2">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="p-1.5 bg-white/80 backdrop-blur rounded-full text-gray-500 hover:text-red-500 hover:bg-red-50 transition-colors shadow-sm"
                                                            title="Remove from wishlist">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                            <!-- Product Info -->
                                            <div class="p-3">
                                                <a href="{{ route('shop.show', $wishlist->product->slug ?? $wishlist->product->id) }}"
                                                   class="text-sm font-medium text-gray-900 hover:text-orange-600 line-clamp-2">
                                                    {{ $wishlist->product->name }}
                                                </a>
                                                <p class="text-sm font-bold text-orange-600 mt-1">
                                                    ${{ number_format($wishlist->product->price, 2) }}
                                                    @if($wishlist->product->original_price && $wishlist->product->original_price > $wishlist->product->price)
                                                        <span class="text-xs text-gray-400 line-through ml-1">${{ number_format($wishlist->product->original_price, 2) }}</span>
                                                    @endif
                                                </p>
                                                <a href="{{ route('cart.add', $wishlist->product->id) }}"
                                                   class="mt-2 w-full inline-flex items-center justify-center px-3 py-1.5 bg-orange-600 text-white text-xs font-medium rounded-md hover:bg-orange-700 transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                                    Add to Cart
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16">
                                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900 mb-1">Your wishlist is empty</h3>
                                <p class="text-sm text-gray-500 mb-4">Save items you love by clicking the heart icon.</p>
                                <a href="{{ route('shop.index') }}"
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                    Start Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-store.footer />
</x-base-layout>
