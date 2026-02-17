<x-vendor-layout :page-title="'Order Management'" :breadcrumbs="['Vendor' => route('vendor.dashboard'), 'Orders' => route('vendor.orders.index')]">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Order Management') }}</h1>
        <div class="flex items-center gap-2">
            <!-- Reserved for potential export/action buttons -->
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="p-6 bg-white border-b border-gray-200">
            
            <!-- Filters -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                <form method="GET" action="{{ route('vendor.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <x-input-label for="status" value="Status" />
                        <select name="status" id="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="date_from" value="From Date" />
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    </div>
                    <div>
                        <x-input-label for="date_to" value="To Date" />
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    </div>
                    <div>
                        <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                            Filter Orders
                        </x-ui.button>
                    </div>
                </form>
            </div>

            <x-ui.table>
                <x-slot:thead>
                    <th scope="col" class="px-6 py-3 font-semibold">Order ID</th>
                    <th scope="col" class="px-6 py-3 font-semibold">Customer</th>
                    <th scope="col" class="px-6 py-3 font-semibold">Total</th>
                    <th scope="col" class="px-6 py-3 font-semibold">Date</th>
                    <th scope="col" class="px-6 py-3 font-semibold">Status</th>
                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">View</span></th>
                </x-slot:thead>
                <x-slot:tbody>
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('vendor.orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->first_name }} {{ $order->last_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">${{ number_format($order->total, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusVariant = match($order->order_status) {
                                        'completed', 'delivered' => 'success',
                                        'processing', 'shipped' => 'info',
                                        'cancelled' => 'danger',
                                        'pending' => 'warning',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusVariant" :dot="true">
                                    {{ ucfirst($order->order_status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('vendor.orders.show', $order->id) }}">
                                    <x-ui.button variant="secondary" size="sm">View</x-ui.button>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No orders found matching criteria.
                            </td>
                        </tr>
                    @endforelse
                </x-slot:tbody>
                <x-slot:footer>
                    <div class="px-4 py-3">
                        {{ $orders->links() }}
                    </div>
                </x-slot:footer>
            </x-ui.table>
        </div>
    </div>
</x-vendor-layout>
