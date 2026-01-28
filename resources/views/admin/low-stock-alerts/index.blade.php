@extends('admin.layout.admin')

@section('title', 'Low Stock Alerts')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <x-admin-header
        title="Low Stock Alerts"
        subtitle="Monitor inventory levels & manage restocking"
        icon="exclamation-triangle"
        background="orange">
        <div class="flex gap-2">
            <x-admin-button variant="secondary" size="md">
                <i class="fas fa-cog mr-2"></i> Settings
            </x-admin-button>
            <x-admin-button variant="warning" size="md">
                <i class="fas fa-download mr-2"></i> Export
            </x-admin-button>
        </div>
    </x-admin-header>

    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Header Section - Filter Area -->
        <x-admin-card variant="orange" border="top" borderColor="orange" class="mb-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <x-admin-select label="Status Filter" name="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="critical">Critical (0-50%)</option>
                    <option value="warning">Warning (51-80%)</option>
                    <option value="low">Low (81-100%)</option>
                </x-admin-select>
                <x-admin-select label="Category" name="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="electronics">Electronics</option>
                    <option value="fashion">Fashion</option>
                    <option value="home">Home & Garden</option>
                    <option value="sports">Sports</option>
                </x-admin-select>
                <x-admin-select label="Sort By" name="sortFilter">
                    <option value="urgency">Urgency (Critical First)</option>
                    <option value="stock">Stock Level</option>
                    <option value="name">Product Name</option>
                    <option value="restock">Restock Qty</option>
                </x-admin-select>
                <x-admin-input label="Search" name="searchFilter" placeholder="Product name..." />
                <div class="flex items-end">
                    <x-admin-button variant="warning" size="md" class="w-full justify-center">
                        <i class="fas fa-filter mr-2"></i>Apply
                    </x-admin-button>
                </div>
            </div>
        </x-admin-card>
        <!-- Alert Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <x-admin-metric-card count="{{ $critical ?? 8 }}" label="Critical (0-50%)" variant="red">
                <p class="text-xs text-red-600 font-semibold mt-2"><i class="fas fa-arrow-up"></i> Immediate action needed</p>
            </x-admin-metric-card>

            <x-admin-metric-card count="{{ $warning ?? 14 }}" label="Warning (51-80%)" variant="warning">
                <p class="text-xs text-orange-600 font-semibold mt-2"><i class="fas fa-clock"></i> Plan restocking</p>
            </x-admin-metric-card>

            <x-admin-metric-card count="{{ $low ?? 6 }}" label="Low (81-100%)" variant="yellow">
                <p class="text-xs text-yellow-600 font-semibold mt-2"><i class="fas fa-bell"></i> Monitor closely</p>
            </x-admin-metric-card>

            <x-admin-metric-card count="{{ $total ?? 28 }}" label="Watched Items" variant="blue">
                <p class="text-xs text-blue-600 font-semibold mt-2"><i class="fas fa-chart-pie"></i> Products monitored</p>
            </x-admin-metric-card>
        </div>

        <!-- Alert Table -->
        <x-admin-table title="Inventory Alert Table">
            <thead class="bg-gray-50 border-b border-gray-300">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase">Product Name</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Current Stock</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Min. Threshold</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Stock Level</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Restock Qty</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Critical: Premium Headphones -->
                <tr class="bg-red-50 border-l-4 border-red-600 hover:bg-red-100 transition">
                    <td class="px-6 py-4">
                        <x-admin-badge variant="critical" animated>
                            <i class="fas fa-exclamation-circle mr-1"></i> CRITICAL
                        </x-admin-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">Premium Wireless Headphones</div>
                        <div class="text-sm text-gray-600">Electronics</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-xl font-bold text-red-700">12</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-semibold text-gray-900">50</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-xs">
                                <x-admin-progress-bar color="red" percentage="24" label="24% of minimum" />
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded">+100</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <x-admin-button variant="danger" size="sm">
                            <i class="fas fa-exclamation-circle mr-1"></i> Restock
                        </x-admin-button>
                    </td>
                </tr>

                <!-- Critical: Smart Watch -->
                <tr class="bg-red-50 border-l-4 border-red-600 hover:bg-red-100 transition">
                    <td class="px-6 py-4">
                        <x-admin-badge variant="critical" animated>
                            <i class="fas fa-exclamation-circle mr-1"></i> CRITICAL
                        </x-admin-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">Smart Watch Pro</div>
                        <div class="text-sm text-gray-600">Electronics</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-xl font-bold text-red-700">8</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-semibold text-gray-900">45</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-xs">
                                <x-admin-progress-bar color="red" percentage="18" label="18% of minimum" />
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded">+75</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <x-admin-button variant="danger" size="sm">
                            <i class="fas fa-exclamation-circle mr-1"></i> Restock
                        </x-admin-button>
                    </td>
                </tr>

                <!-- Warning: Cotton T-Shirt Bundle -->
                <tr class="bg-orange-50 border-l-4 border-orange-500 hover:bg-orange-100 transition">
                    <td class="px-6 py-4">
                        <x-admin-badge variant="warning">
                            <i class="fas fa-exclamation mr-1"></i> WARNING
                        </x-admin-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">Cotton T-Shirt Bundle</div>
                        <div class="text-sm text-gray-600">Fashion</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-xl font-bold text-orange-600">125</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-semibold text-gray-900">150</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-xs">
                                <x-admin-progress-bar color="orange" percentage="83" label="83% of minimum" />
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded">+200</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <x-admin-button variant="warning" size="sm">
                            <i class="fas fa-box mr-1"></i> Schedule
                        </x-admin-button>
                    </td>
                </tr>

                <!-- Warning: Yoga Mat Set -->
                <tr class="bg-orange-50 border-l-4 border-orange-500 hover:bg-orange-100 transition">
                    <td class="px-6 py-4">
                        <x-admin-badge variant="warning">
                            <i class="fas fa-exclamation mr-1"></i> WARNING
                        </x-admin-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">Yoga Mat Set</div>
                        <div class="text-sm text-gray-600">Sports</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-xl font-bold text-orange-600">98</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-semibold text-gray-900">120</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-xs">
                                <x-admin-progress-bar color="orange" percentage="82" label="82% of minimum" />
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded">+60</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <x-admin-button variant="warning" size="sm">
                            <i class="fas fa-box mr-1"></i> Schedule
                        </x-admin-button>
                    </td>
                </tr>

                <!-- Low: Running Shoes -->
                <tr class="bg-yellow-50 border-l-4 border-yellow-500 hover:bg-yellow-100 transition">
                    <td class="px-6 py-4">
                        <x-admin-badge variant="info">
                            <i class="fas fa-bell mr-1"></i> LOW
                        </x-admin-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">Running Shoes Classic</div>
                        <div class="text-sm text-gray-600">Sports</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-xl font-bold text-yellow-600">189</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-semibold text-gray-900">200</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-xs">
                                <x-admin-progress-bar color="yellow" percentage="95" label="95% of minimum" />
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">+50</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <x-admin-button size="sm">
                            <i class="fas fa-clock mr-1"></i> Monitor
                        </x-admin-button>
                    </td>
                </tr>

                <!-- Low: Winter Jacket -->
                <tr class="bg-yellow-50 border-l-4 border-yellow-500 hover:bg-yellow-100 transition">
                    <td class="px-6 py-4">
                        <x-admin-badge variant="info">
                            <i class="fas fa-bell mr-1"></i> LOW
                        </x-admin-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">Winter Jacket</div>
                        <div class="text-sm text-gray-600">Fashion</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-xl font-bold text-yellow-600">156</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-semibold text-gray-900">160</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-xs">
                                <x-admin-progress-bar color="yellow" percentage="97" label="97% of minimum" />
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded">+40</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <x-admin-button size="sm">
                            <i class="fas fa-clock mr-1"></i> Monitor
                        </x-admin-button>
                    </td>
                </tr>

                <!-- Critical: Desk Lamp -->
                <tr class="bg-red-50 border-l-4 border-red-600 hover:bg-red-100 transition">
                    <td class="px-6 py-4">
                        <x-admin-badge variant="critical" animated>
                            <i class="fas fa-exclamation-circle mr-1"></i> CRITICAL
                        </x-admin-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">Desk Lamp LED</div>
                        <div class="text-sm text-gray-600">Home & Garden</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-xl font-bold text-red-700">15</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-semibold text-gray-900">60</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-xs">
                                <x-admin-progress-bar color="red" percentage="25" label="25% of minimum" />
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded">+90</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <x-admin-button variant="danger" size="sm">
                            <i class="fas fa-exclamation-circle mr-1"></i> Restock
                        </x-admin-button>
                    </td>
                </tr>

                <!-- Warning: Bluetooth Speaker -->
                <tr class="bg-orange-50 border-l-4 border-orange-500 hover:bg-orange-100 transition">
                    <td class="px-6 py-4">
                        <x-admin-badge variant="warning">
                            <i class="fas fa-exclamation mr-1"></i> WARNING
                        </x-admin-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-900">Bluetooth Speaker</div>
                        <div class="text-sm text-gray-600">Electronics</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="text-xl font-bold text-orange-600">85</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="font-semibold text-gray-900">100</div>
                        <div class="text-xs text-gray-600">units</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center">
                            <div class="w-full max-w-xs">
                                <x-admin-progress-bar color="orange" percentage="85" label="85% of minimum" />
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center font-semibold text-gray-900">
                        <span class="bg-orange-100 text-orange-700 px-2 py-1 rounded">+50</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <x-admin-button variant="warning" size="sm">
                            <i class="fas fa-box mr-1"></i> Schedule
                        </x-admin-button>
                    </td>
                </tr>
            </tbody>
        </x-admin-table>

        <!-- Action Panel -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            <!-- Quick Actions -->
            <x-admin-card variant="white" border="top" borderColor="gray">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-zap text-yellow-500"></i> Quick Actions
                </h2>
                <div class="space-y-3">
                    <x-admin-button variant="danger" class="w-full justify-center">
                        <i class="fas fa-exclamation-circle mr-2"></i> Restock All Critical (8 items)
                    </x-admin-button>
                    <x-admin-button variant="warning" class="w-full justify-center">
                        <i class="fas fa-box mr-2"></i> Schedule Restock - Warning (14 items)
                    </x-admin-button>
                    <x-admin-button variant="primary" class="w-full justify-center">
                        <i class="fas fa-eye mr-2"></i> Review Low Items (6 items)
                    </x-admin-button>
                    <x-admin-button variant="secondary" class="w-full justify-center">
                        <i class="fas fa-cog mr-2"></i> Configure Thresholds
                    </x-admin-button>
                </div>
            </x-admin-card>

            <!-- Alerts & Notifications -->
            <x-admin-card variant="white" border="top" borderColor="gray">
                <h2 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-bell text-blue-500"></i> Recent Activity
                </h2>
                <div class="space-y-4 max-h-80 overflow-y-auto">
                    <!-- Activity 1 -->
                    <div class="flex gap-3 pb-3 border-b border-gray-200">
                        <div class="flex-shrink-0 w-2 h-2 bg-red-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">Critical Alert: Premium Headphones</p>
                            <p class="text-xs text-gray-600">Stock below 50% of minimum threshold</p>
                            <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                        </div>
                    </div>

                    <!-- Activity 2 -->
                    <div class="flex gap-3 pb-3 border-b border-gray-200">
                        <div class="flex-shrink-0 w-2 h-2 bg-red-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">Critical Alert: Smart Watch Pro</p>
                            <p class="text-xs text-gray-600">Stock fell below critical threshold</p>
                            <p class="text-xs text-gray-500 mt-1">15 minutes ago</p>
                        </div>
                    </div>

                    <!-- Activity 3 -->
                    <div class="flex gap-3 pb-3 border-b border-gray-200">
                        <div class="flex-shrink-0 w-2 h-2 bg-orange-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">Warning: T-Shirt Bundle</p>
                            <p class="text-xs text-gray-600">Approaching minimum threshold (83%)</p>
                            <p class="text-xs text-gray-500 mt-1">45 minutes ago</p>
                        </div>
                    </div>

                    <!-- Activity 4 -->
                    <div class="flex gap-3 pb-3 border-b border-gray-200">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">Restock Completed: Yoga Mat Set</p>
                            <p class="text-xs text-gray-600">200 units added to inventory</p>
                            <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                        </div>
                    </div>

                    <!-- Activity 5 -->
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">Threshold Updated: Running Shoes</p>
                            <p class="text-xs text-gray-600">Minimum threshold increased from 180 to 200</p>
                            <p class="text-xs text-gray-500 mt-1">1 day ago</p>
                        </div>
                    </div>
                </div>
            </x-admin-card>
        </div>

        <!-- Info Footer -->
        <x-admin-alert status="critical" title="Warning" dismissible class="mt-8">
            You have <strong>8 critical items</strong> that require immediate restocking. Review and take action to avoid stockouts.
        </x-admin-alert>
    </div>
</div>
@endsection