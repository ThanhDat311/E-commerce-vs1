<x-admin-layout>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Orders Management</h1>
        <a href="{{ route('admin.orders.export', request()->all()) }}">
            <x-ui.button variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </x-ui.button>
        </a>
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

    @if(session('info'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
            {{ session('info') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="mb-6 bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div>
                <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}" 
                    placeholder="Order ID, Name, Email" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                <select name="payment_status" id="payment_status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <x-ui.button type="submit" variant="primary" class="w-full">
                    Filter
                </x-ui.button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="p-6">
            <x-ui.table>
                <x-slot:thead>
                    <th scope="col" class="px-6 py-3">Order ID</th>
                    <th scope="col" class="px-6 py-3">Customer</th>
                    <th scope="col" class="px-6 py-3">Total</th>
                    <th scope="col" class="px-6 py-3">Payment</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </x-slot:thead>
                <x-slot:tbody>
                    @forelse($orders as $order)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $order->first_name }} {{ $order->last_name }}</div>
                                    <div class="text-gray-500">{{ $order->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $paymentVariant = match($order->payment_status) {
                                        'paid' => 'success',
                                        'pending' => 'pending',
                                        'failed' => 'danger',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$paymentVariant">
                                    {{ ucfirst($order->payment_status) }}
                                </x-ui.badge>
                                <div class="text-xs text-gray-500 mt-1">{{ ucfirst($order->payment_method) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusVariant = match($order->order_status) {
                                        'completed', 'delivered' => 'success',
                                        'processing', 'shipped' => 'pending',
                                        'cancelled' => 'danger',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusVariant">
                                    {{ ucfirst($order->order_status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $order->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}">
                                        <x-ui.button variant="secondary" size="sm">
                                            View
                                        </x-ui.button>
                                    </a>
                                    @if(in_array($order->order_status, ['cancelled', 'pending']))
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button type="submit" variant="danger" size="sm">
                                                Delete
                                            </x-ui.button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </x-slot:tbody>
            </x-ui.table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
