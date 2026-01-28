@extends('layouts.admin')
@section('title', 'Top Selling Products')

@section('content')
<x-admin.header 
    title="Top Selling Products" 
    subtitle="Top 20 products by quantity sold."
    icon="trophy"
    background="orange"
/>

<div class="max-w-7xl mx-auto px-6 py-8">
    <x-admin.card variant="white" border="left" borderColor="orange">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-900 to-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">#</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Product Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Category</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Sold Qty</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $index => $item)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="flex items-center gap-3">
                            @if($item->product)
                                <img src="{{ asset($item->product->image_url) }}" width="40" class="rounded-md object-cover">
                                <span class="font-medium">{{ $item->product->name }}</span>
                            @else
                                <span class="text-red-600 font-medium">Product Deleted (ID: {{ $item->product_id }})</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $item->product->category->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <x-admin.badge variant="info">{{ $item->total_sold }} sold</x-admin.badge>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-green-600">
                        ${{ number_format($item->total_revenue, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-600">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-3xl text-gray-400"></i>
                            <p class="font-medium">No sales data available yet.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>
</div>
@endsection