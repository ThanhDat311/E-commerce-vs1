@extends('layouts.admin')
@section('title', 'Top Selling Products')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Top Selling Products</h1>
    <p class="text-muted">Top 20 products by quantity sold.</p>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-trophy me-1 text-warning"></i> Best Sellers</div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Sold Qty</th>
                        <th>Total Revenue Generated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($item->product)
                                    <img src="{{ asset($item->product->image_url) }}" width="40" class="me-2 rounded">
                                    {{ $item->product->name }}
                                @else
                                    <span class="text-danger">Product Deleted (ID: {{ $item->product_id }})</span>
                                @endif
                            </div>
                        </td>
                        <td>{{ $item->product->category->name ?? 'N/A' }}</td>
                        <td class="fw-bold fs-5">{{ $item->total_sold }}</td>
                        <td class="text-success">${{ number_format($item->total_revenue, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">No sales data available yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection