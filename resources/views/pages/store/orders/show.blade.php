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
                <a href="{{ route('orders.index') }}" class="hover:text-gray-900">Order History</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900 font-medium">Order #{{ $order->id }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Details (Left Column) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Header -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                                <p class="text-sm text-gray-500 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-indigo-100 text-indigo-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-gray-100 text-gray-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                ];
                                $colorClass = $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>

                         <!-- Action Buttons -->
                        <div class="mt-6 flex flex-wrap gap-3">
                             @if($order->order_status === 'pending')
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors">
                                        Cancel Order
                                    </button>
                                </form>
                                <form action="{{ route('orders.repay', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                                        Pay Now
                                    </button>
                                </form>
                            @endif

                            @if(in_array($order->order_status, ['delivered', 'cancelled']))
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                                    Buy Again
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Order Status Timeline -->
                    @if(!in_array($order->order_status, ['cancelled', 'refunded', 'failed']))
                    @php
                        $timelineSteps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                        $stepLabels = [
                            'pending' => 'Ordered',
                            'confirmed' => 'Confirmed',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                        ];
                        $currentIndex = array_search($order->order_status, $timelineSteps);
                        if ($currentIndex === false) $currentIndex = -1;
                    @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-6">Order Progress</h3>
                        <div class="flex items-center justify-between">
                            @foreach($timelineSteps as $index => $step)
                                <div class="flex flex-col items-center relative {{ $index < count($timelineSteps) - 1 ? 'flex-1' : '' }}">
                                    {{-- Step Circle --}}
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center z-10
                                        {{ $index <= $currentIndex ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-400' }} transition-colors">
                                        @if($index < $currentIndex)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($index === $currentIndex)
                                            <div class="w-2.5 h-2.5 bg-white rounded-full"></div>
                                        @else
                                            <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                        @endif
                                    </div>
                                    {{-- Label --}}
                                    <span class="mt-2 text-xs font-medium {{ $index <= $currentIndex ? 'text-orange-600' : 'text-gray-400' }}">
                                        {{ $stepLabels[$step] }}
                                    </span>
                                </div>
                                {{-- Connector Line --}}
                                @if($index < count($timelineSteps) - 1)
                                    <div class="flex-1 h-0.5 mx-1 mt-[-1.5rem] {{ $index < $currentIndex ? 'bg-orange-500' : 'bg-gray-200' }}"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-medium text-gray-900">Order Items</h2>
                        </div>
                        <ul class="divide-y divide-gray-100">
                            @foreach($order->orderItems as $item)
                                <li class="p-6 flex items-start gap-4">
                                    <div class="flex-shrink-0 h-20 w-20 border border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                                        <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/80' }}"
                                             alt="{{ $item->product->name }}"
                                             class="h-full w-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('shop.show', $item->product->slug ?? '#') }}" class="hover:underline">
                                                {{ $item->product->name }}
                                            </a>
                                        </h3>
                                        @if($item->variant)
                                            <p class="text-sm text-gray-500 mt-1">{{ $item->variant->name }}</p>
                                        @endif
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <span>Quantity: {{ $item->quantity }}</span>
                                            <span class="mx-2">&bull;</span>
                                            <span>{{ number_format($item->price, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right font-medium text-gray-900">
                                        {{ number_format($item->total, 2) }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Order Summary & Address (Right Column) -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Shipping Address -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Shipping Address</h2>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p class="font-medium text-gray-900">{{ $order->first_name }} {{ $order->last_name }}</p>
                            <p>{{ $order->address }}</p>
                            <!-- City/Country not in fillable, assuming address has full info or just address field -->
                            <p class="mt-2">{{ $order->phone }}</p>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                         <h2 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h2>
                         <div class="flex justify-between items-center text-sm mb-2">
                             <span class="text-gray-500">Payment Method</span>
                             <span class="font-medium text-gray-900">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                         </div>
                         <div class="flex justify-between items-center text-sm">
                             <span class="text-gray-500">Payment Status</span>
                              @php
                                $paymentColors = [
                                    'pending' => 'text-yellow-600',
                                    'paid' => 'text-green-600',
                                    'failed' => 'text-red-600',
                                    'refunded' => 'text-gray-600',
                                ];
                                $payColor = $paymentColors[$order->payment_status] ?? 'text-gray-600';
                            @endphp
                             <span class="font-medium {{ $payColor }}">{{ ucfirst($order->payment_status) }}</span>
                         </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>
                        <div class="space-y-3 text-sm border-b border-gray-100 pb-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>{{ number_format($order->orderItems->sum('total'), 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping Fee</span>
                                <span>{{ number_format($order->shipping_fee ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax</span>
                                <span>{{ number_format($order->tax_amount ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Discount</span>
                                <span class="text-green-600">-{{ number_format($order->discount_amount ?? 0, 2) }}</span>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-between items-center">
                            <span class="text-base font-bold text-gray-900">Total</span>
                            <span class="text-xl font-bold text-blue-600">
                                {{ number_format($order->total, 2) }} {{ $order->currency ?? 'USD' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-store.footer />
</x-base-layout>
