<x-staff-layout :pageTitle="'Orders Management'" :breadcrumbs="['Staff' => route('staff.dashboard'), 'Orders' => route('staff.orders.index')]">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <form method="GET" action="{{ route('staff.orders.index') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div>
                <label for="keyword" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Search</label>
                <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}"
                    placeholder="Order ID, Name, Email"
                    class="w-full rounded-lg border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 placeholder-gray-400">
            </div>
            <div>
                <label for="status" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Order Status</label>
                <select name="status" id="status" class="w-full rounded-lg border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label for="payment_status" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Payment Status</label>
                <select name="payment_status" id="payment_status" class="w-full rounded-lg border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                    class="w-full rounded-lg border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div>
                <label for="date_to" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                    class="w-full rounded-lg border-gray-200 text-sm shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <x-ui.button type="submit" variant="primary" class="w-full">
                    Filter
                </x-ui.button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-semibold">Order ID</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Customer</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Total</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Payment</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Date</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-semibold text-blue-600">#{{ $order->id }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-semibold text-slate-600 flex-shrink-0">
                                        {{ strtoupper(substr($order->first_name, 0, 1)) }}{{ strtoupper(substr($order->last_name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-900 truncate">{{ $order->first_name }} {{ $order->last_name }}</div>
                                        <div class="text-xs text-gray-400 truncate">{{ $order->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $paymentVariant = match($order->payment_status) {
                                        'paid' => 'success',
                                        'pending' => 'pending',
                                        'failed' => 'danger',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$paymentVariant" :dot="true">
                                    {{ ucfirst($order->payment_status) }}
                                </x-ui.badge>
                                <div class="text-xs text-gray-400 mt-1">{{ ucfirst($order->payment_method) }}</div>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusVariant = match($order->order_status) {
                                        'completed', 'delivered' => 'success',
                                        'processing', 'shipped' => 'pending',
                                        'cancelled' => 'danger',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusVariant" :dot="true">
                                    {{ ucfirst($order->order_status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-500">
                                {{ $order->created_at->format('M d, Y') }}
                                <div class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('staff.orders.show', $order->id) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors" title="View">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No orders found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $orders->firstItem() }}</span> to <span class="font-medium">{{ $orders->lastItem() }}</span> of <span class="font-bold text-gray-700">{{ $orders->total() }}</span> orders
                </p>
                <div>
                    {{ $orders->links('vendor.pagination.admin') }}
                </div>
            </div>
        @endif
    </div>
</x-staff-layout>
