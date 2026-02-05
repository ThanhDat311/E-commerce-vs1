@extends('layouts.vendor')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="text-gray-500 text-sm mt-1">
                Placed on {{ $order->created_at->format('M d, Y \a\t H:i') }}
            </p>
        </div>
        <a href="{{ route('vendor.orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
             &larr; Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Order Items & Customer Info (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Order Items --}}
            <x-admin.card title="Order Items (Your Products)">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                $vendorTotal = 0;
                            @endphp
                            @foreach($order->orderItems as $item)
                                @if($item->product->vendor_id === Auth::id())
                                    @php $vendorTotal += $item->total; @endphp
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($item->product->image_url)
                                                    <img class="h-10 w-10 rounded object-cover mr-3" src="{{ asset($item->product->image_url) }}" alt="">
                                                @else 
                                                    <div class="h-10 w-10 bg-gray-100 rounded flex items-center justify-center mr-3">
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                    <div class="text-xs text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${{ number_format($item->price, 2) }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                            ${{ number_format($item->total, 2) }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-4 py-3 text-right text-sm font-bold text-gray-900">Your Earning Total:</td>
                                <td class="px-4 py-3 text-right text-sm font-bold text-gray-900">${{ number_format($vendorTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-admin.card>

            {{-- Customer Details --}}
            <x-admin.card title="Customer Details">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Contact Info</h4>
                        <div class="text-sm text-gray-900">
                            <p class="font-medium">{{ $order->first_name ?? $order->user->name }} {{ $order->last_name ?? '' }}</p>
                            <p><a href="mailto:{{ $order->email }}" class="text-indigo-600 hover:underline">{{ $order->email }}</a></p>
                            <p>{{ $order->phone_number ?? $order->user->phone_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Shipping Address</h4>
                        <div class="text-sm text-gray-900">
                            <p>{{ $order->shipping_address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </x-admin.card>

        </div>

        {{-- Right Column: Status & History (1/3) --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Update Status --}}
            <x-admin.card title="Update Order Status">
                <form action="{{ route('vendor.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <x-vendor.select-input name="order_status" class="w-full">
                            <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </x-vendor.select-input>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Note (Optional)</label>
                        <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Add a note about this status change..."></textarea>
                    </div>

                    <x-admin.button type="submit" variant="primary" size="md" class="w-full justify-center">
                        Update Status
                    </x-admin.button>
                </form>
            </x-admin.card>

            {{-- Order History --}}
            <x-admin.card title="Order History">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($histories as $history)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white">
                                            <i class="fas fa-history text-white text-xs"></i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                Status changed to <span class="font-medium text-gray-900">{{ ucfirst($history->new_status) }}</span>
                                            </p>
                                            @if($history->notes)
                                            <p class="mt-1 text-xs text-gray-400">"{{ $history->notes }}"</p>
                                            @endif
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $history->created_at }}">{{ $history->created_at->format('M d, H:i') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </x-admin.card>

        </div>
    </div>
</div>
@endsection
