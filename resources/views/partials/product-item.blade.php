<div class="product-item rounded h-100 d-flex flex-column">
    <div class="product-item-inner border rounded flex-grow-1 d-flex flex-column">
        <div class="product-item-inner-item position-relative">

            {{-- 1. ẢNH SẢN PHẨM --}}
            <div class="overflow-hidden rounded-top">
                <a href="{{ route('product.detail', $product) }}">
                    <img src="{{ asset($product->image_url ?? 'img/default.png') }}"
                        class="img-fluid w-100 rounded-top"
                        style="height: 230px; object-fit: cover;"
                        alt="{{ $product->name }}">
                </a>
            </div>

            {{-- 2. Nhãn New --}}
            @if($product->is_new)
            <div class="bg-secondary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">New</div>
            @endif

            {{-- 3. Nút xem nhanh --}}
            <div class="product-details position-absolute end-0 top-0">
                <a href="{{ route('product.detail', $product) }}" class="btn btn-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <i class="fa fa-eye text-white"></i>
                </a>
            </div>
        </div>

        {{-- 4. Thông tin sản phẩm --}}
        <div class="text-center p-4">
            <a href="#" class="d-block mb-2 text-muted small text-uppercase">
                {{ $product->category->name ?? 'Electronics' }}
            </a>

            <a href="{{ route('product.detail', $product) }}" class="d-block h5 mb-2 text-dark fw-bold text-decoration-none">
                {{ $product->name }}
            </a>

            <div class="d-flex justify-content-center align-items-center">
                <span class="text-primary fs-5 fw-bold">${{ number_format($product->price) }}</span>
                @if($product->sale_price)
                <span class="text-decoration-line-through text-muted ms-2 small">${{ number_format($product->sale_price) }}</span>
                @endif
            </div>
        </div>
    </div>

    {{-- 5. Nút Add to Cart & Wishlist --}}
    <div class="product-item-add border border-top-0 rounded-bottom text-center p-4 bg-white mt-auto">

        {{-- Flex container để 2 nút nằm ngang hàng --}}
        <div class="d-flex justify-content-center align-items-center mb-4">

            {{-- Nút Add To Cart --}}
            <a href="{{ route('cart.add', ['id' => $product->id]) }}" class="btn btn-primary border-secondary rounded-pill py-2 px-4 text-white d-flex align-items-center">
                <i class="fas fa-shopping-cart me-2"></i> Add To Cart
            </a>

            {{-- Nút Wishlist (Chỉ hiện khi đã đăng nhập) --}}
            @auth
            @php
            // Kiểm tra xem sản phẩm đã có trong wishlist chưa
            // Lưu ý: Đảm bảo bạn đã tạo Model Wishlist và migration tương ứng
            $isInWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();
            @endphp

            <div class="ms-2">
                <form method="POST" action="{{ route('wishlist.toggle') }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit"
                        class="btn {{ $isInWishlist ? 'btn-danger' : 'btn-outline-danger' }} rounded-circle p-0 d-flex align-items-center justify-content-center border-secondary"
                        style="width: 40px; height: 40px;"
                        title="{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                        <i class="fa fa-heart {{ $isInWishlist ? 'text-white' : 'text-danger' }}"></i>
                    </button>
                </form>
            </div>
            @endauth
        </div>

        {{-- Phần đánh giá (Rating) --}}
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex text-secondary small">
                @php $rating = 5; @endphp
                @for($i = 0; $i < 5; $i++)
                    @if($i < $rating)
                    <i class="fas fa-star text-primary"></i>
                    @else
                    <i class="fas fa-star"></i>
                    @endif
                    @endfor
            </div>
        </div>
    </div>
</div>