@extends('admin.layout.admin')

@section('title', 'Revenue Analytics')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white px-6 py-8 shadow-lg">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-chart-line text-3xl text-blue-400"></i>
                    <div>
                        <h1 class="text-4xl font-bold">Revenue Analytics</h1>
                        <p class="text-slate-300 mt-1">Real-time business intelligence & performance metrics</p>
                    </div>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center gap-2 transition">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>

            <!-- Date Range Picker -->
            <div class="bg-slate-800 bg-opacity-50 rounded-lg p-4 backdrop-blur">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm font-bold text-slate-300 uppercase">Start Date</label>
                        <input type="date" id="startDate" class="w-full mt-2 bg-slate-700 text-white px-3 py-2 rounded border border-slate-600 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-slate-300 uppercase">End Date</label>
                        <input type="date" id="endDate" class="w-full mt-2 bg-slate-700 text-white px-3 py-2 rounded border border-slate-600 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-slate-300 uppercase">Comparison Period</label>
                        <select id="comparisonPeriod" class="w-full mt-2 bg-slate-700 text-white px-3 py-2 rounded border border-slate-600 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="previous">Previous Period</option>
                            <option value="last-year">Last Year</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button class="flex-1 bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded font-semibold transition">
                            <i class="fas fa-filter mr-2"></i>Apply
                        </button>
                        <button class="flex-1 bg-slate-700 hover:bg-slate-600 px-4 py-2 rounded font-semibold transition">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Revenue Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-600 uppercase">Total Revenue</h3>
                    <i class="fas fa-dollar-sign text-2xl text-green-500 opacity-20"></i>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">$186,450</p>
                    <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse(request('startDate', now()->startOfMonth()))->format('M d') }} - {{ \Carbon\Carbon::parse(request('endDate', now()))->format('M d, Y') }}</p>
                </div>
                <div class="flex items-center gap-1 text-green-600 text-sm font-semibold">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12.5% vs previous period</span>
                </div>
            </div>

            <!-- Average Order Value Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-600 uppercase">Avg Order Value</h3>
                    <i class="fas fa-receipt text-2xl text-blue-500 opacity-20"></i>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">$127.35</p>
                    <p class="text-xs text-gray-500 mt-1">From 1,462 orders</p>
                </div>
                <div class="flex items-center gap-1 text-green-600 text-sm font-semibold">
                    <i class="fas fa-arrow-up"></i>
                    <span>+3.2% vs previous period</span>
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-600 uppercase">Total Orders</h3>
                    <i class="fas fa-shopping-cart text-2xl text-purple-500 opacity-20"></i>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">1,462</p>
                    <p class="text-xs text-gray-500 mt-1">Orders completed</p>
                </div>
                <div class="flex items-center gap-1 text-green-600 text-sm font-semibold">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8.7% vs previous period</span>
                </div>
            </div>

            <!-- Conversion Rate Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-600 uppercase">Conversion Rate</h3>
                    <i class="fas fa-percent text-2xl text-orange-500 opacity-20"></i>
                </div>
                <div class="mb-2">
                    <p class="text-3xl font-bold text-gray-900">3.24%</p>
                    <p class="text-xs text-gray-500 mt-1">45,072 total visitors</p>
                </div>
                <div class="flex items-center gap-1 text-red-600 text-sm font-semibold">
                    <i class="fas fa-arrow-down"></i>
                    <span>-0.8% vs previous period</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Revenue Trend Line Chart -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Revenue Trend</h2>
                        <p class="text-sm text-gray-500">Daily revenue over selected period</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded font-semibold">Line</button>
                        <button class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded font-semibold hover:bg-gray-200">Area</button>
                    </div>
                </div>

                <!-- Chart Container -->
                <div class="h-80 bg-gradient-to-b from-blue-50 to-gray-50 rounded-lg p-4 border border-gray-100 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 font-semibold">Chart visualization (Chart.js / ApexCharts)</p>
                        <p class="text-xs text-gray-400 mt-2">Revenue line chart with daily breakdown</p>
                    </div>
                </div>

                <!-- Chart Legend -->
                <div class="mt-4 flex items-center justify-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                        <span class="text-gray-700 font-semibold">Current Period</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                        <span class="text-gray-700 font-semibold">Comparison Period</span>
                    </div>
                </div>
            </div>

            <!-- Revenue by Category -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Revenue by Category</h2>
                    <p class="text-sm text-gray-500 mb-6">Top performing categories</p>
                </div>

                <!-- Category List -->
                <div class="space-y-4">
                    <!-- Electronics -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900">Electronics</span>
                            <span class="text-sm font-bold text-gray-900">$78,240</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 42%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">42% of total revenue</p>
                    </div>

                    <!-- Fashion -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900">Fashion</span>
                            <span class="text-sm font-bold text-gray-900">$52,310</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: 28%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">28% of total revenue</p>
                    </div>

                    <!-- Home & Garden -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900">Home & Garden</span>
                            <span class="text-sm font-bold text-gray-900">$38,145</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 20%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">20% of total revenue</p>
                    </div>

                    <!-- Sports -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900">Sports</span>
                            <span class="text-sm font-bold text-gray-900">$17,755</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-600 h-2 rounded-full" style="width: 10%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">10% of total revenue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Comparison & Bar Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue Comparison -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Period Comparison</h2>
                    <p class="text-sm text-gray-500 mb-6">Current period vs. previous period breakdown</p>
                </div>

                <!-- Comparison Metrics -->
                <div class="space-y-5">
                    <!-- Revenue -->
                    <div class="border-b pb-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900">Total Revenue</span>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-gray-900">$186,450</span>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-semibold">+12.5%</span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Previous: $165,734</div>
                    </div>

                    <!-- Average Order Value -->
                    <div class="border-b pb-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900">Avg Order Value</span>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-gray-900">$127.35</span>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-semibold">+3.2%</span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Previous: $123.41</div>
                    </div>

                    <!-- Orders Completed -->
                    <div class="border-b pb-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900">Orders Completed</span>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-gray-900">1,462</span>
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded font-semibold">+8.7%</span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Previous: 1,344</div>
                    </div>

                    <!-- Conversion Rate -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900">Conversion Rate</span>
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-bold text-gray-900">3.24%</span>
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded font-semibold">-0.8%</span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Previous: 3.27%</div>
                    </div>
                </div>
            </div>

            <!-- Revenue by Day of Week Bar Chart -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Revenue by Day of Week</h2>
                    <p class="text-sm text-gray-500 mb-6">Daily distribution across the period</p>
                </div>

                <!-- Bar Chart -->
                <div class="h-80 bg-gradient-to-b from-blue-50 to-gray-50 rounded-lg p-4 border border-gray-100 flex items-center justify-center">
                    <div class="text-center w-full">
                        <!-- Bar Chart Bars -->
                        <div class="flex items-end justify-around h-56 gap-2">
                            <!-- Monday -->
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: 85%"></div>
                                <span class="text-xs font-semibold text-gray-600">Mon</span>
                                <span class="text-xs text-gray-500">$28.2k</span>
                            </div>
                            <!-- Tuesday -->
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: 75%"></div>
                                <span class="text-xs font-semibold text-gray-600">Tue</span>
                                <span class="text-xs text-gray-500">$25.1k</span>
                            </div>
                            <!-- Wednesday -->
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: 92%"></div>
                                <span class="text-xs font-semibold text-gray-600">Wed</span>
                                <span class="text-xs text-gray-500">$30.8k</span>
                            </div>
                            <!-- Thursday -->
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: 88%"></div>
                                <span class="text-xs font-semibold text-gray-600">Thu</span>
                                <span class="text-xs text-gray-500">$29.5k</span>
                            </div>
                            <!-- Friday -->
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: 100%"></div>
                                <span class="text-xs font-semibold text-gray-600">Fri</span>
                                <span class="text-xs text-gray-500">$33.6k</span>
                            </div>
                            <!-- Saturday -->
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: 82%"></div>
                                <span class="text-xs font-semibold text-gray-600">Sat</span>
                                <span class="text-xs text-gray-500">$27.3k</span>
                            </div>
                            <!-- Sunday -->
                            <div class="flex flex-col items-center gap-2 flex-1">
                                <div class="w-full bg-gradient-to-t from-blue-600 to-blue-400 rounded-t" style="height: 70%"></div>
                                <span class="text-xs font-semibold text-gray-600">Sun</span>
                                <span class="text-xs text-gray-500">$12.0k</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Info -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
            <div>
                <p class="text-sm font-semibold text-blue-900">Data refreshes every hour</p>
                <p class="text-xs text-blue-700 mt-1">Last updated: Today at {{ now()->format('H:i') }} • Next update: In {{ 60 - now()->minute }} minutes</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styling for dashboard */
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        border-radius: 4px;
        margin-right: 2px;
        opacity: 0.6;
        filter: invert(1);
    }
</style>
@endsection