@props(['categories'])

<div class="space-y-8">
    <!-- Categories -->
    <div>
        <h3 class="text-sm font-medium text-gray-900 border-b pb-2 mb-4">Categories</h3>
        <ul class="space-y-3">
            @foreach($categories as $category)
            <li>
                <a href="{{ request()->fullUrlWithQuery(['category' => $category->slug ?? $category->id]) }}" 
                   class="flex items-center text-sm {{ request('category') == ($category->slug ?? $category->id) ? 'text-indigo-600 font-bold' : 'text-gray-600 hover:text-indigo-600' }}">
                    <span class="w-4 h-4 mr-2 border rounded {{ request('category') == ($category->slug ?? $category->id) ? 'bg-indigo-600 border-indigo-600' : 'border-gray-300' }}"></span>
                    {{ $category->name }}
                    <span class="ml-auto text-gray-400 text-xs">({{ $category->products_count ?? 0 }})</span>
                </a>
                
                @if($category->children->isNotEmpty())
                <ul class="ml-6 mt-2 space-y-2">
                    @foreach($category->children as $child)
                    <li>
                         <a href="{{ request()->fullUrlWithQuery(['category' => $child->slug ?? $child->id]) }}" 
                            class="text-xs {{ request('category') == ($child->slug ?? $child->id) ? 'text-indigo-600 font-bold' : 'text-gray-500 hover:text-indigo-600' }}">
                             {{ $child->name }}
                         </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </div>

    <!-- Price Range -->
    <div x-data="{ min: {{ request('min_price', 0) }}, max: {{ request('max_price', 2000) }} }">
        <h3 class="text-sm font-medium text-gray-900 border-b pb-2 mb-4">Price Range</h3>
        <div class="flex items-center space-x-2 mb-4">
             <input type="number" name="min_price" x-model="min" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" placeholder="Min">
             <span class="text-gray-500">-</span>
             <input type="number" name="max_price" x-model="max" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" placeholder="Max">
        </div>
        <button 
            @click="window.location.href = '{{ request()->url() }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)), min_price: min, max_price: max}).toString()"
            class="w-full bg-gray-900 text-white py-2 px-4 rounded text-sm hover:bg-gray-800 transition"
        >
            Apply Filter
        </button>
    </div>
</div>
