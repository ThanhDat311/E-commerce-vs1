<x-admin-layout :pageTitle="'Reports & Analytics'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Analytics' => route('admin.reports.index')]">

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
        <div class="flex items-center gap-3 flex-wrap">
            <!-- Date Range Picker -->
            <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-2" id="dateForm">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="pl-10 pr-2 py-2 rounded-lg border border-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" onchange="document.getElementById('dateForm').submit()">
                </div>
                <span class="text-gray-400 text-sm">—</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" onchange="document.getElementById('dateForm').submit()">
            </form>

            <a href="{{ route('admin.reports.export_pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank">
                <x-ui.button variant="outline">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    Export PDF
                </x-ui.button>
            </a>
            <a href="{{ route('admin.reports.export_csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}">
                <x-ui.button variant="primary">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    Export CSV
                </x-ui.button>
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total Sales Volume -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Sales Volume</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalSales, 0) }}</p>
                </div>
                @if($salesTrend != 0)
                    <span class="inline-flex items-center gap-0.5 px-2 py-1 rounded-full text-xs font-semibold {{ $salesTrend > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                        @if($salesTrend > 0)↑ @else ↓ @endif
                        {{ abs($salesTrend) }}%
                    </span>
                @endif
            </div>
            <div class="mt-3 h-8">
                <svg viewBox="0 0 200 30" class="w-full h-full text-blue-500" preserveAspectRatio="none">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" points="0,25 20,20 40,22 60,15 80,18 100,10 120,12 140,8 160,5 180,7 200,3" />
                </svg>
            </div>
        </div>

        <!-- New User Sign-ups -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">New User Sign-ups</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($newUsers) }}</p>
                </div>
                @if($usersTrend != 0)
                    <span class="inline-flex items-center gap-0.5 px-2 py-1 rounded-full text-xs font-semibold {{ $usersTrend > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                        @if($usersTrend > 0)↑ @else ↓ @endif
                        {{ abs($usersTrend) }}%
                    </span>
                @endif
            </div>
            <div class="mt-3 h-8">
                <svg viewBox="0 0 200 30" class="w-full h-full text-emerald-500" preserveAspectRatio="none">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" points="0,28 25,22 50,24 75,18 100,20 125,14 150,10 175,12 200,6" />
                </svg>
            </div>
        </div>

        <!-- Active Vendors -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Active Vendors</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($activeVendors) }}</p>
                </div>
                @if($vendorsTrend != 0)
                    <span class="inline-flex items-center gap-0.5 px-2 py-1 rounded-full text-xs font-semibold {{ $vendorsTrend > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                        @if($vendorsTrend > 0)↑ @else ↓ @endif
                        {{ abs($vendorsTrend) }}%
                    </span>
                @endif
            </div>
            <div class="mt-3 h-8">
                <svg viewBox="0 0 200 30" class="w-full h-full text-slate-400" preserveAspectRatio="none">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" points="0,15 25,14 50,16 75,13 100,15 125,14 150,15 175,13 200,14" />
                </svg>
            </div>
        </div>

        <!-- Average Order Value -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Average Order Value</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($avgOrderValue, 2) }}</p>
                </div>
                @if($avgTrend != 0)
                    <span class="inline-flex items-center gap-0.5 px-2 py-1 rounded-full text-xs font-semibold {{ $avgTrend > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                        @if($avgTrend > 0)↑ @else ↓ @endif
                        {{ abs($avgTrend) }}%
                    </span>
                @endif
            </div>
            <div class="mt-3 h-8">
                <svg viewBox="0 0 200 30" class="w-full h-full text-red-400" preserveAspectRatio="none">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" points="0,5 25,8 50,6 75,12 100,10 125,15 150,13 175,18 200,20" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Daily & Monthly Sales (2/3) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-semibold text-gray-900">Daily & Monthly Sales</h3>
                <div class="flex items-center bg-gray-100 rounded-lg p-0.5" x-data="{ chartMode: 'daily' }">
                    <button @click="chartMode = 'daily'; switchChart('daily')"
                            :class="chartMode === 'daily' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                            class="px-4 py-1.5 rounded-md text-xs font-semibold transition-all">Daily</button>
                    <button @click="chartMode = 'monthly'; switchChart('monthly')"
                            :class="chartMode === 'monthly' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                            class="px-4 py-1.5 rounded-md text-xs font-semibold transition-all">Monthly</button>
                </div>
            </div>
            <div class="relative" style="height: 300px">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Sales by Category (1/3) -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-semibold text-gray-900">Sales by Category</h3>
            </div>
            <div class="relative mx-auto" style="height: 200px; max-width: 200px;">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-5 space-y-3">
                @php
                    $categoryColors = ['#3b82f6', '#64748b', '#06b6d4', '#f59e0b', '#a3a3a3'];
                @endphp
                @foreach($categoryLabels as $i => $label)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $categoryColors[$i] ?? '#a3a3a3' }}"></span>
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">{{ $categoryPercentages[$i] ?? 0 }}%</span>
                    </div>
                @endforeach
                @if(empty($categoryLabels))
                    <p class="text-sm text-gray-400 text-center">No category data available</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Vendor Performance Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h3 class="text-base font-semibold text-gray-900">Vendor Performance Overview</h3>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.reports.index') }}" class="relative">
                    <input type="hidden" name="start_date" value="{{ $startDate }}">
                    <input type="hidden" name="end_date" value="{{ $endDate }}">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="vendor_search" value="{{ request('vendor_search') }}"
                           placeholder="Search vendor..."
                           class="pl-10 pr-4 py-2 w-56 rounded-lg border border-gray-200 text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </form>
                <button class="p-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-semibold">Vendor Name</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Total Sales</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Products</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Satisfaction Rating</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($vendors as $vendor)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-500 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $vendor->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900">
                                ${{ number_format($vendor->total_sales ?? 0, 0) }}
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $vendor->products_count }}</td>
                            <td class="px-5 py-4">
                                @php $rating = round($vendor->avg_rating ?? 0, 1); @endphp
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= round($rating) ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $rating }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end">
                                    <button class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No vendors found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vendors->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $vendors->firstItem() }}</span> to <span class="font-medium">{{ $vendors->lastItem() }}</span> of <span class="font-bold text-gray-700">{{ $vendors->total() }}</span> vendors
                </p>
                <div>{{ $vendors->links('vendor.pagination.admin') }}</div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        let salesChart;
        const dailyData = { labels: @json($dailyLabels), values: @json($dailyValues) };
        const monthlyData = { labels: @json($monthlyLabels), values: @json($monthlyValues) };

        function createSalesChart(labels, values) {
            const ctx = document.getElementById('salesChart').getContext('2d');
            if (salesChart) salesChart.destroy();

            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.3)');

            salesChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales',
                        data: values,
                        backgroundColor: gradient,
                        borderRadius: 4,
                        borderSkipped: false,
                        maxBarThickness: 28,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            callbacks: {
                                label: ctx => '$' + ctx.parsed.y.toLocaleString()
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, color: '#94a3b8', maxTicksLimit: 15 }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { size: 11 },
                                color: '#94a3b8',
                                callback: v => v >= 1000 ? '$' + (v/1000) + 'k' : '$' + v
                            }
                        }
                    }
                }
            });
        }

        function switchChart(mode) {
            const d = mode === 'monthly' ? monthlyData : dailyData;
            createSalesChart(d.labels, d.values);
        }

        document.addEventListener('DOMContentLoaded', function() {
            createSalesChart(dailyData.labels, dailyData.values);

            // Category Doughnut
            const catLabels = @json($categoryLabels);
            const catValues = @json($categoryValues);
            const catColors = ['#3b82f6', '#64748b', '#06b6d4', '#f59e0b', '#a3a3a3'];

            if (catLabels.length > 0) {
                const catCtx = document.getElementById('categoryChart').getContext('2d');
                new Chart(catCtx, {
                    type: 'doughnut',
                    data: {
                        labels: catLabels,
                        datasets: [{
                            data: catValues,
                            backgroundColor: catColors.slice(0, catLabels.length),
                            borderWidth: 0,
                            cutout: '70%',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 10,
                                cornerRadius: 8,
                                callbacks: {
                                    label: ctx => ctx.label + ': $' + ctx.parsed.toLocaleString()
                                }
                            }
                        }
                    },
                    plugins: [{
                        id: 'centerText',
                        afterDraw(chart) {
                            const { width, height, ctx } = chart;
                            ctx.save();
                            const pct = @json($categoryPercentages[0] ?? 0) + '%';
                            const label = @json($categoryLabels[0] ?? '');
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.font = 'bold 24px sans-serif';
                            ctx.fillStyle = '#1e293b';
                            ctx.fillText(pct, width/2, height/2 - 8);
                            ctx.font = '12px sans-serif';
                            ctx.fillStyle = '#94a3b8';
                            ctx.fillText(label, width/2, height/2 + 14);
                            ctx.restore();
                        }
                    }]
                });
            }
        });
    </script>
    @endpush
</x-admin-layout>
