@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-white">
    <!-- Search Header -->
    <div class="container mx-auto px-4 py-8 mb-6">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Search Results</h1>
        @if($query)
        <p class="text-lg text-gray-600">
            Found <span class="font-semibold text-blue-600">{{ $results->total() }}</span>
            @if($results->total() == 1)
            result
            @else
            results
            @endif
            for "<span class="font-semibold italic">{{ $query }}</span>"
        </p>
        @else
        <p class="text-gray-600">Browse our products</p>
        @endif
    </div>

    <!-- Search Bar -->
    <div class="container mx-auto px-4 mb-8">
        @include('components.search-bar')
    </div>
</div>

<div class="container mx-auto px-4 py-8" x-data="searchFilters()">
    @if($results->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                <!-- Filter Header -->
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Filters</h2>
                    <button
                        @click="resetFilters()"
                        type="button"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Reset
                    </button>
                </div>

                <form method="GET" class="space-y-6" id="filter-form">
                    <!-- Hidden Search Query -->
                    <input type="hidden" name="q" value="{{ request('q') }}" />

                    <!-- Category Filter -->
                    <div class="border-b pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v10H5V5z"></path>
                            </svg>
                            Category
                        </h3>
                        <div class="space-y-3">
                            @foreach($categories ?? [] as $category)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    name="category[]"
                                    value="{{ $category->id }}"
                                    {{ in_array($category->id, request('category', [])) ? 'checked' : '' }}
                                    @change="submitForm()"
                                    class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-2 focus:ring-blue-500" />
                                <span class="text-sm text-gray-700 group-hover:text-gray-900">
                                    {{ $category->name }}
                                </span>
                                <span class="text-xs text-gray-500 ml-auto">
                                    ({{ $category->products_count ?? 0 }})
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <div class="border-b pb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.16 5.314l4.897-1.596A1 1 0 0114.791 5v8a2 2 0 01-2 2H8V5.314z"></path>
                                <path d="M5 5a2 2 0 012 2v8a2 2 0 01-2 2H3a1 1 0 01-1-1V6a1 1 0 011-1h2z"></path>
                            </svg>
                            Price Range
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-xs font-medium text-gray-700">Minimum</label>
                                <input
                                    type="number"
                                    name="min_price"
                                    value="{{ request('min_price', '') }}"
                                    placeholder="$0"
                                    min="0"
                                    step="10"
                                    @change="submitForm()"
                                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-700">Maximum</label>
                                <input
                                    type="number"
                                    name="max_price"
                                    value="{{ request('max_price', '') }}"
                                    placeholder="$9999"
                                    min="0"
                                    step="10"
                                    @change="submitForm()"
                                    class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                        </div>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path>
                            </svg>
                            Sort By
                        </h3>
                        <select
                            name="sort"
                            @change="submitForm()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Relevance</option>
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
            <!-- Results Info -->
            <div class="mb-6 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Showing <span class="font-semibold">{{ $results->firstItem() }}</span> to
                    <span class="font-semibold">{{ $results->lastItem() }}</span> of
                    <span class="font-semibold">{{ $results->total() }}</span> results
                </p>
            </div>

            <!-- Products -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($results as $product)
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">
                    <!-- Product Image -->
                    <a href="{{ route('products.show', $product->id) }}" class="relative overflow-hidden bg-gray-100 aspect-square block">
                        @if($product->image_url)
                        <img
                            src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300" />
                        @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                            </svg>
                        </div>
                        @endif

                        <!-- Badge -->
                        @if($product->is_new)
                        <div class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            New
                        </div>
                        @endif
                    </a>

                    <!-- Product Info -->
                    <div class="p-4">
                        <!-- Product Name -->
                        <a href="{{ route('products.show', $product->id) }}" class="text-gray-900 font-semibold hover:text-blue-600 line-clamp-2">
                            {{ $product->name }}
                        </a>

                        <!-- Category -->
                        @if($product->category)
                        <p class="text-xs text-gray-500 mt-1">{{ $product->category->name }}</p>
                        @endif

                        <!-- Description -->
                        <p class="text-sm text-gray-600 line-clamp-2 mt-2">
                            {{ $product->description }}
                        </p>

                        <!-- Rating -->
                        @if($product->ratings->isNotEmpty())
                        <div class="flex items-center gap-1 mt-3">
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < floor($product->averageRating()))
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    @endif
                                    @endfor
                            </div>
                            <span class="text-xs text-gray-600">({{ $product->ratings->count() }})</span>
                        </div>
                        @endif

                        <!-- Price -->
                        <div class="flex items-baseline gap-2 mt-4">
                            <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @if($product->sale_price)
                            <span class="text-sm line-through text-gray-500">${{ number_format($product->sale_price, 2) }}</span>
                            @endif
                        </div>

                        <!-- Add to Cart Button -->
                        <button
                            type="button"
                            class="w-full mt-4 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-medium"
                            onclick="alert('Add to cart functionality')">
                            Add to Cart
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $results->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
    @else
    <!-- No Results State -->
    <div class="flex flex-col items-center justify-center py-20">
        <svg class="w-24 h-24 text-gray-300 mb-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
        </svg>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">No Products Found</h2>
        <p class="text-gray-600 mb-6 text-center max-w-md">
            @if($query)
            We couldn't find any products matching "<span class="font-semibold">{{ $query }}</span>".
            Try adjusting your search terms or filters.
            @else
            No products available with the selected filters.
            @endif
        </p>

        <div class="flex gap-4">
            <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                Back to Home
            </a>
            <a href="{{ route('search.index') }}" class="inline-block bg-gray-200 text-gray-900 px-6 py-2 rounded-lg hover:bg-gray-300 transition font-medium">
                Clear Filters
            </a>
        </div>

        <!-- Popular Products Fallback -->
        <div class="mt-16 w-full max-w-4xl">
            <h3 class="text-lg font-bold text-gray-900 mb-6 text-center">Popular Products</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($popularProducts ?? [] as $product)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                    <a href="{{ route('products.show', $product->id) }}" class="block">
                        @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded-t-lg" />
                        @else
                        <div class="w-full h-40 bg-gray-200 rounded-t-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                            </svg>
                        </div>
                        @endif
                    </a>
                    <div class="p-3">
                        <h4 class="font-semibold text-gray-900 line-clamp-2 text-sm">{{ $product->name }}</h4>
                        <p class="text-lg font-bold text-blue-600 mt-2">${{ number_format($product->price, 2) }}</p>
                    </div>
                </div>
                @empty
                <p class="col-span-full text-center text-gray-600">No products available</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    function searchFilters() {
        return {
            submitForm() {
                document.getElementById('filter-form').submit();
            },
            resetFilters() {
                // Reset all filters and redirect to search page with only the query
                const query = new URLSearchParams(window.location.search).get('q');
                window.location.href = query ? `/search?q=${encodeURIComponent(query)}` : '/search';
            },
        };
    }
