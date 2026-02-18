<x-staff-layout :sidebarType="'staff'">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="text-sm text-gray-600 mt-1">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('staff.orders.index') }}">
                <x-ui.button variant="secondary">
                    Back to Orders
                </x-ui.button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Details (Left Column) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <div class="p-6 flex items-start gap-4">
                            <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                @if($item->product && $item->product->image_url)
                                    <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover object-center">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gray-50">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-medium text-gray-900">{{ $item->product_name }}</h3>
                                <p class="mt-1 text-sm text-gray-500">Price: ${{ number_format($item->price, 2) }}</p>
                                <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-base font-medium text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between text-base font-medium text-gray-900">
                        <p>Total</p>
                        <p>${{ number_format($order->total, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Billing Address</p>
                        <p class="mt-2 text-sm text-gray-900">{{ $order->first_name }} {{ $order->last_name }}</p>
                        <p class="text-sm text-gray-900">{{ $order->email }}</p>
                        <p class="text-sm text-gray-900">{{ $order->phone_number }}</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->address }}</p>
                        <p class="text-sm text-gray-900">{{ $order->city }}, {{ $order->state }} {{ $order->zip_code }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Order History -->
             <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Order History</h2>
                @if($order->histories && $order->histories->count() > 0)
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($order->histories as $history)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">{{ $history->description }}</p>
                                                    <p class="text-xs text-gray-400 mt-0.5">by {{ $history->user->name ?? 'System' }}</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $history->created_at }}">{{ $history->created_at->format('M d, H:i') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No history available for this order.</p>
                @endif
            </div>
        </div>

        <!-- Sidebar (Right Column) -->
        <div class="space-y-6">
            <!-- Order Status -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h2>
                <form action="{{ route('staff.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    
                     <div class="mb-4">
                        <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-1">Tracking Number</label>
                        <input type="text" name="tracking_number" id="tracking_number" value="{{ $order->tracking_number }}" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. TRK123456789">
                    </div>

                    <div class="mb-6">
                        <label for="admin_note" class="block text-sm font-medium text-gray-700 mb-1">Staff Note</label>
                        <textarea name="admin_note" id="admin_note" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Visible only to staff...">{{ $order->admin_note }}</textarea>
                    </div>

                    <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                        Update Order
                    </x-ui.button>
                </form>
            </div>
        </div>
    </div>
</x-staff-layout>
