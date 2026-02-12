<x-admin-layout>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Products Management</h1>
        <a href="{{ route('admin.products.create') }}">
            <x-ui.button variant="primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Product
            </x-ui.button>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <div class="p-6">
            <x-ui.table>
                <x-slot:thead>
                    <th scope="col" class="px-6 py-3">Image</th>
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">SKU</th>
                    <th scope="col" class="px-6 py-3">Category</th>
                    <th scope="col" class="px-6 py-3">Price</th>
                    <th scope="col" class="px-6 py-3">Stock</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </x-slot:thead>
                <x-slot:tbody>
                    @forelse($products as $product)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if($product->image_url)
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $product->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $product->sku }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $product->category->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-gray-900">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $stockVariant = $product->stock_quantity > 10 ? 'success' : ($product->stock_quantity > 0 ? 'pending' : 'danger');
                                @endphp
                                <x-ui.badge :variant="$stockVariant">
                                    {{ $product->stock_quantity }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4">
                                @if($product->is_featured)
                                    <x-ui.badge variant="success">Featured</x-ui.badge>
                                @endif
                                @if($product->is_new)
                                    <x-ui.badge variant="pending">New</x-ui.badge>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}">
                                        <x-ui.button variant="secondary" size="sm">
                                            Edit
                                        </x-ui.button>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="danger" size="sm">
                                            Delete
                                        </x-ui.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                No products found. <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:underline">Create your first product</a>
                            </td>
                        </tr>
                    @endforelse
                </x-slot:tbody>
            </x-ui.table>
        </div>

        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
