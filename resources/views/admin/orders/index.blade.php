@extends('layouts.admin')
@section('title', 'Order Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order Management</h1>
            <p class="text-gray-600 mt-2">Manage and track your customer orders in real-time.</p>
        </div>
        <button class="inline-flex items-center gap-2 px-6 py-3 bg-admin-primary hover:bg-blue-700 rounded-lg text-white font-semibold transition-colors shadow-md">
            <i class="fas fa-download"></i> Export CSV
        </button>
    </div>

    <!-- Status Tabs Navigation -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-0 overflow-hidden">
        <nav class="flex items-center border-b border-gray-200 px-6" role="tablist">
            <a href="#" class="flex items-center gap-2 px-4 py-4 border-b-3 border-admin-primary text-admin-primary font-semibold text-sm transition-colors nav-tab-active" role="tab">
                <i class="fas fa-list text-lg"></i>
                <span>All Orders</span>
                <span class="bg-gray-100 text-gray-900 rounded-full px-3 py-1 text-xs font-semibold ml-2">{{ $orders->total() }}</span>
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-4 border-b-3 border-transparent text-gray-600 hover:text-gray-900 font-semibold text-sm transition-colors nav-tab" role="tab">
                <i class="fas fa-hourglass-half text-lg"></i>
                <span>Processing</span>
                <span class="bg-blue-100 text-blue-700 rounded-full px-3 py-1 text-xs font-semibold ml-2">42</span>
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-4 border-b-3 border-transparent text-gray-600 hover:text-gray-900 font-semibold text-sm transition-colors nav-tab" role="tab">
                <i class="fas fa-truck text-lg"></i>
                <span>Shipping</span>
                <span class="bg-yellow-100 text-yellow-700 rounded-full px-3 py-1 text-xs font-semibold ml-2">128</span>
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-4 border-b-3 border-transparent text-gray-600 hover:text-gray-900 font-semibold text-sm transition-colors nav-tab" role="tab">
                <i class="fas fa-check-circle text-lg"></i>
                <span>Completed</span>
                <span class="bg-green-100 text-green-700 rounded-full px-3 py-1 text-xs font-semibold ml-2">1,012</span>
            </a>
            <a href="#" class="flex items-center gap-2 px-4 py-4 border-b-3 border-transparent text-gray-600 hover:text-gray-900 font-semibold text-sm transition-colors nav-tab" role="tab">
                <i class="fas fa-times-circle text-lg"></i>
                <span>Cancelled</span>
                <span class="bg-red-100 text-red-700 rounded-full px-3 py-1 text-xs font-semibold ml-2">58</span>
            </a>
        </nav>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex items-end gap-4 flex-wrap">
            <!-- Search Input -->
            <div class="flex-1 min-w-64">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                    <input type="text" name="keyword" class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm"
                        placeholder="Search Order ID or Customer name..." value="{{ request('keyword') }}">
                </div>
            </div>

            <!-- Date Range Picker -->
            <div class="flex items-end gap-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">From</label>
                    <input type="date" name="date_from" class="px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm"
                        value="{{ request('date_from') }}">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">To</label>
                    <input type="date" name="date_to" class="px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm"
                        value="{{ request('date_to') }}">
                </div>
            </div>

            <!-- Payment Method Filter -->
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Payment Method</label>
                <select name="payment_method" class="px-3 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all text-sm">
                    <option value="">All Methods</option>
                    <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="paypal" {{ request('payment_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                </select>
            </div>

            <!-- Filter Button -->
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-admin-primary hover:bg-blue-700 rounded-lg text-white font-semibold transition-colors shadow-sm text-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">Total Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-admin-primary hover:text-blue-700 font-semibold text-sm">
                                #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                    {{ substr($order->first_name, 0, 1) }}{{ substr($order->last_name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 text-sm">{{ $order->first_name }} {{ $order->last_name }}</div>
                                    <div class="text-gray-500 text-xs truncate">{{ $order->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-semibold">{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-gray-900 text-sm">${{ number_format($order->total, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                            $paymentIcons = [
                            'credit_card' => 'fas fa-credit-card',
                            'paypal' => 'fab fa-paypal',
                            'bank_transfer' => 'fas fa-building',
                            'cod' => 'fas fa-dollar-sign',
                            ];
                            $iconClass = $paymentIcons[$order->payment_method] ?? 'fas fa-money-bill';
                            $methodDisplay = ucwords(str_replace('_', ' ', $order->payment_method ?? 'COD'));
                            @endphp
                            <div class="flex items-center gap-2 text-gray-700">
                                <i class="{{ $iconClass }} w-4"></i>
                                <span>{{ $methodDisplay }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                            $statusConfig = [
                            'pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Pending'],
                            'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Processing'],
                            'shipped' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Shipping'],
                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Completed'],
                            'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Cancelled'],
                            ];
                            $config = $statusConfig[$order->order_status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                {{ $config['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-admin-primary/10 hover:bg-admin-primary/20 text-admin-primary hover:text-blue-700 transition-colors" title="View Details">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <i class="fas fa-inbox text-gray-300 text-5xl mb-4 block"></i>
                            <p class="text-gray-500 text-sm mt-2">No orders found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between">
            <small class="text-gray-600 text-sm">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
            </small>
            <nav aria-label="Page navigation">
                {{ $orders->render('pagination::bootstrap-5') }}
            </nav>
        </div>
        @endif
    </div>
</div>

<style>
    .nav-tab {
        border-bottom-color: transparent !important;
        transition: all 0.3s ease;
    }

    .nav-tab:hover {
        border-bottom-color: #e5e7eb !important;
        color: #374151 !important;
    }

    .nav-tab-active {
        border-bottom-color: #4f46e5 !important;
        color: #4f46e5 !important;
    }
</style>
@endsection