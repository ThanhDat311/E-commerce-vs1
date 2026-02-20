@props(['category'])

<a href="#" class="group block text-center">
    <div class="relative w-20 h-20 sm:w-28 sm:h-28 mx-auto rounded-full overflow-hidden bg-gray-100 group-hover:ring-4 ring-blue-100 transition duration-300">
        @if($category->image_url)
            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
        @else
             <div class="w-full h-full flex items-center justify-center text-blue-500 bg-blue-50">
                <svg class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
             </div>
        @endif
    </div>
    <h3 class="mt-3 text-sm font-medium text-gray-900 group-hover:text-blue-600">
        {{ $category->name }}
    </h3>
</a>
