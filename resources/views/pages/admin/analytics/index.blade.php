<x-admin-layout :pageTitle="'Revenue Analytics'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Analytics' => route('admin.analytics.index')]">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Revenue Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $startDate->format('M d, Y') }} — {{ $endDate->format('M d, Y') }}
                <span class="text-gray-400 ml-2">vs {{ $comparisonStart->format('M d') }}–{{ $comparisonEnd->format('M d') }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Date Range Filter Form --}}
            <form method="GET" action="{{ route('admin.analytics.index') }}" class="flex items-center gap-2">
                <input type="date" name="startDate" value="{{ $startDate->format('Y-m-d') }}"
                       class="rounded-lg border border-gray-200 text-sm text-gray-700 px-3 py-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <span class="text-gray-400 text-sm">to</span>
                <input type="date" name="endDate" value="{{ $endDate->format('Y-m-d') }}"
                       class="rounded-lg border border-gray-200 text-sm text-gray-700 px-3 py-1.5 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Apply
                </button>
            </form>
        </div>
    </div>

    {{-- KPI Cards --}}
    @php
        $revenueChange  = $analytics['prevTotalRevenue']  > 0 ? (($analytics['totalRevenue']  - $analytics['prevTotalRevenue'])  / $analytics['prevTotalRevenue'])  * 100 : 0;
        $ordersChange   = $analytics['prevTotalOrders']   > 0 ? (($analytics['totalOrders']   - $analytics['prevTotalOrders'])   / $analytics['prevTotalOrders'])   * 100 : 0;
        $aovChange      = $analytics['prevAverageOrderValue'] > 0 ? (($analytics['averageOrderValue'] - $analytics['prevAverageOrderValue']) / $analytics['prevAverageOrderValue']) * 100 : 0;
        $convChange     = $analytics['prevConversionRate'] > 0 ? (($analytics['conversionRate'] - $analytics['prevConversionRate']) / $analytics['prevConversionRate']) * 100 : 0;
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Revenue --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($analytics['totalRevenue'], 0) }}</p>
            <div class="flex items-center gap-1 mt-1">
                @if($revenueChange >= 0)
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                    <span class="text-xs font-semibold text-green-600">+{{ number_format($revenueChange, 1) }}%</span>
                @else
                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    <span class="text-xs font-semibold text-red-600">{{ number_format($revenueChange, 1) }}%</span>
                @endif
                <span class="text-xs text-gray-400">vs prev period</span>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Total Orders</p>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($analytics['totalOrders']) }}</p>
            <div class="flex items-center gap-1 mt-1">
                @if($ordersChange >= 0)
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                    <span class="text-xs font-semibold text-green-600">+{{ number_format($ordersChange, 1) }}%</span>
                @else
                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    <span class="text-xs font-semibold text-red-600">{{ number_format($ordersChange, 1) }}%</span>
                @endif
                <span class="text-xs text-gray-400">vs prev period</span>
            </div>
        </div>

        {{-- Avg Order Value --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Avg. Order Value</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($analytics['averageOrderValue'], 2) }}</p>
            <div class="flex items-center gap-1 mt-1">
                @if($aovChange >= 0)
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                    <span class="text-xs font-semibold text-green-600">+{{ number_format($aovChange, 1) }}%</span>
                @else
                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    <span class="text-xs font-semibold text-red-600">{{ number_format($aovChange, 1) }}%</span>
                @endif
                <span class="text-xs text-gray-400">vs prev period</span>
            </div>
        </div>

        {{-- Conversion Rate --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Conversion Rate</p>
            <p class="text-2xl font-bold text-gray-900">{{ $analytics['conversionRate'] }}%</p>
            <div class="flex items-center gap-1 mt-1">
                @if($convChange >= 0)
                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5"/></svg>
                    <span class="text-xs font-semibold text-green-600">+{{ number_format($convChange, 1) }}%</span>
                @else
                    <svg class="w-3.5 h-3.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                    <span class="text-xs font-semibold text-red-600">{{ number_format($convChange, 1) }}%</span>
                @endif
                <span class="text-xs text-gray-400">vs prev period</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Revenue by Day of Week --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Revenue by Day of Week</h3>
            @php $maxDayRevenue = collect($analytics['dailyBreakdown'])->max('revenue'); @endphp
            <div class="space-y-3">
                @foreach($analytics['dailyBreakdown'] as $day)
                    @php $barWidth = $maxDayRevenue > 0 ? ($day['revenue'] / $maxDayRevenue) * 100 : 0; @endphp
                    <div class="flex items-center gap-3">
                        <span class="w-8 text-xs font-medium text-gray-500 text-right flex-shrink-0">{{ $day['day'] }}</span>
                        <div class="flex-1 h-6 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-6 bg-blue-500 rounded-full flex items-center justify-end pr-2 transition-all"
                                 style="width: {{ $barWidth }}%">
                                <span class="text-xs font-semibold text-white whitespace-nowrap">${{ number_format($day['revenue']) }}</span>
                            </div>
                        </div>
                        <span class="w-10 text-xs text-gray-400 text-right flex-shrink-0">{{ $day['percentage'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Revenue by Category --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Revenue by Category</h3>
            @php
                $categoryColors = ['bg-blue-500', 'bg-purple-500', 'bg-green-500', 'bg-orange-500', 'bg-pink-500'];
                $totalCatRevenue = collect($analytics['categoryBreakdown'])->sum('revenue');
            @endphp
            <div class="space-y-4">
                @foreach($analytics['categoryBreakdown'] as $i => $cat)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $cat['name'] }}</span>
                            <span class="text-sm font-bold text-gray-900">${{ number_format($cat['revenue']) }}</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="{{ $categoryColors[$i % count($categoryColors)] }} h-2 rounded-full"
                                 style="width: {{ $cat['percentage'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5 text-right">{{ $cat['percentage'] }}% of total</p>
                    </div>
                @endforeach
            </div>

            {{-- Total --}}
            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-600">Total</span>
                <span class="text-base font-bold text-gray-900">${{ number_format($totalCatRevenue) }}</span>
            </div>
        </div>
    </div>

    {{-- Additional Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Total Visitors --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="p-3 bg-indigo-50 rounded-xl">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Visitors</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($analytics['totalVisitors']) }}</p>
                <p class="text-xs text-gray-400">Prev: {{ number_format($analytics['prevTotalVisitors']) }}</p>
            </div>
        </div>

        {{-- Revenue per visitor --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="p-3 bg-emerald-50 rounded-xl">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Revenue per Visitor</p>
                @php $rpv = $analytics['totalVisitors'] > 0 ? $analytics['totalRevenue'] / $analytics['totalVisitors'] : 0; @endphp
                <p class="text-2xl font-bold text-gray-900">${{ number_format($rpv, 2) }}</p>
                <p class="text-xs text-gray-400">Based on {{ number_format($analytics['totalVisitors']) }} visitors</p>
            </div>
        </div>
    </div>

</x-admin-layout>
