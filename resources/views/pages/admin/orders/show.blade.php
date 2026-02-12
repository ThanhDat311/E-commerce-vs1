<x-admin-layout>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="mt-1 text-sm text-gray-600">Placed on {{ $order->created_at->format('M d, Y \a\t H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.index') }}">
                <x-ui.button variant="secondary">
                    Back to Orders
                </x-ui.button>
            </a>
            @if(in_array($order->order_status, ['pending', 'processing']))
                <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                    @csrf
                    <x-ui.button type="submit" variant="danger">
                        Cancel Order
                    </x-ui.button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
                <div class="space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center gap-4 pb-4 border-b last:border-0">
                            @if($item->product->image_url)
                                <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <span class="text-gray-400 text-xs">No image</span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} each</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between text-lg font-semibold">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order History</h2>
                <div class="space-y-3">
                    @forelse($order->histories as $history)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-2 h-2 mt-2 bg-blue-500 rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $history->action }}</p>
                                <p class="text-sm text-gray-600">{{ $history->description }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $history->created_at->format('M d, Y H:i') }} 
                                    @if($history->user)
                                        by {{ $history->user->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No history available.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Name:</span> {{ $order->first_name }} {{ $order->last_name }}</p>
                    <p><span class="font-medium">Email:</span> {{ $order->email }}</p>
                    <p><span class="font-medium">Phone:</span> {{ $order->phone }}</p>
                    <p><span class="font-medium">Address:</span> {{ $order->address }}</p>
                </div>
            </div>

            <!-- Order Status -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h2>
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                            <select name="payment_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                            <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Note</label>
                            <textarea name="admin_note" rows="3" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $order->admin_note }}</textarea>
                        </div>

                        <x-ui.button type="submit" variant="primary" class="w-full">
                            Update Order
                        </x-ui.button>
                    </div>
                </form>
            </div>

            <!-- Disputes -->
            @if($order->disputes->count() > 0)
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Disputes</h2>
                    @foreach($order->disputes as $dispute)
                        <div class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-sm font-medium">{{ ucfirst($dispute->reason) }}</p>
                            <p class="text-xs text-gray-600 mt-1">Status: {{ ucfirst($dispute->status) }}</p>
                            <a href="{{ route('admin.disputes.show', $dispute) }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block">
                                View Details →
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
