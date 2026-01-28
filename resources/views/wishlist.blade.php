@extends('layouts.master')

@section('title', 'My Wishlist - Electro')

@section('content')
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4 text-center">My Wishlist</h1>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if($wishlists->count() > 0)
                <!-- Wishlist Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="border-0">Product</th>
                                <th scope="col" class="border-0">Name</th>
                                <th scope="col" class="border-0">Price</th>
                                <th scope="col" class="border-0">Stock Status</th>
                                <th scope="col" class="border-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wishlists as $wishlist)
                            <tr>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">
                                        @if($wishlist->product->image_url)
                                        <img src="{{ asset('storage/' . $wishlist->product->image_url) }}"
                                            alt="{{ $wishlist->product->name }}"
                                            class="img-fluid rounded"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 80px; height: 80px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="border-0">
                                    <h6 class="mb-0">
                                        <a href="{{ route('product.detail', $wishlist->product) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $wishlist->product->name }}
                                        </a>
                                    </h6>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex flex-column">
                                        <span class="text-primary fw-bold">
                                            ${{ number_format($wishlist->product->sale_price ?? $wishlist->product->price, 2) }}
                                        </span>
                                        @if($wishlist->product->sale_price && $wishlist->product->sale_price < $wishlist->product->price)
                                            <small class="text-muted text-decoration-line-through">
                                                ${{ number_format($wishlist->product->price, 2) }}
                                            </small>
                                            @endif
                                    </div>
                                </td>
                                <td class="border-0">
                                    @if($wishlist->product->stock_quantity > 0)
                                    <span class="badge bg-success">In Stock</span>
                                    @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </td>
                                <td class="border-0">
                                    <div class="d-flex gap-2">
                                        @if($wishlist->product->stock_quantity > 0)
                                        <form method="POST" action="{{ route('cart.add', $wishlist->product->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">
                                                <i class="fas fa-cart-plus me-1"></i>Add to Cart
                                            </button>
                                        </form>
                                        @endif
                                        <form method="POST" action="{{ route('wishlist.remove', $wishlist->product->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Remove this item from wishlist?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Continue Shopping Button -->
                <div class="text-center mt-4">
                    <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                    </a>
                </div>
                @else
                <!-- Empty Wishlist State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-heart-broken text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-muted mb-3">Your wishlist is empty</h3>
                    <p class="text-muted mb-4">
                        Save items you love for later! Start browsing our amazing products and add them to your wishlist.
                    </p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th {
        font-weight: 600;
        color: #495057;
    }

    .btn-warning {
        background-color: #fd7e14;
        border-color: #fd7e14;
    }

    .btn-warning:hover {
        background-color: #e8590c;
        border-color: #e8590c;
    }

    .back-to-top {
        position: fixed;
        bottom: 25px;
        right: 25px;
        display: none;
    }
</style>
@endpush