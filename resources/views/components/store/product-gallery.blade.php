@props(['product'])

<div x-data="{ 
    activeImage: '{{ $product->image_url ? asset($product->image_url) : asset('img/no-image.png') }}',
    images: [
        '{{ $product->image_url ? asset($product->image_url) : asset('img/no-image.png') }}',
        // In a real scenario, this would loop through a gallery relationship
        '{{ asset('img/product-1.png') }}',
        '{{ asset('img/product-2.png') }}',
        '{{ asset('img/product-3.png') }}'
    ] 
}" class="flex flex-col-reverse lg:flex-row gap-4">
    <!-- Thumbnails -->
    <div class="flex lg:flex-col gap-4 overflow-x-auto lg:overflow-y-auto lg:h-[500px] w-full lg:w-24 px-1 pb-2 lg:pb-0 scrollbar-hide">
        <template x-for="image in images" :key="image">
            <button 
                @click="activeImage = image"
                class="border-2 rounded-xl overflow-hidden w-20 h-20 flex-shrink-0 transition-all duration-200"
                :class="activeImage === image ? 'border-indigo-600 ring-2 ring-indigo-100 scale-95' : 'border-gray-200 hover:border-gray-300'"
            >
                <img :src="image" class="w-full h-full object-cover">
            </button>
        </template>
    </div>

    <!-- Main Image -->
    <div class="flex-1 bg-white border border-gray-100 rounded-2xl overflow-hidden relative group shadow-sm">
        <div class="aspect-w-1 aspect-h-1 w-full h-full flex items-center justify-center p-4">
             <img :src="activeImage" :alt="'{{ $product->name }}'" class="w-full h-full object-contain max-h-[500px] transition-transform duration-500 group-hover:scale-105">
        </div>
       
        <!-- Badges -->
        <div class="absolute top-4 left-4 flex flex-col gap-2">
            @if($product->is_new)
                <span class="bg-blue-600/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">NEW ARRIVAL</span>
            @endif
            @if($product->sold_count > 100)
                <span class="bg-amber-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">BEST SELLER</span>
            @endif
        </div>

        @if($product->discount_price)
            <div class="absolute top-4 right-4">
                <span class="bg-red-500 text-white text-sm font-bold px-3 py-1.5 rounded-full shadow-md animate-pulse">
                    -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                </span>
            </div>
        @endif
        
        <!-- Zoom hints or actions could go here -->
    </div>
</div>
