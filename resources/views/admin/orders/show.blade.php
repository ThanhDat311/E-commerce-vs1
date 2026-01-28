@extends('admin.layout.admin')

@section('title', 'Order Detail')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-start justify-between">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-admin-primary hover:text-blue-700 text-sm font-semibold mb-4">
                <i class="fas fa-chevron-left"></i> Back to Orders
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Order #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h1>
            <div class="flex items-center gap-4 mt-3">
                @php
                $paymentStatusConfig = [
                'pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Pending'],
                'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Paid'],
                'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Failed'],
                ];
                $paymentConfig = $paymentStatusConfig[$order->payment_status] ?? $paymentStatusConfig['pending'];
                @endphp
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{ $paymentConfig['bg'] }} {{ $paymentConfig['text'] }}">
                    <i class="fas fa-check-circle"></i> {{ $paymentConfig['label'] }}
                </span>
                <span class="text-gray-600 text-sm">{{ $order->created_at->format('M d, Y \a\t H:i A') }}</span>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors text-sm" title="Resend Confirmation">
                <i class="fas fa-envelope"></i> Resend
            </button>
            <button class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors text-sm" title="Print Invoice">
                <i class="fas fa-print"></i> Print
            </button>
            <button class="inline-flex items-center gap-2 px-4 py-2.5 bg-admin-primary hover:bg-blue-700 rounded-lg text-white font-semibold transition-colors text-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                <i class="fas fa-edit"></i> Update Status
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left Column) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900">Order Items</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Product</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase">QTY</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase">Unit Price</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative w-12 h-12 rounded-lg overflow-hidden border border-gray-200">
                                            <img src="{{ asset($item->product->image_url ?? 'img/no-image.png') }}"
                                                alt="{{ $item->product->name }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-gray-900 text-sm">{{ $item->product->name }}</div>
                                            <div class="text-xs text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-semibold text-gray-900">{{ $item->quantity }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-700">${{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment Information Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900"><i class="fas fa-credit-card mr-2 text-admin-primary"></i>Payment Information</h3>
                </div>
                <div class="p-6 grid grid-cols-2 gap-6">
                    <div>
                        <div class="text-xs font-bold text-gray-600 uppercase mb-2">Payment Method</div>
                        <div class="text-gray-900 font-semibold flex items-center gap-2">
                            @php
                            $methodDisplay = ucwords(str_replace('_', ' ', $order->payment_method ?? 'COD'));
                            $methodIcon = match($order->payment_method) {
                            'credit_card' => 'fas fa-credit-card',
                            'paypal' => 'fab fa-paypal',
                            'bank_transfer' => 'fas fa-building',
                            'cod' => 'fas fa-truck',
                            default => 'fas fa-money-bill'
                            };
                            @endphp
                            <i class="{{ $methodIcon }}"></i>{{ $methodDisplay }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-600 uppercase mb-2">Transaction ID</div>
                        <div class="text-gray-900 font-semibold font-mono">#TXN-{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-600 uppercase mb-2">Payment Status</div>
                        <div>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <i class="fas fa-check-circle"></i> Authorized
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-600 uppercase mb-2">Currency</div>
                        <div class="text-gray-900 font-semibold">USD - US Dollar</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (Right Column) -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Customer Information Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900"><i class="fas fa-user mr-2 text-admin-primary"></i>Customer</h3>
                    <a href="#" class="text-admin-primary hover:text-blue-700 text-sm font-semibold">Edit</a>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Customer Avatar & Name -->
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-admin-primary text-white rounded-full flex items-center justify-center font-bold text-2xl">
                            {{ substr($order->first_name, 0, 1) }}{{ substr($order->last_name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">{{ $order->first_name }} {{ $order->last_name }}</div>
                            <div class="text-sm text-gray-600">Customer since {{ $order->user ? $order->user->created_at->format('Y') : 'N/A' }}</div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-gray-400 w-5"></i>
                            <a href="mailto:{{ $order->email }}" class="text-admin-primary hover:text-blue-700 text-sm">
                                {{ $order->email }}
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <i class="fas fa-phone text-gray-400 w-5"></i>
                        <a href="tel:{{ $order->phone }}" class="text-admin-primary hover:text-blue-700 text-sm">
                            {{ $order->phone }}
                        </a>
                    </div>

                    <!-- Shipping Address -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="text-xs font-bold text-gray-600 uppercase mb-2 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-indigo-600"></i> Shipping Address
                        </div>
                        <div class="bg-gray-50 p-3 rounded text-sm text-gray-700 leading-relaxed">
                            {{ $order->address }}<br>
                            @if($order->note)
                            <em class="text-gray-600 block mt-2">{{ $order->note }}</em>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-gray-900"><i class="fas fa-calculator mr-2 text-admin-primary"></i>Order Summary</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-gray-900">${{ number_format($order->total * 0.9, 2) }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Tax (8%)</span>
                        <span class="font-semibold text-gray-900">${{ number_format($order->total * 0.08, 2) }}</span>
                    </div>
                    <div class="flex justify-between pb-4 border-b border-gray-200">
                        <span class="text-gray-600">Shipping (Express)</span>
                        <span class="font-semibold text-gray-900">$15.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-900">Total</span>
                        <span class="text-2xl font-bold text-admin-primary">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Danger Zone Card -->
            <div class="bg-white rounded-xl shadow-md border border-red-200 overflow-hidden">
                <div class="bg-red-50 border-b border-red-200 px-6 py-4">
                    <h3 class="text-lg font-bold text-red-700"><i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone</h3>
                </div>
                <div class="p-6 space-y-3">
                    <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 border border-red-300 rounded-lg text-red-700 hover:bg-red-100 font-semibold transition-colors text-sm">
                        <i class="fas fa-undo"></i> Refund Order
                    </button>
                    @if($order->order_status != 'cancelled')
                    <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 rounded-lg text-white font-semibold transition-colors text-sm" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                        <i class="fas fa-times"></i> Cancel Order
                    </button>
                    @else
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i> This order is cancelled
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border border-gray-200 rounded-xl shadow-lg">
            <div class="modal-header border-b border-gray-200 py-4 px-6">
                <h5 class="modal-title font-bold text-gray-900 text-lg" id="updateStatusLabel">
                    <i class="fas fa-edit mr-2 text-admin-primary"></i>Update Order Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Select New Status</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm" required>
                            <option value="">-- Choose Status --</option>
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>
                                Processing
                            </option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>
                                Shipped
                            </option>
                            <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Admin Note (Optional)</label>
                        <textarea name="admin_note" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm" rows="3"
                            placeholder="Add notes about this status change..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-t border-gray-200 px-6 py-4 flex gap-2">
                    <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors text-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-white font-semibold transition-colors text-sm">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border border-gray-200 rounded-xl shadow-lg">
            <div class="modal-header border-b border-gray-200 px-6 py-4">
                <h5 class="modal-title font-bold text-gray-900 text-lg" id="cancelOrderLabel">
                    <i class="fas fa-exclamation-circle mr-2 text-red-600"></i>Cancel Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-6 py-6 space-y-4">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-700 text-sm font-semibold flex items-start gap-2">
                        <i class="fas fa-exclamation-triangle mt-0.5"></i>
                        <span><strong>Warning:</strong> Cancelling this order is irreversible. The customer will be notified.</span>
                    </p>
                </div>
                <p class="text-gray-600 text-sm">Are you sure you want to cancel order <strong>#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong>?</p>
            </div>
            <div class="modal-footer border-t border-gray-200 px-6 py-4 flex gap-2">
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors text-sm" data-bs-dismiss="modal">Keep Order</button>
                <button type="button" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white font-semibold transition-colors text-sm" onclick="cancelOrder()">
                    <i class="fas fa-check mr-2"></i>Yes, Cancel Order
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function cancelOrder() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.orders.update", $order->id) }}';
        form.innerHTML = `
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name="status" value="cancelled">
    `;
        document.body.appendChild(form);
        form.submit();
    }
</script>

<style>
    .text-gray-900 {
        color: #212529;
    }

    .text-gray-700 {
        color: #6c757d;
    }

    .bg-light {
        background-color: #f8f9fa;
    }

    .fw-600 {
        font-weight: 600;
    }

    .fw-500 {
        font-weight: 500;
    }

    .font-monospace {
        font-family: 'Monaco', 'Courier New', monospace;
    }

    .avatar-lg {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
</style>
@endsection