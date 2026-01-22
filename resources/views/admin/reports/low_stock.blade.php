@extends('layouts.admin')
@section('title', 'Low Stock Alert')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-danger">Low Stock Alert</h1>
    <p class="text-muted">Products with stock quantity <= 10. <span class="fw-bold">Please restock soon!</span></p>

    <div class="card mb-4 border-danger">
        <div class="card-header bg-danger text-white"><i class="fas fa-exclamation-triangle me-1"></i> Critical Inventory</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Current Stock</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ asset($product->image_url) }}" width="40" class="me-2 rounded">
                                {{ $product->name }}
                            </div>
                        </td>
                        <td>{{ $product->sku }}</td>
                        <td class="fw-bold {{ $product->stock_quantity == 0 ? 'text-danger' : 'text-warning' }}">
                            {{ $product->stock_quantity }}
                        </td>
                        <td>
                            @if($product->stock_quantity == 0)
                                <span class="badge bg-danger">Out of Stock</span>
                            @else
                                <span class="badge bg-warning text-dark">Low Stock</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Restock
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-success"><i class="fas fa-check-circle me-1"></i> Good news! No products are low in stock.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection