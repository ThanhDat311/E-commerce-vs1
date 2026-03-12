<x-admin-layout :pageTitle="'Low Stock Alerts'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Low Stock Alerts' => route('admin.low-stock-alerts.index')]">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Low Stock Alerts</h1>
            <p class="text-sm text-gray-500 mt-1">Monitor and action products that need restocking.</p>
        </div>
        <a href="{{ route('admin.low-stock-alerts.export', request()->all()) }}"
           class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export CSV
        </a>
    </div>

    {{-- Summary Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Alerts</p>
            <p class="text-2xl font-bold text-gray-900">{{ $total }}</p>
        </div>
        <div class="bg-red-50 rounded-xl border border-red-200 shadow-sm p-4">
            <p class="text-xs font-medium text-red-600 uppercase tracking-wide mb-1">🔴 Critical</p>
            <p class="text-2xl font-bold text-red-700">{{ $critical }}</p>
            <p class="text-xs text-red-400 mt-0.5">Immediate action needed</p>
        </div>
        <div class="bg-amber-50 rounded-xl border border-amber-200 shadow-sm p-4">
            <p class="text-xs font-medium text-amber-600 uppercase tracking-wide mb-1">🟡 Warning</p>
            <p class="text-2xl font-bold text-amber-700">{{ $warning }}</p>
            <p class="text-xs text-amber-400 mt-0.5">Order soon</p>
        </div>
        <div class="bg-blue-50 rounded-xl border border-blue-200 shadow-sm p-4">
            <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">🔵 Low</p>
            <p class="text-2xl font-bold text-blue-700">{{ $low }}</p>
            <p class="text-xs text-blue-400 mt-0.5">Monitor closely</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-col sm:flex-row gap-3">
        <form method="GET" action="{{ route('admin.low-stock-alerts.index') }}" class="flex flex-wrap items-center gap-2">

            {{-- Status Filter --}}
            <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-200 text-sm text-gray-700 px-3 py-2 bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="" {{ $statusFilter === '' ? 'selected' : '' }}>All Status</option>
                <option value="critical" {{ $statusFilter === 'critical' ? 'selected' : '' }}>Critical</option>
                <option value="warning" {{ $statusFilter === 'warning' ? 'selected' : '' }}>Warning</option>
                <option value="low" {{ $statusFilter === 'low' ? 'selected' : '' }}>Low</option>
            </select>

            {{-- Category Filter --}}
            <select name="category" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-200 text-sm text-gray-700 px-3 py-2 bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="" {{ $categoryFilter === '' ? 'selected' : '' }}>All Categories</option>
                <option value="electronics" {{ $categoryFilter === 'electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="fashion" {{ $categoryFilter === 'fashion' ? 'selected' : '' }}>Fashion</option>
                <option value="home" {{ $categoryFilter === 'home' ? 'selected' : '' }}>Home & Garden</option>
                <option value="sports" {{ $categoryFilter === 'sports' ? 'selected' : '' }}>Sports</option>
            </select>

            {{-- Sort --}}
            <select name="sort" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-200 text-sm text-gray-700 px-3 py-2 bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                <option value="urgency" {{ $sortFilter === 'urgency' ? 'selected' : '' }}>Sort: Urgency</option>
                <option value="stock" {{ $sortFilter === 'stock' ? 'selected' : '' }}>Sort: Stock (Low–High)</option>
                <option value="name" {{ $sortFilter === 'name' ? 'selected' : '' }}>Sort: Name (A–Z)</option>
                <option value="restock" {{ $sortFilter === 'restock' ? 'selected' : '' }}>Sort: Restock Qty</option>
            </select>

            {{-- Search --}}
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ $searchFilter }}"
                       placeholder="Search product..."
                       class="pl-9 pr-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
        </form>
    </div>

    {{-- Products Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Product</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Category</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Status</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Stock Level</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Current / Threshold</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Restock Qty</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($products as $product)
                        @php
                            $statusConfig = match($product['status']) {
                                'critical' => ['bg' => 'bg-red-100',  'text' => 'text-red-700',  'bar' => 'bg-red-500',  'label' => 'Critical'],
                                'warning'  => ['bg' => 'bg-amber-100','text' => 'text-amber-700','bar' => 'bg-amber-500','label' => 'Warning'],
                                default    => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'bar' => 'bg-blue-400', 'label' => 'Low'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">

                            {{-- Product Name --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $product['name'] }}</p>
                                        <p class="text-xs text-gray-400">ID #{{ $product['id'] }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="px-5 py-4">
                                <span class="capitalize text-gray-600">{{ $product['category'] }}</span>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig['bar'] }}"></span>
                                    {{ $statusConfig['label'] }}
                                </span>
                            </td>

                            {{-- Stock Bar --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex flex-col items-center gap-1 min-w-[100px]">
                                    <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="{{ $statusConfig['bar'] }} h-2 rounded-full"
                                             style="width: {{ $product['level_percentage'] }}%"></div>
                                    </div>
                                    <span class="text-xs {{ $statusConfig['text'] }} font-semibold">{{ $product['level_percentage'] }}%</span>
                                </div>
                            </td>

                            {{-- Current / Threshold --}}
                            <td class="px-5 py-4 text-center">
                                <span class="font-bold text-gray-900">{{ $product['current_stock'] }}</span>
                                <span class="text-gray-400 mx-1">/</span>
                                <span class="text-gray-500">{{ $product['min_threshold'] }}</span>
                            </td>

                            {{-- Restock Qty --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-700 text-xs font-semibold rounded">
                                    +{{ $product['restock_qty'] }} units
                                </span>
                            </td>

                            {{-- Action --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end">
                                    <a href="{{ route('admin.products.index') }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                        </svg>
                                        Manage
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-green-50 rounded-full">
                                        <svg class="w-10 h-10 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">All stocked up!</p>
                                        <p class="text-xs text-gray-400 mt-1">No products matching the current filters.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>
