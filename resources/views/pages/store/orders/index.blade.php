<x-base-layout>
    <x-store.navbar />

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-gray-500 text-sm">
                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                <span class="mx-2">&rsaquo;</span>
                <a href="{{ route('profile.edit') }}" class="hover:text-gray-900">Account</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900 font-medium">Order History</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar -->
                <div class="w-full md:w-1/4 flex-shrink-0">
                    @include('profile.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="flex-1 min-w-0">
                     <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <!-- Tabs -->
                        <div class="border-b border-gray-200 overflow-x-auto">
                            <nav class="flex -mb-px">
                                @php
                                    $statuses = [
                                        'all' => 'All',
                                        'to_pay' => 'To Pay',
                                        'to_ship' => 'To Ship',
                                        'to_receive' => 'To Receive',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ];
                                    $currentStatus = request('status', 'all');
                                @endphp

                                @foreach ($statuses as $key => $label)
                                    <a href="{{ route('orders.index', ['status' => $key]) }}"
                                       class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200
                                       {{ $currentStatus === $key
                                            ? 'border-orange-500 text-orange-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>

                        <!-- Search -->
                        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                            <form action="{{ route('orders.index') }}" method="GET" class="relative">
                                <input type="hidden" name="status" value="{{ $currentStatus }}">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-orange-300 focus:shadow-outline-orange sm:text-sm transition duration-150 ease-in-out"
                                       placeholder="Search by Order ID or Product Name">
                            </form>
                        </div>

                        <!-- Order List -->
                        <div class="divide-y divide-gray-100">
                            @forelse ($orders as $order)
                                <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                                        <div class="flex items-center gap-4 text-sm text-gray-500">
                                            <span class="font-medium text-gray-900">Order #{{ $order->id }}</span>
                                            <span>|</span>
                                            <span>Placed on {{ $order->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="mt-2 md:mt-0">
                                             @php
                                                $statusColors = [
                                                    'pending' => 'text-yellow-600 bg-yellow-100',
                                                    'confirmed' => 'text-blue-600 bg-blue-100',
                                                    'processing' => 'text-orange-600 bg-orange-100',
                                                    'shipped' => 'text-purple-600 bg-purple-100',
                                                    'delivered' => 'text-green-600 bg-green-100',
                                                    'cancelled' => 'text-red-600 bg-red-100',
                                                    'refunded' => 'text-red-600 bg-red-100',
                                                    'failed' => 'text-red-600 bg-red-100',
                                                ];
                                                $colorClass = $statusColors[$order->order_status] ?? 'text-gray-600 bg-gray-100';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase {{ $colorClass }}">
                                                {{ $order->order_status }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Order Items Preview (First item) -->
                                    <div class="flex items-start gap-4">
                                        @if($order->orderItems->count() > 0)
                                            @php $firstItem = $order->orderItems->first(); @endphp
                                            <div class="flex-shrink-0 h-20 w-20 border border-gray-200 rounded-lg overflow-hidden bg-gray-100">
                                                <img src="{{ $firstItem->product->image_url ?? 'https://via.placeholder.com/80' }}"
                                                     alt="{{ $firstItem->product->name }}"
                                                     class="h-full w-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $firstItem->product->name }}
                                                </h4>
                                                <p class="text-sm text-gray-500">
                                                    {{ $firstItem->variant->name ?? '' }}
                                                </p>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    x{{ $firstItem->quantity }}
                                                </p>
                                            </div>
                                        @else
                                            <div class="text-sm text-gray-500">No items</div>
                                        @endif
                                        
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">Total Amount</p>
                                            <p class="text-lg font-bold text-orange-600">
                                                {{ number_format($order->total, 2) }} {{ $order->currency ?? 'USD' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="mt-4 flex justify-end gap-3 pt-4 border-t border-gray-50 border-dashed">
                                        @if($order->order_status === 'shipped')
                                            <button class="px-4 py-2 bg-blue-50 text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-100 transition-colors">
                                                Track Order
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('orders.show', $order) }}" 
                                           class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                            View Details
                                        </a>

                                        @if(in_array($order->order_status, ['delivered', 'cancelled']))
                                            <button class="px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                                                Buy Again
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                                    <p class="mt-1 text-sm text-gray-500">You haven't placed any orders yet.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                            Start Shopping
                                        </a>
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="px-4 py-3 border-t border-gray-20 border-gray-200 bg-gray-50 sm:px-6">
                                {{ $orders->onEachSide(1)->links() }}
                            </div>
                        @endif
                     </div>
                </div>
            </div>
        </div>
    </div>
    
    <x-store.footer />
</x-base-layout>
