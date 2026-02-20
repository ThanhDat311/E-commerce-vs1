<x-vendor-layout :page-title="'Order Details #' . $order->id" :breadcrumbs="['Vendor' => route('vendor.dashboard'), 'Orders' => route('vendor.orders.index'), '#' . $order->id => '#']">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Order Details') }} <span class="text-gray-500">#{{ $order->id }}</span></h1>
            @php
                $statusVariant = match($order->order_status) {
                    'completed', 'delivered' => 'success',
                    'processing', 'shipped' => 'info',
                    'cancelled' => 'danger',
                    'pending' => 'warning',
                    default => 'neutral'
                };
            @endphp
            <x-ui.badge :variant="$statusVariant" :dot="true" size="lg">
                {{ ucfirst($order->order_status) }}
            </x-ui.badge>
        </div>
        <a href="{{ route('vendor.orders.index') }}">
            <x-ui.button variant="secondary">
                &larr; Back to Orders
            </x-ui.button>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Order Items
                    </h3>
                    <div class="overflow-x-auto">
                        <x-ui.table>
                            <x-slot:thead>
                                <th class="px-6 py-3 font-semibold">Product</th>
                                <th class="px-6 py-3 font-semibold text-right">Price</th>
                                <th class="px-6 py-3 font-semibold text-center">Qty</th>
                                <th class="px-6 py-3 font-semibold text-right">Total</th>
                            </x-slot:thead>
                            <x-slot:tbody>
                                @foreach($order->orderItems as $item)
                                    <tr class="{{ $item->product->vendor_id === auth()->id() ? 'bg-indigo-50/50' : 'opacity-75 bg-gray-50/50' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($item->product->image_url)
                                                        <img class="h-10 w-10 rounded-lg object-cover ring-1 ring-gray-200" src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center ring-1 ring-gray-200">
                                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->product->name }}
                                                    </div>
                                                    @if($item->product->vendor_id === auth()->id())
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                            Your Product
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-gray-500 italic">Other Vendor</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                            ${{ number_format($item->price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                            ${{ number_format($item->price * $item->quantity, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot:tbody>
                        </x-ui.table>
                    </div>
                </div>
            </div>

            <!-- Order History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Order History
                    </h3>
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse($histories as $history)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Status updated to <span class="font-medium text-gray-900">{{ ucfirst($history->new_status) }}</span></p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    <time datetime="{{ $history->created_at }}">{{ $history->created_at->format('M d, H:i') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                        @if($history->notes)
                                            <div class="ml-12 mt-1 text-sm text-gray-500 bg-gray-50 p-2 rounded">
                                                {{ $history->notes }}
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500 pl-4">No history available.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Customer
                    </h3>
                    <div class="text-sm text-gray-600 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="font-medium text-gray-900 w-16">Name:</div>
                            <div>{{ $order->first_name }} {{ $order->last_name }}</div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="font-medium text-gray-900 w-16">Email:</div>
                            <div class="break-all">{{ $order->email }}</div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="font-medium text-gray-900 w-16">Phone:</div>
                            <div>{{ $order->phone_number }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Shipping
                    </h3>
                    <p class="text-sm text-gray-600 leading-relaxed bg-gray-50 p-3 rounded-md">
                        {{ $order->shipping_address }}
                    </p>
                </div>
            </div>

            <!-- Order Status Update -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 border-l-4 border-l-indigo-500">
                <div class="p-6 bg-white border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status</h3>
                    <form method="POST" action="{{ route('vendor.orders.updateStatus', $order->id) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="order_status" :value="__('Change Status')" />
                                <select id="order_status" name="order_status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            
                            <div>
                                <x-input-label for="notes" :value="__('Notes (Optional)')" />
                                <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Reason for status change..."></textarea>
                            </div>

                            <x-ui.button type="submit" variant="primary" class="w-full justify-center">
                                Update Status
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-vendor-layout>
