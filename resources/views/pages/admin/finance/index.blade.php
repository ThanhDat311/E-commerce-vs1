<x-admin-layout :pageTitle="'Financial Dashboard'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Finance' => route('admin.finance.index')]">

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Financial Overview</h1>
        <a href="{{ route('admin.finance.export') }}">
            <x-ui.button variant="outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Report
            </x-ui.button>
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRevenue, 0) }}</p>
                </div>
                @if($revenueTrend != 0)
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $revenueTrend > 0 ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                        @if($revenueTrend > 0)
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L10 13.586l3.293-3.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @endif
                        {{ abs($revenueTrend) }}%
                    </span>
                @endif
            </div>
            <div class="mt-3 h-8">
                <svg viewBox="0 0 200 30" class="w-full h-full text-blue-500" preserveAspectRatio="none">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="0,25 20,20 40,22 60,15 80,18 100,10 120,12 140,8 160,5 180,7 200,3" />
                </svg>
            </div>
        </div>

        <!-- Platform Commissions -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Platform Commissions</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($platformCommissions, 0) }}</p>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L10 6.414l-3.293 3.293a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    +{{ $commissionTrend }}%
                </span>
            </div>
            <div class="mt-3 h-8">
                <svg viewBox="0 0 200 30" class="w-full h-full text-emerald-500" preserveAspectRatio="none">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="0,28 25,22 50,24 75,18 100,20 125,14 150,10 175,12 200,6" />
                </svg>
            </div>
        </div>

        <!-- Pending Payouts -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pending Payouts</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($pendingPayouts, 0) }}</p>
                </div>
                <x-ui.badge variant="pending">Pending</x-ui.badge>
            </div>
            <div class="mt-3 h-8">
                <svg viewBox="0 0 200 30" class="w-full h-full text-amber-400" preserveAspectRatio="none">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="0,15 25,18 50,12 75,20 100,15 125,22 150,18 175,25 200,20" />
                </svg>
            </div>
        </div>

        <!-- Tax Collected -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tax Collected</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($taxCollected, 0) }}</p>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Flat</span>
            </div>
            <div class="mt-3 h-8">
                <svg viewBox="0 0 200 30" class="w-full h-full text-slate-400" preserveAspectRatio="none">
                    <polyline fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" points="0,16 25,14 50,17 75,13 100,15 125,12 150,16 175,14 200,15" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Revenue Chart + Commission Settings (side by side) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue vs Payouts Chart (2/3) -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-base font-semibold text-gray-900">Revenue vs. Payouts</h3>
                <select id="chartPeriod" class="text-sm border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="30">Last 30 Days</option>
                    <option value="7">Last 7 Days</option>
                    <option value="90">Last 90 Days</option>
                </select>
            </div>
            <div class="relative" style="height: 280px">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Commission Settings (1/3) -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="h-10 w-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900">Commission Settings</h3>
            </div>

            <p class="text-sm text-gray-500 mb-5">Adjust the global platform commission rate for all vendor transactions.</p>

            <form action="{{ route('admin.finance.commission.update') }}" method="POST" x-data="{ rate: {{ $globalRate }} }">
                @csrf

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Global Rate</span>
                        <span class="text-lg font-bold text-blue-600" x-text="rate + '%'"></span>
                    </div>
                    <input type="range" name="rate" min="0" max="20" step="0.5"
                           x-model="rate"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                    <div class="flex justify-between text-xs text-gray-400 mt-1">
                        <span>0%</span>
                        <span>10%</span>
                        <span>20%</span>
                    </div>
                </div>

                @error('rate')
                    <p class="text-sm text-red-600 mb-3">{{ $message }}</p>
                @enderror

                <div class="mb-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Quick Actions</p>
                    <a href="#" class="flex items-center gap-2 px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                        Advanced Rules
                    </a>
                </div>

                <x-ui.button type="submit" variant="primary" class="w-full">
                    Save Changes
                </x-ui.button>
            </form>
        </div>
    </div>

    <!-- Transactions & Payouts Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h3 class="text-base font-semibold text-gray-900">Transactions & Payouts</h3>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.finance.index') }}" class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search ID or Vendor..."
                           class="pl-10 pr-4 py-2 w-64 rounded-lg border border-gray-200 bg-white text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </form>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" type="button" class="p-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                        <a href="{{ route('admin.finance.index', array_merge(request()->except('status'), [])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">All</a>
                        <a href="{{ route('admin.finance.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pending</a>
                        <a href="{{ route('admin.finance.index', array_merge(request()->except('status'), ['status' => 'paid'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Paid</a>
                        <a href="{{ route('admin.finance.index', array_merge(request()->except('status'), ['status' => 'cancelled'])) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelled</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-semibold">Transaction ID</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Vendor</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Amount</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Platform Fee</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Payout Date</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <div>
                                    <span class="font-semibold text-gray-900">#TRX-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <div class="text-xs text-gray-400 mt-0.5">Order #ORD-{{ $trx->order_id }}</div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-semibold text-slate-500 flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $trx->vendor->name ?? 'Platform Direct' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900">
                                ${{ number_format($trx->order_total, 2) }}
                            </td>
                            <td class="px-5 py-4 text-gray-600">
                                ${{ number_format($trx->commission_amount, 2) }} ({{ $trx->commission_rate }}%)
                            </td>
                            <td class="px-5 py-4 text-gray-500">
                                {{ $trx->paid_at ? $trx->paid_at->format('M d, Y') : '—' }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusVariant = match($trx->status) {
                                        'paid' => 'success',
                                        'pending' => 'pending',
                                        'cancelled' => 'danger',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusVariant" :dot="true">
                                    {{ ucfirst($trx->status) }}
                                </x-ui.badge>
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No transactions found yet.</p>
                                <p class="text-xs text-gray-400 mt-1">Commission records will appear here as orders are placed.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $transactions->firstItem() }}</span> to <span class="font-medium">{{ $transactions->lastItem() }}</span> of <span class="font-bold text-gray-700">{{ $transactions->total() }}</span> transactions
                </p>
                <div>{{ $transactions->links('vendor.pagination.admin') }}</div>
            </div>
        @endif
    </div>

    <!-- Chart.js Script -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 280);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Revenue',
                            data: @json($chartRevenue),
                            borderColor: '#3b82f6',
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2.5,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            pointHoverBackgroundColor: '#3b82f6',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                        },
                        {
                            label: 'Payouts',
                            data: @json($chartPayouts),
                            borderColor: '#94a3b8',
                            backgroundColor: 'transparent',
                            fill: false,
                            tension: 0.4,
                            borderWidth: 2,
                            borderDash: [5, 5],
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: '#94a3b8',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label;
                                    const value = '$' + context.parsed.y.toLocaleString();
                                    return label + ': ' + value;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                maxTicksLimit: 10,
                                font: { size: 11 },
                                color: '#94a3b8',
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { size: 11 },
                                color: '#94a3b8',
                                callback: function(value) {
                                    if (value >= 1000) return '$' + (value / 1000) + 'k';
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
