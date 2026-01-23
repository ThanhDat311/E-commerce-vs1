@extends('layouts.vendor')

@section('title', 'My Products')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">My Products</h1>
        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($product->image_url)
                                <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" style="height: 40px; width: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                @else
                                <div style="height: 40px; width: 40px; background: #e9ecef; border-radius: 4px; margin-right: 10px;"></div>
                                @endif
                                <div>
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    @if($product->category)
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $product->sku ?? 'N/A' }}</code></td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            <span class="badge {{ $product->stock_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->stock_quantity }} {{ $product->stock_quantity === 1 ? 'unit' : 'units' }}
                            </span>
                        </td>
                        <td>
                            @if($product->stock_quantity < 10)
                                <span class="badge bg-warning">Low Stock</span>
                                @elseif($product->is_featured)
                                <span class="badge bg-primary">Featured</span>
                                @else
                                <span class="badge bg-secondary">Active</span>
                                @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-outline-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No products yet. <a href="{{ route('vendor.products.create') }}" class="text-decoration-none">Create your first product</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                </div>
                {{ $products->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection