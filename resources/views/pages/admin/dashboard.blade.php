<x-admin-layout :pageTitle="'Dashboard'" :breadcrumbs="['Admin' => route('admin.dashboard')]">
    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back, <span class="font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin' }}</span>. Here is what's happening with your store today.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:ring-offset-2 active:bg-gray-100 transition-all">
                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Report
            </a>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-800 transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Manage Orders
            </a>
        </div>
    </div>

    <!-- Stats Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total Revenue -->
        <x-dashboard.stat-card title="Total Revenue" value="${{ number_format($totalRevenue, 2) }}" color="blue">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-dashboard.stat-card>

        <!-- Total Orders -->
        <x-dashboard.stat-card title="Total Orders" value="{{ number_format($totalOrders) }}" color="emerald">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
        </x-dashboard.stat-card>

        <!-- Active Users -->
        <x-dashboard.stat-card title="Active Users" value="{{ number_format($activeUsers) }}" color="purple">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </x-dashboard.stat-card>

        <!-- Pending Orders -->
        <x-dashboard.stat-card title="Pending Orders" value="{{ number_format($pendingOrders) }}" color="amber">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-dashboard.stat-card>
    </div>

    <!-- Charts Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm lg:col-span-2 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-white">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Revenue Overview</h3>
                    <p class="text-xs text-gray-500 mt-1">Daily revenue for the last 7 days</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10">7 Days</span>
                </div>
            </div>
            <div class="p-6">
                <!-- Chart Container -->
                <div id="revenueChart" class="w-full h-80"></div>
            </div>
        </div>

        <!-- Login Risk Chart -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-white">
                <h3 class="text-base font-semibold text-gray-900">Login Risk Evaluations</h3>
                <p class="text-xs text-gray-500 mt-1">Overall login attempt risk distribution</p>
            </div>
            <div class="p-6 flex-1 flex flex-col justify-center relative">
                <!-- Chart Container -->
                <div id="riskChart" class="w-full flex justify-center scale-105 origin-center"></div>
                
                <div class="w-full mt-8 space-y-4">
                    <div class="flex items-center justify-between text-sm group cursor-default">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm group-hover:scale-110 transition-transform"></span>
                            <span class="text-gray-600 font-medium">Low Risk</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ number_format($riskData[0] ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm group cursor-default">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-amber-500 shadow-sm group-hover:scale-110 transition-transform"></span>
                            <span class="text-gray-600 font-medium">Medium Risk</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ number_format($riskData[1] ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm group cursor-default">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-red-500 shadow-sm group-hover:scale-110 transition-transform"></span>
                            <span class="text-gray-600 font-medium">High Risk</span>
                        </div>
                        <span class="font-bold text-gray-900">{{ number_format($riskData[2] ?? 0) }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Avg Score</p>
                        <p class="text-lg font-bold text-gray-900 mt-1">{{ number_format($avgRiskScore, 1) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Blocked</p>
                        <p class="text-lg font-bold text-red-600 mt-1">{{ number_format($fraudBlocked) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Orders Row 3 -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-white">
            <div>
                <h3 class="text-base font-semibold text-gray-900">Latest Orders</h3>
                <p class="text-xs text-gray-500 mt-1">Most recent transactions in the store</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                View all orders
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Order ID</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                     @forelse ($latestOrders as $order)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">
                                        {{ substr($order->user->name ?? 'G', 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                                        {{ $order->user->name ?? 'Guest' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                ${{ number_format($order->total, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $variant = match($order->order_status) {
                                        'completed', 'delivered' => 'success',
                                        'pending', 'processing' => 'warning',
                                        'cancelled', 'failed' => 'danger',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$variant" :dot="true">
                                    {{ ucfirst($order->order_status) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-right font-medium">
                                {{ $order->created_at->format('M d, Y') }}
                                <span class="block text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('h:i A') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-base font-medium text-gray-900">No orders found</p>
                                    <p class="text-sm mt-1">There are no recent orders to display.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. Revenue Chart
                const rawValues = @json($chartValues ?? []);
                const rawLabels = @json($chartLabels ?? []);

                // Generate last 7 days to ensure line chart draws properly
                const chartLabels = [];
                const chartValues = [];
                for (let i = 6; i >= 0; i--) {
                    const d = new Date();
                    d.setDate(d.getDate() - i);
                    
                    // Format Date as YYYY-MM-DD
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');
                    const dateStr = `${year}-${month}-${day}`;
                    
                    chartLabels.push(dateStr);
                    
                    const index = rawLabels.indexOf(dateStr);
                    if (index !== -1) {
                        chartValues.push(parseFloat(rawValues[index]));
                    } else {
                        chartValues.push(0);
                    }
                }

                if (document.querySelector("#revenueChart") && chartValues.length > 0) {
                    const revenueOptions = {
                        series: [{
                            name: 'Revenue',
                            data: chartValues
                        }],
                        chart: {
                            type: 'area',
                            height: 320,
                            fontFamily: 'inherit',
                            toolbar: { show: false },
                            zoom: { enabled: false }
                        },
                        colors: ['#2563eb'], // blue-600
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.45,
                                opacityTo: 0.05,
                                stops: [50, 100, 100]
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: { 
                            curve: 'smooth', 
                            width: 3 
                        },
                        markers: {
                            size: 4,
                            colors: ['#2563eb'],
                            strokeColors: '#fff',
                            strokeWidth: 2,
                            hover: { size: 6 }
                        },
                        xaxis: {
                            categories: chartLabels,
                            labels: { 
                                style: { 
                                    colors: '#64748b', 
                                    fontSize: '12px',
                                    fontWeight: 500
                                } 
                            },
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            tooltip: { enabled: false }
                        },
                        yaxis: {
                            labels: {
                                style: { 
                                    colors: '#64748b',
                                    fontSize: '12px',
                                    fontWeight: 500
                                },
                                formatter: (value) => { return '$' + value }
                            }
                        },
                        grid: {
                            borderColor: '#f1f5f9',
                            strokeDashArray: 4,
                            xaxis: { lines: { show: false } },
                            yaxis: { lines: { show: true } },
                            padding: { top: 0, right: 0, bottom: 0, left: 10 }
                        },
                        tooltip: {
                            theme: 'light',
                            y: { formatter: function (val) { return "$" + val.toFixed(2) } }
                        }
                    };

                    const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
                    revenueChart.render();
                }

                // 2. Risk Pie Chart
                const riskData = @json($riskData ?? [0, 0, 0]);
                const totalRiskScans = riskData.reduce((a, b) => a + b, 0);

                if (document.querySelector("#riskChart") && totalRiskScans > 0) {
                    const riskOptions = {
                        series: riskData,
                        chart: {
                            type: 'donut',
                            height: 280,
                            fontFamily: 'inherit',
                        },
                        labels: ['Low Risk', 'Medium Risk', 'High Risk'],
                        colors: ['#10b981', '#f59e0b', '#ef4444'], // emerald-500, amber-500, red-500
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '78%',
                                    labels: {
                                        show: true,
                                        name: { 
                                            show: true, 
                                            color: '#64748b', 
                                            fontSize: '13px',
                                            fontWeight: 600,
                                            offsetY: -10
                                        },
                                        value: { 
                                            show: true, 
                                            color: '#0f172a', 
                                            fontSize: '32px', 
                                            fontWeight: 700,
                                            offsetY: 8,
                                            formatter: function (val) { return val } 
                                        },
                                        total: { 
                                            show: true, 
                                            showAlways: true, 
                                            label: 'Total Scanned', 
                                            fontSize: '13px', 
                                            fontWeight: 600,
                                            color: '#64748b',
                                            formatter: function (w) {
                                                return w.globals.seriesTotals.reduce((a, b) => {
                                                    return a + b
                                                }, 0)
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: { enabled: false },
                        legend: { show: false },
                        stroke: { width: 0 },
                        tooltip: {
                            theme: 'light',
                            fillSeriesColor: false,
                            y: { title: { formatter: function (seriesName) { return seriesName + ":" } } }
                        }
                    };

                    const riskChart = new ApexCharts(document.querySelector("#riskChart"), riskOptions);
                    riskChart.render();
                } else if (document.querySelector("#riskChart") && totalRiskScans === 0) {
                    document.querySelector("#riskChart").innerHTML = '<div class="text-sm text-gray-500 flex items-center justify-center h-full">No risk data available to chart</div>';
                }
            });
        </script>
    @endpush
</x-admin-layout>
