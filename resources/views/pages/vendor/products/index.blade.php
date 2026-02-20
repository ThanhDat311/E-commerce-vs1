<x-vendor-layout :page-title="'My Products'" :breadcrumbs="['Vendor' => route('vendor.dashboard'), 'Products' => route('vendor.products.index')]">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('My Products') }}</h1>
        <a href="{{ route('vendor.products.create') }}">
            <x-ui.button variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4m0 0v16"/>
                </svg>
                Add Product
            </x-ui.button>
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <x-ui.table>
            <x-slot:thead>
                <th scope="col" class="px-6 py-3 font-semibold">Product</th>
                <th scope="col" class="px-6 py-3 font-semibold">Category</th>
                <th scope="col" class="px-6 py-3 font-semibold">Price</th>
                <th scope="col" class="px-6 py-3 font-semibold">Stock</th>
                <th scope="col" class="px-6 py-3 font-semibold">Status</th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Actions</span>
                </th>
            </x-slot:thead>
            <x-slot:tbody>
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($product->image_url)
                                        <img class="h-10 w-10 rounded-lg object-cover ring-1 ring-gray-200" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center ring-1 ring-gray-200">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">SKU: {{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <x-ui.badge :variant="$product->stock_quantity > 10 ? 'success' : ($product->stock_quantity > 0 ? 'warning' : 'danger')" :dot="true">
                                    {{ $product->stock_quantity }} in stock
                                </x-ui.badge>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                @if($product->is_featured)
                                    <x-ui.badge variant="info" size="sm">Featured</x-ui.badge>
                                @endif
                                @if($product->is_new)
                                    <x-ui.badge variant="success" size="sm">New</x-ui.badge>
                                @endif
                                @if(!$product->is_featured && !$product->is_new)
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('vendor.products.edit', $product) }}">
                                    <x-ui.button variant="secondary" size="sm">Edit</x-ui.button>
                                </a>
                                <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.button type="submit" variant="danger-outline" size="sm">Delete</x-ui.button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No products found.</p>
                                <a href="{{ route('vendor.products.create') }}" class="mt-4">
                                    <x-ui.button variant="primary" size="sm">Create your first product</x-ui.button>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot:tbody>
            <x-slot:footer>
                <div class="px-4 py-3">
                    {{ $products->links() }}
                </div>
            </x-slot:footer>
        </x-ui.table>
    </div>
</x-vendor-layout>
