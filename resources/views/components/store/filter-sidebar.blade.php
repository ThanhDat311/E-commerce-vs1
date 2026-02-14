@props(['categories', 'brands' => [], 'priceRange' => ['min' => 0, 'max' => 5000]])

<div class="space-y-6" x-data="{
    minPrice: {{ request('min_price', $priceRange['min']) }},
    maxPrice: {{ request('max_price', $priceRange['max']) }},
    selectedCategories: {{ json_encode(request('category', [])) }},
    selectedBrands: {{ json_encode(request('brands', [])) }},
    selectedRating: {{ request('rating', 0) }},
    applyFilters() {
        const params = new URLSearchParams(window.location.search);
        
        // Handle Categories
        params.delete('category[]'); 
        // Note: For array handling in standard URLSearchParams with Laravel, we might need 'category[]' or just repeat 'category'.
        // Laravel default handles `category=1&category=2`.
        // Let's manually construct query since Alpine/JS formatting can be tricky.
        
        let url = new URL(window.location.href);
        
        // Helper to update array params
        const updateArrayParam = (key, values) => {
            url.searchParams.delete(key);
            url.searchParams.delete(key + '[]');
            if (Array.isArray(values)) {
                values.forEach(v => url.searchParams.append(key + '[]', v)); // Use [] for array
            } else if (values) {
                 url.searchParams.append(key + '[]', values);
            }
        };

        // We can just submit a form, which is easier.
        this.$refs.filterForm.submit();
    }
}">

    <form method="GET" action="{{ route('shop.index') }}" x-ref="filterForm">
        <!-- Preserve other query params like sort, search using hidden inputs is tricky if we iterate all.
             Better to let the logic below handle it or just rely on form submission 
             which automagically handles inputs inside it.
             We need validation that 'sort' is preserved? 
             The form only submits what's inside it. 
             So we should include hidden inputs for 'sort' and 'search' from request.
        -->
        @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif

        <!-- Categories -->
        <div class="border-b border-gray-200 pb-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Categories</h3>
            <div class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar">
                @foreach($categories as $category)
                    <div class="flex items-center">
                        <input id="cat-{{ $category->id }}" name="category[]" value="{{ $category->slug }}" type="checkbox" 
                               {{ in_array($category->slug, (array)request('category', [])) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               onchange="this.form.submit()">
                        <label for="cat-{{ $category->id }}" class="ml-3 text-sm text-gray-600">
                            {{ $category->name }} <span class="text-gray-400">({{ $category->products_count }})</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Price Range -->
        <div class="border-b border-gray-200 py-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Price Range</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between space-x-2">
                    <div class="w-full">
                        <label class="text-xs text-gray-500">Min</label>
                        <input type="number" name="min_price" x-model="minPrice" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="text-gray-400">-</div>
                    <div class="w-full">
                         <label class="text-xs text-gray-500">Max</label>
                        <input type="number" name="max_price" x-model="maxPrice" class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <button type="submit" class="w-full bg-gray-100 text-gray-800 py-1.5 rounded text-xs font-medium hover:bg-gray-200 transition">
                    Apply Price
                </button>
            </div>
        </div>

        <!-- Brands -->
        @if(count($brands) > 0)
        <div class="border-b border-gray-200 py-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Brands</h3>
            <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                @foreach($brands as $brand)
                    <div class="flex items-center">
                        <input id="brand-{{ $brand->id }}" name="brands[]" value="{{ $brand->id }}" type="checkbox"
                               {{ in_array($brand->id, (array)request('brands', [])) ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               onchange="this.form.submit()">
                        <label for="brand-{{ $brand->id }}" class="ml-3 text-sm text-gray-600">
                            {{ $brand->name }} <span class="text-gray-400">({{ $brand->products_count }})</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Rating -->
        <div class="border-b border-gray-200 py-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Rating</h3>
            <div class="space-y-2">
                @foreach([5, 4, 3, 2, 1] as $rating)
                    <div class="flex items-center">
                         <input id="rating-{{ $rating }}" name="rating" value="{{ $rating }}" type="radio"
                               {{ request('rating') == $rating ? 'checked' : '' }}
                               class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                               onchange="this.form.submit()">
                        <label for="rating-{{ $rating }}" class="ml-3 flex items-center text-sm text-gray-600 cursor-pointer">
                            <div class="flex items-center text-yellow-400">
                                @for($i = 0; $i < $rating; $i++)
                                    <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                                @for($j = $rating; $j < 5; $j++)
                                     <svg class="h-4 w-4 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="ml-2">& Up</span>
                        </label>
                    </div>
                @endforeach
            </div>
            
            @if(request()->hasAny(['category', 'brands', 'min_price', 'max_price', 'rating', 'search']))
            <div class="mt-6">
                <a href="{{ route('shop.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 underline">
                    Clear All Filters
                </a>
            </div>
            @endif
        </div>
    </form>
</div>
