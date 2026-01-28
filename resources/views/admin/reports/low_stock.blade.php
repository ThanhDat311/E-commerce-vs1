@extends('layouts.admin')
@section('title', 'Low Stock Alert')

@section('content')
<x-admin.header 
    title="Low Stock Alert" 
    subtitle="Products with stock quantity <= 10. Please restock soon!"
    icon="exclamation-triangle"
    background="red"
/>

<div class="max-w-7xl mx-auto px-6 py-8">
    <x-admin.card variant="red" border="left" borderColor="red">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-red-900 to-red-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Product</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">SKU</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Current Stock</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowStockProducts as $product)
                <tr class="border-b border-gray-200 hover:bg-red-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset($product->image_url) }}" width="40" class="rounded-md object-cover">
                            <span class="font-medium">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $product->sku }}</td>
                    <td class="px-6 py-4 text-sm font-semibold {{ $product->stock_quantity == 0 ? 'text-red-600' : 'text-orange-600' }}">
                        {{ $product->stock_quantity }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($product->stock_quantity == 0)
                            <x-admin.badge variant="critical" animated>Out of Stock</x-admin.badge>
                        @else
                            <x-admin.badge variant="warning">Low Stock</x-admin.badge>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700 transition">
                            <i class="fas fa-plus"></i> Restock
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-600">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-check-circle text-3xl text-green-600"></i>
                            <p class="font-medium">Good news! No products are low in stock.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>
</div>
@endsection