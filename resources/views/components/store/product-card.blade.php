@props(['product'])

<div class="group relative bg-white border border-gray-100 rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <!-- Image Aspect Ratio -->
    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-80 relative">
         @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center lg:h-full lg:w-full">
        @else
            <div class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-400">
                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        @endif

        @if($product->is_new)
            <span class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded">NEW</span>
        @endif
        @if($product->discount_price)
            <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">
                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
            </span>
        @endif

        <!-- Wishlist Heart Icon -->
        @auth
            @php
                $inWishlist = auth()->user()->hasInWishlist($product->id);
            @endphp
            <button 
                onclick="toggleWishlist({{ $product->id }}, this)"
                class="absolute {{ $product->discount_price ? 'top-12' : 'top-2' }} right-2 p-2 bg-white rounded-full shadow-md hover:shadow-lg transition-all duration-200 group/heart z-10"
                title="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}"
                data-in-wishlist="{{ $inWishlist ? 'true' : 'false' }}"
            >
                <svg class="h-5 w-5 transition-all duration-200 {{ $inWishlist ? 'fill-red-500 text-red-500' : 'fill-none text-gray-400' }} group-hover/heart:scale-110" 
                     viewBox="0 0 24 24" 
                     stroke="currentColor" 
                     stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>
        @else
            <a 
                href="{{ route('login') }}"
                class="absolute {{ $product->discount_price ? 'top-12' : 'top-2' }} right-2 p-2 bg-white rounded-full shadow-md hover:shadow-lg transition-all duration-200 group/heart z-10"
                title="Login to add to wishlist"
            >
                <svg class="h-5 w-5 fill-none text-gray-400 group-hover/heart:scale-110 transition-transform duration-200" 
                     viewBox="0 0 24 24" 
                     stroke="currentColor" 
                     stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </a>
        @endauth

        <!-- Quick Add Button (Visible on Hover) -->
        <div class="absolute bottom-4 left-0 right-0 px-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 hidden sm:block">
            <button class="w-full bg-black text-white py-2 rounded font-medium hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                Add to Cart
            </button>
        </div>
    </div>

    <div class="mt-4 px-4 pb-4">
        <h3 class="text-sm text-gray-700 font-medium truncate">
            <a href="{{ route('shop.show', $product->slug) }}">
                <span aria-hidden="true" class="absolute inset-0"></span>
                {{ $product->name }}
            </a>
        </h3>
        
        <!-- Rating & Sold Count -->
        <div class="flex items-center justify-between mt-1">
            <div class="flex items-center">
                 @php $rating = $product->ratings_avg_rating ?? 0; @endphp
                 @for($i = 0; $i < 5; $i++)
                    <svg class="h-4 w-4 {{ $i < round($rating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
                <span class="text-xs text-gray-500 ml-1">({{ $product->reviews_count ?? 0 }})</span> 
            </div>
            @if(isset($product->sold_count))
                <div class="text-xs text-green-600 font-semibold bg-green-50 px-2 py-0.5 rounded-full">
                    {{ $product->sold_count }}+ Sold
                </div>
            @else
                 <!-- Placeholder if column doesn't exist, randomly show something or hide -->
                 <div class="text-xs text-green-600 font-semibold bg-green-50 px-2 py-0.5 rounded-full">
                    {{ rand(10, 500) }}+ Sold
                </div>
            @endif
        </div>

        <div class="mt-2 flex items-center justify-between">
            <div>
                 @if($product->discount_price)
                    <p class="text-lg font-bold text-gray-900">${{ number_format($product->discount_price, 2) }}</p>
                    <p class="text-sm text-gray-500 line-through">${{ number_format($product->price, 2) }}</p>
                @else
                    <p class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>
                @endif
            </div>
             <!-- Mobile Quick Add -->
             <button class="sm:hidden p-2 rounded-full bg-gray-100 text-gray-500 hover:bg-black hover:text-white transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </button>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
function toggleWishlist(productId, button) {
    // Prevent multiple clicks
    if (button.disabled) return;
    button.disabled = true;
    
    const svg = button.querySelector('svg');
    const currentState = button.dataset.inWishlist === 'true';
    
    // Add loading animation
    button.classList.add('animate-pulse');
    
    fetch('{{ route("wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button state
            button.dataset.inWishlist = data.inWishlist ? 'true' : 'false';
            button.title = data.inWishlist ? 'Remove from wishlist' : 'Add to wishlist';
            
            // Update heart icon
            if (data.inWishlist) {
                svg.classList.remove('fill-none', 'text-gray-400');
                svg.classList.add('fill-red-500', 'text-red-500');
            } else {
                svg.classList.remove('fill-red-500', 'text-red-500');
                svg.classList.add('fill-none', 'text-gray-400');
            }
            
            // Add success animation
            svg.classList.add('scale-125');
            setTimeout(() => {
                svg.classList.remove('scale-125');
            }, 200);
            
            // Show toast notification (optional)
            window.showToast(data.message, 'success');
        }
    })
    .catch(error => {
        console.error('Error toggling wishlist:', error);
        // Show error notification
        window.showToast('Failed to update wishlist. Please try again.', 'error');
    })
    .finally(() => {
        button.disabled = false;
        button.classList.remove('animate-pulse');
    });
}
</script>
@endpush
@endonce
