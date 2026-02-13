<x-admin-layout :pageTitle="'Dashboard'" :breadcrumbs="['Admin' => route('admin.dashboard')]">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Welcome back, {{ Auth::user()->name ?? 'Admin' }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total Revenue -->
        <x-dashboard.stat-card title="Total Revenue" value="${{ number_format($totalRevenue, 2) }}">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-dashboard.stat-card>

        <!-- Active Users -->
        <x-dashboard.stat-card title="Active Users" value="{{ number_format($activeUsers) }}">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </x-dashboard.stat-card>

        <!-- Pending Orders -->
        <x-dashboard.stat-card title="Pending Orders" value="{{ number_format($pendingOrders) }}">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-dashboard.stat-card>

        <!-- Avg Risk Score -->
        <x-dashboard.stat-card title="Avg Risk Score" value="{{ number_format($avgRiskScore * 100, 1) }}%">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </x-dashboard.stat-card>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-base font-semibold text-gray-900">Latest Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-semibold">Order ID</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Customer</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Total</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                     @forelse($latestOrders as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-semibold text-blue-600">#{{ $order->id }}</span>
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-900">
                                {{ $order->user->name ?? 'Guest' }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $variant = match($order->order_status) {
                                        'completed', 'delivered' => 'success',
                                        'pending', 'processing' => 'warning',
                                        'cancelled' => 'danger',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$variant" :dot="true">
                                    {{ ucfirst($order->order_status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-5 py-4 text-gray-500">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
