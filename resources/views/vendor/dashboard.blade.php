@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="container-fluid">
    {{-- Vendor Dashboard Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Dashboard</h1>
        <span class="badge bg-primary">Vendor Portal</span>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary text-uppercase mb-1">Total Products</div>
                    <div class="h3 mb-0">{{ auth()->user()->products()->count() }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success text-uppercase mb-1">Total Orders</div>
                    <div class="h3 mb-0">{{ auth()->user()->products()->with('orderItems')->count() }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info text-uppercase mb-1">Revenue</div>
                    <div class="h3 mb-0">$0.00</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning text-uppercase mb-1">Low Stock</div>
                    <div class="h3 mb-0">{{ auth()->user()->products()->where('stock_quantity', '<', 10)->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                    <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-eye"></i> View All Products
                    </a>
                    <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-info">
                        <i class="fas fa-shopping-cart"></i> View Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Orders will be shown here --}}
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No orders yet. Your vendor account is ready!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection