@extends('layouts.admin')

@section('content')
<!-- Stats Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Total Revenue Card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                <h3 class="text-2xl font-bold text-gray-900">$128,430</h3>
                <p class="text-xs text-gray-500 mt-1">v.s. last 30 days</p>
            </div>
            <div class="bg-green-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm font-semibold text-green-600">+412.5%</p>
    </div>

    <!-- Total Orders Card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">Total Orders</p>
                <h3 class="text-2xl font-bold text-gray-900">1,240</h3>
                <p class="text-xs text-gray-500 mt-1">Active processing orders</p>
            </div>
            <div class="bg-blue-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm font-semibold text-blue-600">+5.2%</p>
    </div>

    <!-- New Users Card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-sm font-medium text-gray-600 mb-1">New Users</p>
                <h3 class="text-2xl font-bold text-gray-900">450</h3>
                <p class="text-xs text-gray-500 mt-1">Customers joined this month</p>
            </div>
            <div class="bg-purple-100 p-2 rounded-lg">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
        </div>
        <p class="text-sm font-semibold text-purple-600">+8.1%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Revenue Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-md border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Revenue Overview</h3>
                <p class="text-sm text-gray-500 mt-1">Last 30 days performance</p>
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-1.5 text-xs font-semibold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">Month</button>
                <button class="px-4 py-1.5 text-xs font-semibold bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Week</button>
            </div>
        </div>
        <div class="h-80 w-full relative">
            <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 800 300">
                <defs>
                    <linearGradient id="chartGradient" x1="0" x2="0" y1="0" y2="1">
                        <stop offset="0%" stop-color="#4f46e5" stop-opacity="0.15"></stop>
                        <stop offset="100%" stop-color="#4f46e5" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
                <path d="M0,250 Q100,220 200,240 T400,100 T600,120 T800,50 L800,300 L0,300 Z" fill="url(#chartGradient)"></path>
                <path d="M0,250 Q100,220 200,240 T400,100 T600,120 T800,50" fill="none" stroke="#4f46e5" stroke-linecap="round" stroke-width="4"></path>
                <circle cx="100" cy="220" fill="#4f46e5" r="5" stroke="white" stroke-width="2"></circle>
                <circle cx="200" cy="240" fill="#4f46e5" r="5" stroke="white" stroke-width="2"></circle>
                <circle cx="400" cy="100" fill="#4f46e5" r="5" stroke="white" stroke-width="2"></circle>
                <circle cx="600" cy="120" fill="#4f46e5" r="5" stroke="white" stroke-width="2"></circle>
                <circle cx="800" cy="50" fill="#4f46e5" r="5" stroke="white" stroke-width="2"></circle>
            </svg>
        </div>
        <div class="mt-4 flex justify-between text-xs text-gray-500 font-medium">
            <span>DAY 1</span>
            <span>DAY 7</span>
            <span>DAY 14</span>
            <span>DAY 21</span>
            <span>DAY 30</span>
        </div>
    </div>

    <!-- System Alerts -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">System Alerts</h3>
            <button class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Mark all read</button>
        </div>
        <div class="space-y-4 flex-1">
            <!-- Alert Item 1 -->
            <div class="flex gap-3 pb-4 border-b border-gray-100 last:border-b-0 last:pb-0">
                <div class="bg-blue-100 p-2 rounded-lg h-fit">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">New order received from John Doe</p>
                    <p class="text-xs text-gray-500 mt-1">2 mins ago • $320.00</p>
                </div>
            </div>

            <!-- Alert Item 2 -->
            <div class="flex gap-3 pb-4 border-b border-gray-100 last:border-b-0 last:pb-0">
                <div class="bg-yellow-100 p-2 rounded-lg h-fit">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 5v1m-8.5-15a9 9 0 1119 0 9 9 0 01-19 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">Low stock alert: Wireless Headphones</p>
                    <p class="text-xs text-gray-500 mt-1">15 mins ago • 3 left</p>
                </div>
            </div>

            <!-- Alert Item 3 -->
            <div class="flex gap-3 pb-4 border-b border-gray-100 last:border-b-0 last:pb-0">
                <div class="bg-green-100 p-2 rounded-lg h-fit">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">New customer registration</p>
                    <p class="text-xs text-gray-500 mt-1">1 hour ago • Sarah Smith</p>
                </div>
            </div>

            <!-- Alert Item 4 -->
            <div class="flex gap-3 pb-4 border-b border-gray-100 last:border-b-0 last:pb-0">
                <div class="bg-red-100 p-2 rounded-lg h-fit">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">Failed login attempt</p>
                    <p class="text-xs text-gray-500 mt-1">2 hours ago • IP: 192.168.1.1</p>
                </div>
            </div>
        </div>
        <button class="mt-4 w-full py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
            View All Notifications
        </button>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden mt-6">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
        <button class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Export CSV</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">#ORD-7721</a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-xs font-bold text-blue-600">SM</div>
                            <span class="font-medium text-gray-900">Sophia Miller</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Oct 24, 2023</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">$320.00</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">✓ Completed</span>
                    </td>
                    <td class="px-6 py-4">
                        <button class="text-gray-400 hover:text-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">#ORD-7720</a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center text-xs font-bold text-yellow-600">JD</div>
                            <span class="font-medium text-gray-900">James Davis</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Oct 24, 2023</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">$89.50</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">⏱ Pending</span>
                    </td>
                    <td class="px-6 py-4">
                        <button class="text-gray-400 hover:text-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">#ORD-7719</a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center text-xs font-bold text-pink-600">EW</div>
                            <span class="font-medium text-gray-900">Emma Wilson</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Oct 23, 2023</td>
                    <td class="px-6 py-4 font-semibold text-gray-900">$1,129.00</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">✓ Completed</span>
                    </td>
                    <td class="px-6 py-4">
                        <button class="text-gray-400 hover:text-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection