@extends('admin.layout.admin')

@section('title', 'Top Selling Products')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white px-6 py-8 shadow-lg">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-star text-3xl text-yellow-400"></i>
                    <div>
                        <h1 class="text-4xl font-bold">Top Selling Products</h1>
                        <p class="text-slate-300 mt-1">Product performance insights & sales metrics</p>
                    </div>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg flex items-center gap-2 transition">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>

            <!-- Filter Section -->
            <div class="bg-slate-800 bg-opacity-50 rounded-lg p-4 backdrop-blur">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="text-sm font-bold text-slate-300 uppercase">Start Date</label>
                        <input type="date" id="startDate" class="w-full mt-2 bg-slate-700 text-white px-3 py-2 rounded border border-slate-600 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-slate-300 uppercase">End Date</label>
                        <input type="date" id="endDate" class="w-full mt-2 bg-slate-700 text-white px-3 py-2 rounded border border-slate-600 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="text-sm font-bold text-slate-300 uppercase">Category</label>
                        <select id="category" class="w-full mt-2 bg-slate-700 text-white px-3 py-2 rounded border border-slate-600 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Categories</option>
                            <option value="electronics">Electronics</option>
                            <option value="fashion">Fashion</option>
                            <option value="home">Home & Garden</option>
                            <option value="sports">Sports</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-bold text-slate-300 uppercase">Sort By</label>
                        <select id="sortBy" class="w-full mt-2 bg-slate-700 text-white px-3 py-2 rounded border border-slate-600 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="units">Units Sold</option>
                            <option value="revenue">Revenue</option>
                            <option value="trend">Trend</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button class="flex-1 bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded font-semibold transition">
                            <i class="fas fa-filter mr-2"></i>Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Top Product Units -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase">Top Product Units</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $topProduct['units'] ?? '1,245' }}</p>
                    </div>
                    <i class="fas fa-box text-blue-500 text-2xl opacity-20"></i>
                </div>
                <div class="flex items-center gap-1 text-green-600 text-sm font-semibold">
                    <i class="fas fa-arrow-up"></i> <span>12% vs previous</span>
                </div>
            </div>

            <!-- Top Product Revenue -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase">Top Product Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">${{ $topProduct['revenue'] ?? '45,230' }}</p>
                    </div>
                    <i class="fas fa-dollar-sign text-purple-500 text-2xl opacity-20"></i>
                </div>
                <div class="flex items-center gap-1 text-green-600 text-sm font-semibold">
                    <i class="fas fa-arrow-up"></i> <span>8% vs previous</span>
                </div>
            </div>

            <!-- Total Products Tracked -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase">Products Tracked</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $productsCount ?? '48' }}</p>
                    </div>
                    <i class="fas fa-cube text-green-500 text-2xl opacity-20"></i>
                </div>
                <div class="text-gray-600 text-sm">
                    <span class="font-semibold">Top 10 shown</span>
                </div>
            </div>

            <!-- Avg Product Revenue -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-gray-600 text-sm font-semibold uppercase">Avg Product Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">${{ $avgRevenue ?? '3,245' }}</p>
                    </div>
                    <i class="fas fa-chart-bar text-orange-500 text-2xl opacity-20"></i>
                </div>
                <div class="flex items-center gap-1 text-red-600 text-sm font-semibold">
                    <i class="fas fa-arrow-down"></i> <span>3% vs previous</span>
                </div>
            </div>
        </div>

        <!-- Ranked Products List & Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Products Table -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white px-6 py-4">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-list"></i> Ranked Product List
                    </h2>
                    <p class="text-slate-300 text-sm mt-1">Top selling products by units and revenue</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">#</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Product Name</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Units</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Revenue</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Trend</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">% of Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <!-- Row 1 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 font-bold rounded-full text-sm">1</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Premium Wireless Headphones</div>
                                    <div class="text-sm text-gray-600">Electronics</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">1,245</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$45,230</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-green-600">
                                        <i class="fas fa-arrow-up text-sm"></i>
                                        <span class="text-sm font-semibold">12%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">18%</td>
                            </tr>

                            <!-- Row 2 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full text-sm">2</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Smart Watch Pro</div>
                                    <div class="text-sm text-gray-600">Electronics</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">987</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$38,450</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-green-600">
                                        <i class="fas fa-arrow-up text-sm"></i>
                                        <span class="text-sm font-semibold">8%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">15%</td>
                            </tr>

                            <!-- Row 3 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full text-sm">3</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Cotton T-Shirt Bundle</div>
                                    <div class="text-sm text-gray-600">Fashion</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">2,156</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$21,560</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-red-600">
                                        <i class="fas fa-arrow-down text-sm"></i>
                                        <span class="text-sm font-semibold">5%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">8%</td>
                            </tr>

                            <!-- Row 4 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full text-sm">4</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Yoga Mat Set</div>
                                    <div class="text-sm text-gray-600">Sports</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">1,834</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$18,340</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-green-600">
                                        <i class="fas fa-arrow-up text-sm"></i>
                                        <span class="text-sm font-semibold">22%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">7%</td>
                            </tr>

                            <!-- Row 5 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full text-sm">5</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Desk Lamp LED</div>
                                    <div class="text-sm text-gray-600">Home & Garden</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">1,456</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$29,120</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-green-600">
                                        <i class="fas fa-arrow-up text-sm"></i>
                                        <span class="text-sm font-semibold">15%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">11%</td>
                            </tr>

                            <!-- Row 6 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full text-sm">6</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Running Shoes Classic</div>
                                    <div class="text-sm text-gray-600">Sports</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">892</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$17,840</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-red-600">
                                        <i class="fas fa-arrow-down text-sm"></i>
                                        <span class="text-sm font-semibold">8%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">7%</td>
                            </tr>

                            <!-- Row 7 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full text-sm">7</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Winter Jacket</div>
                                    <div class="text-sm text-gray-600">Fashion</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">734</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$26,424</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-green-600">
                                        <i class="fas fa-arrow-up text-sm"></i>
                                        <span class="text-sm font-semibold">18%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">10%</td>
                            </tr>

                            <!-- Row 8 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full text-sm">8</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Bluetooth Speaker</div>
                                    <div class="text-sm text-gray-600">Electronics</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">678</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$13,560</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-red-600">
                                        <i class="fas fa-arrow-down text-sm"></i>
                                        <span class="text-sm font-semibold">3%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">5%</td>
                            </tr>

                            <!-- Row 9 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full text-sm">9</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Plant Pot Set</div>
                                    <div class="text-sm text-gray-600">Home & Garden</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">1,012</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$10,120</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-green-600">
                                        <i class="fas fa-arrow-up text-sm"></i>
                                        <span class="text-sm font-semibold">24%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">4%</td>
                            </tr>

                            <!-- Row 10 -->
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-700 font-bold rounded-full text-sm">10</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">Phone Screen Protector</div>
                                    <div class="text-sm text-gray-600">Electronics</div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">2,345</td>
                                <td class="px-6 py-4 text-right font-semibold text-gray-900">$4,690</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1 text-red-600">
                                        <i class="fas fa-arrow-down text-sm"></i>
                                        <span class="text-sm font-semibold">6%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-900">2%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white px-6 py-4">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-chart-bar"></i> Units Sold Chart
                    </h2>
                    <p class="text-slate-300 text-sm mt-1">Top 10 products by units</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Product 1 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">Premium Headphones</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-400 h-full w-full flex items-center justify-end pr-2" style="width: 100%;">
                                        <span class="text-white font-bold text-xs">1,245</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">Smart Watch Pro</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-purple-500 to-purple-400 h-full flex items-center justify-end pr-2" style="width: 79%;">
                                        <span class="text-white font-bold text-xs">987</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 3 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">T-Shirt Bundle</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-green-500 to-green-400 h-full w-full flex items-center justify-end pr-2" style="width: 100%;">
                                        <span class="text-white font-bold text-xs">2,156</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 4 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">Yoga Mat Set</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-orange-500 to-orange-400 h-full flex items-center justify-end pr-2" style="width: 85%;">
                                        <span class="text-white font-bold text-xs">1,834</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 5 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">Desk Lamp LED</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-red-500 to-red-400 h-full flex items-center justify-end pr-2" style="width: 68%;">
                                        <span class="text-white font-bold text-xs">1,456</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 6 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">Running Shoes</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-pink-500 to-pink-400 h-full flex items-center justify-end pr-2" style="width: 36%;">
                                        <span class="text-white font-bold text-xs">892</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 7 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">Winter Jacket</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-400 h-full flex items-center justify-end pr-2" style="width: 30%;">
                                        <span class="text-white font-bold text-xs">734</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 8 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">Bluetooth Speaker</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-cyan-500 to-cyan-400 h-full flex items-center justify-end pr-2" style="width: 27%;">
                                        <span class="text-white font-bold text-xs">678</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 9 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">Plant Pot Set</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-lime-500 to-lime-400 h-full flex items-center justify-end pr-2" style="width: 41%;">
                                        <span class="text-white font-bold text-xs">1,012</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 10 -->
                        <div class="flex items-center gap-3">
                            <div class="w-20 text-xs font-semibold text-gray-700 truncate">Screen Protector</div>
                            <div class="flex-1">
                                <div class="bg-gray-200 rounded-full h-6 overflow-hidden">
                                    <div class="bg-gradient-to-r from-amber-500 to-amber-400 h-full w-full flex items-center justify-end pr-2" style="width: 100%;">
                                        <span class="text-white font-bold text-xs">2,345</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Chart Tip:</strong> Hover over bars to see details
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Breakdown Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue by Category -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white px-6 py-4">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="fas fa-folder"></i> Revenue by Category
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Electronics -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="font-semibold text-gray-900">Electronics</p>
                                <p class="text-sm text-gray-600">3 top products</p>
                            </div>
                            <p class="text-lg font-bold text-blue-600">$97,240</p>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 42%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">42% of total revenue</p>
                    </div>

                    <!-- Fashion -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="font-semibold text-gray-900">Fashion</p>
                                <p class="text-sm text-gray-600">2 top products</p>
                            </div>
                            <p class="text-lg font-bold text-purple-600">$47,984</p>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full" style="width: 21%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">21% of total revenue</p>
                    </div>

                    <!-- Home & Garden -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="font-semibold text-gray-900">Home & Garden</p>
                                <p class="text-sm text-gray-600">2 top products</p>
                            </div>
                            <p class="text-lg font-bold text-green-600">$39,240</p>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 17%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">17% of total revenue</p>
                    </div>

                    <!-- Sports -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <div>
                                <p class="font-semibold text-gray-900">Sports</p>
                                <p class="text-sm text-gray-600">3 top products</p>
                            </div>
                            <p class="text-lg font-bold text-orange-600">$36,324</p>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: 16%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">16% of total revenue</p>
                    </div>
                </div>
            </div>

            <!-- Key Insights -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white px-6 py-4">
                    <h2 class="text-lg font-bold flex items-center gap-2">
                        <i class="fas fa-lightbulb"></i> Key Insights
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Insight 1 -->
                    <div class="flex gap-4 pb-4 border-b border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-100">
                                <i class="fas fa-crown text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Top Performer</p>
                            <p class="text-sm text-gray-600">Premium Wireless Headphones leads with $45,230 (18% of total)</p>
                        </div>
                    </div>

                    <!-- Insight 2 -->
                    <div class="flex gap-4 pb-4 border-b border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-green-100">
                                <i class="fas fa-arrow-trend-up text-green-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Fastest Growing</p>
                            <p class="text-sm text-gray-600">Plant Pot Set rising fast with 24% growth week-over-week</p>
                        </div>
                    </div>

                    <!-- Insight 3 -->
                    <div class="flex gap-4 pb-4 border-b border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-orange-100">
                                <i class="fas fa-chart-pie text-orange-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Category Leader</p>
                            <p class="text-sm text-gray-600">Electronics dominates at 42% of total revenue (4 products)</p>
                        </div>
                    </div>

                    <!-- Insight 4 -->
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-red-100">
                                <i class="fas fa-warning text-red-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Watch Out</p>
                            <p class="text-sm text-gray-600">Running Shoes Classic declining at 8%, consider promotions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center text-blue-700">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Last Updated:</strong> Today at 3:45 PM | <strong>Data Range:</strong> Last 30 Days | <strong>Products Analyzed:</strong> Top 10 of 48
        </div>
    </div>
</div>
@endsection