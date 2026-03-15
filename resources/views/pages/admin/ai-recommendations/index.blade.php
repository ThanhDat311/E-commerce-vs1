<x-admin-layout :pageTitle="'Product Recommendations'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Product Recommendations' => route('admin.ai.recommendations.index')]">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Product Recommendations</h1>
        <p class="text-sm text-gray-500 mt-1">AI-powered product sales insights and recommendation analytics.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Total Products</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_products']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-5">
            <p class="text-xs font-semibold text-green-500 uppercase tracking-wider mb-1.5">Products Sold</p>
            <h3 class="text-3xl font-bold text-green-700">{{ number_format($stats['products_sold']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
            <p class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1.5">Unsold Products</p>
            <h3 class="text-3xl font-bold text-red-700">{{ number_format($stats['unsold_products']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-5">
            <p class="text-xs font-semibold text-blue-400 uppercase tracking-wider mb-1.5">Top Seller</p>
            <p class="text-sm font-bold text-blue-700 truncate">{{ $stats['top_seller_name'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- Top Selling Products --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                <h2 class="font-semibold text-gray-800">🔥 Top 10 Best Sellers</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topProducts as $i => $product)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <span class="text-xs font-bold text-gray-400 w-6 text-center">{{ $i + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400">${{ number_format($product->price, 2) }}</p>
                        </div>
                        <span class="text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100 px-2 py-0.5 rounded-full">
                            {{ $product->order_items_count }} orders
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">No sales data available yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Unsold Products --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <h2 class="font-semibold text-gray-800">⚠️ Unsold Products (Need Promotion)</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($unsoldProducts as $product)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-400">${{ number_format($product->price, 2) }}</p>
                        </div>
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit →</a>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">All products have at least one order!</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Cross-Sell Insights (most ordered products) --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            <h2 class="font-semibold text-gray-800">🔗 Cross-Sell Candidates</h2>
        </div>
        @if($pairings->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3 text-left">Product</th>
                            <th class="px-5 py-3 text-center">Total Orders</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pairings as $pairing)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-5 py-3 font-medium text-gray-800">{{ $pairing->product?->name ?? 'Unknown' }}</td>
                                <td class="px-5 py-3 text-center">
                                    <span class="bg-purple-50 text-purple-700 border border-purple-100 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $pairing->order_count }}</span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    @if($pairing->product)
                                        <a href="{{ route('admin.products.edit', $pairing->product) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">View Product →</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="px-5 py-8 text-center text-sm text-gray-400">No cross-sell data yet.</p>
        @endif
    </div>

</x-admin-layout>
