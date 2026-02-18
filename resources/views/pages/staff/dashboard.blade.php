<x-staff-layout>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Orders to Ship -->
        <x-dashboard.stat-card title="Orders to Ship" value="{{ number_format($ordersToShip) }}">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
            </svg>
        </x-dashboard.stat-card>

        <!-- Low Stock Alerts -->
        <x-dashboard.stat-card title="Low Stock Alerts" value="{{ number_format($lowStockCount) }}">
            <div class="{{ $lowStockCount > 0 ? 'text-red-100' : 'text-white' }}">
                 <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </x-dashboard.stat-card>

        <!-- Today's New Orders -->
        <x-dashboard.stat-card title="Today New Orders" value="{{ number_format($todayOrders) }}">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-dashboard.stat-card>
        
        <!-- Pending Refunds/Returns -->
        <x-dashboard.stat-card title="Pending Refunds" value="{{ number_format($pendingReturns) }}">
             <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
        </x-dashboard.stat-card>
    </div>

    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium leading-6 text-gray-900 text-red-600 flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Action Required Orders
            </h3>
        </div>
        <div class="p-6">
            <x-ui.table>
                <x-slot:thead>
                    <th scope="col" class="px-6 py-3">Order ID</th>
                    <th scope="col" class="px-6 py-3">Customer</th>
                    <th scope="col" class="px-6 py-3">Total</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Quick Action</th>
                </x-slot:thead>
                <x-slot:tbody>
                     @forelse($actionRequiredOrders as $order)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                #{{ $order->id }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->user->name ?? 'Guest' }}
                            </td>
                            <td class="px-6 py-4">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $variant = match($order->order_status) {
                                        'completed', 'delivered' => 'success',
                                        'pending' => 'danger', 
                                        'processing' => 'pending',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$variant">
                                    {{ ucfirst($order->order_status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->order_status === 'pending')
                                    <form action="{{ route('staff.orders.updateStatus', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="processing">
                                        <x-ui.button size="sm" variant="primary">Process</x-ui.button>
                                    </form>
                                @elseif($order->order_status === 'processing')
                                    <form action="{{ route('staff.orders.updateStatus', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="shipped">
                                        <x-ui.button size="sm" variant="success">Ship</x-ui.button>
                                    </form>
                                @endif
                                <a href="{{ route('staff.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-900 ml-2 text-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No orders requiring immediate action.
                            </td>
                        </tr>
                    @endforelse
                </x-slot:tbody>
            </x-ui.table>
        </div>
    </div>
</x-staff-layout>
