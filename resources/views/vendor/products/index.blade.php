@extends('layouts.vendor')

@section('title', 'My Products')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Products</h1>
            <p class="text-gray-500 text-sm mt-1">Manage your product catalog</p>
        </div>
        <x-admin.button :href="route('vendor.products.create')" icon="plus">
            Add Product
        </x-admin.button>
    </div>

    {{-- Note: Success/Error alerts are handled by the layout notification system --}}

    <x-admin.table>
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        @if($product->image_url)
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="h-10 w-10 object-cover rounded shadow-sm mr-3">
                        @else
                        <div class="h-10 w-10 bg-gray-100 rounded flex items-center justify-center mr-3 text-gray-400">
                            <i class="fas fa-image"></i>
                        </div>
                        @endif
                        <div>
                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                            @if($product->category)
                            <small class="text-gray-500">{{ $product->category->name }}</small>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <code class="bg-gray-100 px-2 py-0.5 rounded text-gray-700">{{ $product->sku ?? 'N/A' }}</code>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                    ${{ number_format($product->price, 2) }}
                </td>
                <td class="px-6 py-4">
                    @if($product->stock_quantity <= 0)
                        <span class="text-red-500 text-sm font-bold">Out of Stock</span>
                    @else
                        <span class="{{ $product->stock_quantity < 10 ? 'text-orange-500' : 'text-gray-900' }} text-sm">
                            {{ $product->stock_quantity }} {{ Str::plural('unit', $product->stock_quantity) }}
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($product->stock_quantity < 10 && $product->stock_quantity > 0)
                        <x-admin.badge variant="warning">Low Stock</x-admin.badge>
                    @elseif($product->stock_quantity == 0)
                        <x-admin.badge variant="critical">Out of Stock</x-admin.badge>
                    @elseif($product->is_featured)
                        <x-admin.badge variant="info">Featured</x-admin.badge>
                    @else
                        <x-admin.badge variant="success">Active</x-admin.badge>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <x-admin.button variant="warning" size="sm" :href="route('vendor.products.edit', $product->id)" icon="edit" />
                        
                        <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <x-admin.button type="submit" variant="danger" size="sm" icon="trash" />
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                    <div class="flex flex-col items-center">
                        <div class="bg-gray-100 p-4 rounded-full mb-3">
                            <i class="fas fa-box-open text-3xl text-gray-400"></i>
                        </div>
                        <p class="mb-2">No products found</p>
                        <x-admin.button :href="route('vendor.products.create')" size="sm" variant="ghost">
                            Create your first product
                        </x-admin.button>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </x-admin.table>

    @if($products->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 rounded-lg shadow-sm">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection