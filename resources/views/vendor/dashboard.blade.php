@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')

@section('content')
<div x-data="vendorDashboard()">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Hi, {{ Auth::user()->name }} 👋
            </h1>
            <p class="text-sm text-gray-500 mt-1">Here's what's happening with your store today.</p>
        </div>
        <x-admin.button :href="route('vendor.products.create')" icon="plus">
            Add Product
        </x-admin.button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-admin.stat-card
            title="Total Revenue"
            stat="${{ number_format($totalRevenue, 2) }}"
            icon="dollar-sign"
            iconBg="green"
            trend="+2.5%"
        />

        <x-admin.stat-card
            title="Total Orders"
            stat="{{ number_format($ordersCount) }}"
            icon="shopping-cart"
            iconBg="blue"
            subtitle="New orders"
        />

        <x-admin.stat-card
            title="Active Products"
            stat="{{ number_format($productsCount) }}"
            icon="box"
            iconBg="yellow"
        >
            @if($lowStockCount > 0)
            <div class="mt-2 text-xs text-red-600 font-medium">
                <i class="fas fa-exclamation-triangle mr-1"></i> {{ $lowStockCount }} Low Stock
            </div>
            @else
            <div class="mt-2 text-xs text-access-600 font-medium text-green-600">
                All items in stock
            </div>
            @endif
        </x-admin.stat-card>

        <x-admin.stat-card
            title="Total Reviews"
            stat="{{ number_format($reviewsCount) }}"
            icon="star"
            iconBg="orange"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        {{-- Revenue Chart --}}
        <div class="col-span-1 lg:col-span-2">
            <x-admin.card variant="white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Weekly Revenue</h3>
                    <select class="text-sm border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 p-1">
                        <option>Last 7 Days</option>
                    </select>
                </div>
                <div class="relative h-72">
                     <canvas id="revenueChart"></canvas>
                </div>
            </x-admin.card>
        </div>

        {{-- Store Status --}}
        <div class="col-span-1">
             <x-admin.card variant="white" class="h-full flex flex-col justify-between">
                 <div>
                      <h3 class="text-lg font-bold text-gray-800 mb-4">Store Status</h3>
                      <div class="space-y-4">
                         <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                             <div class="flex items-center">
                                 <i class="fas fa-store text-gray-400 mr-3"></i>
                                 <span class="text-sm font-medium text-gray-600">Store Status</span>
                             </div>
                             <x-admin.badge variant="success" icon="check-circle">Online</x-admin.badge>
                         </div>
     
                         <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                             <div class="flex items-center">
                                 <i class="fas fa-check-circle text-gray-400 mr-3"></i>
                                 <span class="text-sm font-medium text-gray-600">Verification</span>
                             </div>
                             <x-admin.badge variant="info" icon="check-circle">Verified</x-admin.badge>
                         </div>
     
                         <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                              <div class="flex items-center">
                                 <i class="fas fa-box-open text-gray-400 mr-3"></i>
                                 <span class="text-sm font-medium text-gray-600">Low Stock Items</span>
                             </div>
                              @if($lowStockCount > 0)
                                 <x-admin.badge variant="critical">{{ $lowStockCount }}</x-admin.badge>
                              @else
                                 <span class="text-sm text-gray-400">None</span>
                              @endif
                         </div>
                      </div>
                 </div>
                 
                 <div class="mt-6 p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                      <h4 class="text-sm font-semibold text-blue-800 mb-2">Need Help?</h4>
                      <p class="text-xs text-gray-600 mb-3">Contact support if you have issues with orders.</p>
                      <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                          Contact Support <i class="fas fa-arrow-right"></i>
                      </a>
                 </div>
             </x-admin.card>
        </div>
    </div>

    {{-- Recent Orders --}}
    <x-admin.table title="Recent Orders">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">My Earnings</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($recentOrders as $order)
            @php
                $vendorTotal = $order->orderItems->sum('total');
                $statusVariant = match($order->order_status) {
                    'completed' => 'success',
                    'processing' => 'info',
                    'pending' => 'warning',
                    'cancelled' => 'critical',
                    default => 'neutral'
                };
            @endphp
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
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
                <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                    {{ $order->created_at->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-admin.badge :variant="$statusVariant">
                        {{ ucfirst($order->order_status) }}
                    </x-admin.badge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                    ${{ number_format($vendorTotal, 2) }}
                </td>
                <td class="px-6 py-4 text-right whitespace-nowrap">
                    <x-admin.button variant="ghost" size="sm" :href="route('vendor.orders.show', $order)">
                        Details
                    </x-admin.button>
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
    </x-admin.table>
    
    <div class="mt-4 text-right">
        <a href="{{ route('vendor.orders.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">View All Orders &rarr;</a>
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
                            backgroundColor: '#2563eb', // blue-600 matches x-admin theme
                            borderRadius: 4,
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