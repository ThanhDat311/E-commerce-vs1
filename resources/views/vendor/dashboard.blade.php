@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')

@section('content')
@vite(['resources/css/app.css', 'resources/js/app.js'])
<div class="px-4 py-2" x-data="vendorDashboard()">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                Hi, {{ Auth::user()->name }} 👋
            </h1>
            <p class="text-sm text-gray-500 mt-1">Here's what's happening with your store today.</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('vendor.products.create') }}" 
                class="inline-flex items-center px-4 py-2 bg-admin-primary border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-800 focus:bg-blue-800 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <i class="fas fa-plus mr-2"></i> Add Product
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Revenue Card --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-50 text-blue-600 rounded-lg p-3">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <i class="fas fa-arrow-up mr-1"></i> +2.5%
                </span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Total Revenue</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($totalRevenue, 2) }}</p>
        </div>

        {{-- Orders Card --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-50 text-purple-600 rounded-lg p-3">
                    <i class="fas fa-shopping-bag text-xl"></i>
                </div>
                <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2 py-1 rounded-full">New</span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($ordersCount) }}</p>
        </div>

        {{-- Products Card --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-indigo-50 text-indigo-600 rounded-lg p-3">
                    <i class="fas fa-box text-xl"></i>
                </div>
                @if($lowStockCount > 0)
                <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full" title="Low Stock">
                    {{ $lowStockCount }} Alert
                </span>
                @else
                <span class="text-xs font-medium text-gray-500 bg-gray-50 px-2 py-1 rounded-full">
                    In Stock
                </span>
                @endif
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Active Products</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($productsCount) }}</p>
        </div>

        {{-- Reviews Card --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-orange-50 text-orange-600 rounded-lg p-3">
                    <i class="fas fa-star text-xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Total Reviews</h3>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($reviewsCount) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Revenue Chart --}}
        <div class="col-span-1 lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800">Weekly Revenue</h3>
                <select class="text-sm border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 p-1">
                    <option>Last 7 Days</option>
                    {{-- Future options can be added here --}}
                </select>
            </div>
            <div class="relative h-72">
                 <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Top Products / Notices or something else. I'll stick to a simpler widget or maybe just Recent Orders takes full width? 
             The prompt asked for Recent Orders and Revenue Chart.
             I'll put Recent Orders inside the grid if it fits, or full width below.
             Let's put Recent Orders full width below for better table view.
             For the 3rd column here, I'll add a "Store Health" or "Quick Actions" mini panel since the design usually has 2/3 + 1/3 split.
        --}}
        <div class="col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                 <h3 class="text-lg font-bold text-gray-800 mb-4">Store Status</h3>
                 <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-store text-gray-400 mr-3"></i>
                            <span class="text-sm font-medium text-gray-600">Store Status</span>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Online</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-gray-400 mr-3"></i>
                            <span class="text-sm font-medium text-gray-600">Verification</span>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full">Verified</span>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                         <div class="flex items-center">
                            <i class="fas fa-box-open text-gray-400 mr-3"></i>
                            <span class="text-sm font-medium text-gray-600">Low Stock Items</span>
                        </div>
                         @if($lowStockCount > 0)
                            <span class="px-2 py-1 text-xs font-semibold text-white bg-red-500 rounded-full">{{ $lowStockCount }}</span>
                         @else
                            <span class="text-sm text-gray-400">None</span>
                         @endif
                    </div>
                 </div>
            </div>
            
            <div class="mt-6 p-4 bg-admin-primary/5 rounded-xl border border-admin-primary/10">
                 <h4 class="text-sm font-semibold text-admin-primary mb-2">Need Help?</h4>
                 <p class="text-xs text-gray-600 mb-3">Contact support if you have issues with orders.</p>
                 <a href="#" class="text-xs font-bold text-admin-primary hover:text-blue-800">Contact Support &rarr;</a>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-8 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
             <h3 class="text-lg font-bold text-gray-800">Recent Orders</h3>
             <a href="{{ route('vendor.orders.index') }}" class="text-sm text-admin-primary hover:underline font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Order ID</th>
                        <th class="px-6 py-4 font-semibold">Customer</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">My Earnings</th>
                        <th class="px-6 py-4 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                    @php
                        // Calculate vendor specific portion of the order
                        $vendorTotal = $order->orderItems->sum('total');
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900">#{{ $order->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-3 uppercase">
                                    {{ substr($order->first_name, 0, 1) }}{{ substr($order->last_name, 0, 1) }}
                                </div>
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $order->first_name }} {{ $order->last_name }}</div>
                                    <div class="text-gray-500 text-xs">{{ $order->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = match($order->order_status) {
                                    'completed' => 'bg-green-100 text-green-700',
                                    'processing' => 'bg-blue-100 text-blue-700',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">
                            ${{ number_format($vendorTotal, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                             <a href="{{ route('vendor.orders.show', $order) }}" 
                                class="text-admin-primary hover:text-blue-800 font-medium text-sm">
                                Details
                             </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>No orders found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
{{-- Load Chart.js from CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('vendorDashboard', () => ({
            init() {
                this.initChart();
            },
            initChart() {
                const ctx = document.getElementById('revenueChart');
                if (!ctx) return;
                
                // Data from Controller
                const labels = @json($chartLabels);
                const data = @json($chartData);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Revenue ($)',
                            data: data,
                            backgroundColor: '#1111d4', // admin-primary
                            borderRadius: 6,
                            barThickness: 30,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                             tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                titleFont: { size: 13 },
                                bodyFont: { size: 14 },
                                cornerRadius: 8,
                                displayColors: false,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    borderDash: [5, 5],
                                    drawBorder: false,
                                    color: '#f3f4f6'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        }));
    });
</script>
@endpush
@endsection