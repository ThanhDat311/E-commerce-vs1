@props(['product'])

<div x-data="{ 
    activeImage: '{{ $product->image_url ? asset($product->image_url) : asset('img/no-image.png') }}',
    images: [
        '{{ $product->image_url ? asset($product->image_url) : asset('img/no-image.png') }}',
        // Add more images here if available from specifictaions or gallery relation
        // For now, we simulate with placeholders if no gallery
    ] 
}" class="flex flex-col-reverse lg:flex-row gap-4">
    <!-- Thumbnails -->
    <div class="flex lg:flex-col gap-4 overflow-x-auto lg:overflow-y-auto lg:h-[500px] w-full lg:w-24">
        <template x-for="image in images" :key="image">
            <button 
                @click="activeImage = image"
                class="border-2 rounded-lg overflow-hidden w-20 h-20 flex-shrink-0"
                :class="activeImage === image ? 'border-indigo-600' : 'border-transparent hover:border-gray-300'"
            >
                <img :src="image" class="w-full h-full object-cover">
            </button>
        </template>
        
        <!-- Simulation for demo purposes since we might not have gallery yet -->
        @for($i = 1; $i <= 3; $i++)
        <button 
            @click="activeImage = '{{ asset('img/product-'.$i.'.png') }}'"
            class="border-2 rounded-lg overflow-hidden w-20 h-20 flex-shrink-0"
            :class="activeImage === '{{ asset('img/product-'.$i.'.png') }}' ? 'border-indigo-600' : 'border-transparent hover:border-gray-300'"
        >
             <img src="{{ asset('img/product-'.$i.'.png') }}" class="w-full h-full object-cover">
        </button>
        @endfor
    </div>

    <!-- Main Image -->
    <div class="flex-1 border border-gray-100 rounded-lg overflow-hidden bg-gray-50 relative group">
        <img :src="activeImage" alt="{{ $product->name }}" class="w-full h-full object-contain max-h-[500px] transition-all duration-300 group-hover:scale-105">
        
        @if($product->is_new)
            <span class="absolute top-4 left-4 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full">NEW</span>
        @endif
        @if($product->discount_price)
            <span class="absolute top-4 right-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
            </span>
        @endif
    </div>
</div>