</script>
@endsection
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($results as $product)
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition duration-200">
        <!-- Product Image -->
        <div class="relative overflow-hidden bg-gray-100 aspect-square rounded-t-lg">
            @if($product->image_url)
            <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover hover:scale-105 transition duration-200" />
            @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                </svg>
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="p-4">
            <!-- Product Name -->
            <h2 class="text-lg font-semibold text-gray-900 truncate mb-2">
                <a href="{{ route('products.show', $product->id) }}" class="hover:text-blue-600">
                    {{ $product->name }}
                </a>
            </h2>

            <!-- Category -->
            @if($product->category)
            <p class="text-sm text-gray-500 mb-2">{{ $product->category->name }}</p>
            @endif

            <!-- Description -->
            <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                {{ $product->description }}
            </p>

            <!-- Price -->
            <div class="flex items-center justify-between mb-3">
                <div>
                    <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                    @if($product->sale_price)
                    <span class="text-sm line-through text-gray-500 ml-2">${{ number_format($product->sale_price, 2) }}</span>
                    @endif
                </div>
            </div>

            <!-- Rating (if available) -->
            @if($product->ratings->isNotEmpty())
            <div class="flex items-center mb-3">
                <div class="flex text-yellow-400">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < floor($product->averageRating()))
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @else
                        <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        @endif
                        @endfor
                </div>
                <span class="ml-2 text-sm text-gray-600">({{ $product->ratings->count() }})</span>
            </div>
            @endif

            <!-- Add to Cart Button -->
            <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Add to Cart
            </button>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="mt-12">
    {{ $results->links() }}
</div>
@else
<!-- No Results Message -->
<div class="text-center py-16">
    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
        <path d="M8 16A8 8 0 108 0a8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm5-1a1 1 0 11-2 0 1 1 0 012 0z" />
    </svg>
    <h2 class="text-2xl font-semibold text-gray-900 mb-2">No products found</h2>
    <p class="text-gray-600 mb-6">Try adjusting your search criteria or filters</p>
    <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
        Back to Home
    </a>
</div>