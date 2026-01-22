@extends('layouts.master')

@section('title', 'Order Detail #' . $order->id)

@section('content')
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Order Detail</h1>
    <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">My Orders</a></li>
        <li class="breadcrumb-item active text-white">Order #{{ $order->id }}</li>
    </ol>
</div>

<div class="container-fluid py-5">
    <div class="container py-5">
        {{-- Order Status Alert --}}
        @if($order->order_status === 'pending')
            <div class="alert alert-warning mb-4">
                <i class="fas fa-clock me-2"></i>
                <strong>Order Status:</strong> Your order is pending and will be processed soon.
            </div>
        @elseif($order->order_status === 'processing')
            <div class="alert alert-info mb-4">
                <i class="fas fa-cog me-2"></i>
                <strong>Order Status:</strong> Your order is being processed.
            </div>
        @elseif($order->order_status === 'shipped')
            <div class="alert alert-primary mb-4">
                <i class="fas fa-shipping-fast me-2"></i>
                <strong>Order Status:</strong> Your order has been shipped.
            </div>
        @elseif($order->order_status === 'completed')
            <div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Order Status:</strong> Your order has been completed. Thank you for your purchase!
            </div>
        @elseif($order->order_status === 'cancelled')
            <div class="alert alert-danger mb-4">
                <i class="fas fa-times-circle me-2"></i>
                <strong>Order Status:</strong> Your order has been cancelled.
            </div>
        @endif

        <div class="row">
            {{-- Order Items --}}
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0 fw-bold text-white">
                            <i class="fas fa-shopping-cart me-2"></i>Order Items
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image_url)
                                                    <img src="{{ asset($item->product->image_url) }}" 
                                                         alt="{{ $item->product_name }}" 
                                                         class="me-3 rounded" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <img src="{{ asset('img/no-image.png') }}" 
                                                         alt="No image" 
                                                         class="me-3 rounded" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                    @if($item->product)
                                                        <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold fs-5">Grand Total:</td>
                                        <td class="text-end fw-bold text-danger fs-5">${{ number_format($order->total, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Order History --}}
                @if($order->histories && $order->histories->count() > 0)
                <div class="card border-0 shadow-sm rounded-3 mt-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-history me-2"></i>Order History
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="timeline">
                            @foreach($order->histories->sortByDesc('created_at') as $history)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $history->action }}</h6>
                                    <p class="mb-1 text-muted small">{{ $history->description }}</p>
                                    <small class="text-muted">{{ $history->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Order Information Sidebar --}}
            <div class="col-lg-4">
                {{-- Order Summary --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>Order Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Order ID</strong>
                            <span class="fs-5 fw-bold">#{{ $order->id }}</span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Order Date</strong>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Status</strong>
                            <span class="badge 
                                @if($order->order_status === 'pending') bg-warning
                                @elseif($order->order_status === 'processing') bg-info
                                @elseif($order->order_status === 'shipped') bg-primary
                                @elseif($order->order_status === 'completed') bg-success
                                @elseif($order->order_status === 'cancelled') bg-danger
                                @else bg-secondary
                                @endif fs-6">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Payment Method</strong>
                            <span class="badge bg-secondary">{{ strtoupper($order->payment_method ?? 'N/A') }}</span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Payment Status</strong>
                            <span class="badge 
                                @if($order->payment_status === 'paid') bg-success
                                @else bg-warning
                                @endif">
                                {{ ucfirst($order->payment_status ?? 'Unpaid') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Shipping Information --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-truck me-2"></i>Shipping Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Recipient Name</strong>
                            <span>{{ $order->first_name }} {{ $order->last_name }}</span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Email</strong>
                            <span>{{ $order->email }}</span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Phone</strong>
                            <span>{{ $order->phone }}</span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Delivery Address</strong>
                            <span>{{ $order->address }}</span>
                        </div>
                        @if($order->note)
                        <div class="mb-3">
                            <strong class="text-muted d-block mb-1">Note</strong>
                            <span class="text-muted">{{ $order->note }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-grid gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Orders
                    </a>
                    @if($order->order_status === 'pending')
                    <button type="button" class="btn btn-outline-danger" onclick="confirmCancel()">
                        <i class="fas fa-times me-2"></i>Cancel Order
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->order_status === 'pending')
<script>
function confirmCancel() {
    if (confirm('Are you sure you want to cancel this order?')) {
        // TODO: Implement cancel order functionality
        alert('Cancel order functionality will be implemented soon.');
    }
}
</script>
@endif
@endsection
