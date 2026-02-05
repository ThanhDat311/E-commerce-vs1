@extends('layouts.vendor')

@section('title', 'My Orders')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
            <p class="text-gray-500 text-sm mt-1">Manage and track your orders.</p>
        </div>
    </div>

    {{-- Filter Options --}}
    <x-admin.card variant="white">
        <form method="GET" action="{{ route('vendor.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <x-vendor.input-label for="statusFilter" value="Status" />
                <x-vendor.select-input id="statusFilter" name="status">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                </x-vendor.select-input>
            </div>
            <div>
                <x-vendor.input-label for="dateFrom" value="Date From" />
                <x-vendor.text-input type="date" id="dateFrom" name="date_from" value="{{ request('date_from') }}" />
            </div>
            <div>
                <x-vendor.input-label for="dateTo" value="Date To" />
                <x-vendor.text-input type="date" id="dateTo" name="date_to" value="{{ request('date_to') }}" />
            </div>
            <div class="flex items-end">
                <x-admin.button type="submit" variant="primary" class="w-full justify-center" icon="search">
                    Filter Orders
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>

    <x-admin.table>
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items (Yours)</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Your Earnings</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($orders as $order)
                @php
                    $vendorItems = $order->orderItems->filter(function($item) {
                        return $item->product->vendor_id === Auth::id();
                    });
                    $vendorTotal = $vendorItems->sum('total');
                    $itemCount = $vendorItems->sum('quantity');

                    $statusVariant = match($order->order_status) {
                        'completed' => 'success',
                        'processing' => 'info',
                        'shipped' => 'info',
                        'pending' => 'warning',
                        'cancelled' => 'critical',
                        default => 'neutral'
                    };
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-medium text-gray-900">#{{ $order->id }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 mr-2 uppercase">
                                {{ substr($order->first_name ?? $order->user->name ?? 'G', 0, 1) }}
                            </div>
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $order->first_name ?? $order->user->name ?? 'Guest' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <x-admin.badge :variant="$statusVariant">
                            {{ ucfirst($order->order_status) }}
                        </x-admin.badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                        ${{ number_format($vendorTotal, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <x-admin.button variant="ghost" size="sm" :href="route('vendor.orders.show', $order)">
                            View Details
                        </x-admin.button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                            <p>No orders found matching your criteria.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-admin.table>

    @if($orders->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 rounded-lg shadow-sm">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection