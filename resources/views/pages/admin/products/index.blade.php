<x-admin-layout :pageTitle="'Products Management'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Products' => route('admin.products.index')]">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Products Management</h1>
        <a href="{{ route('admin.products.create') }}">
            <x-ui.button variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Product
            </x-ui.button>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-semibold">Image</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Name</th>
                        <th scope="col" class="px-5 py-3 font-semibold">SKU</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Category</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Price</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Stock</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                @if($product->image_url)
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" class="w-11 h-11 object-cover rounded-lg border border-gray-100">
                                @else
                                    <div class="w-11 h-11 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-100">
                                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-900">
                                {{ $product->name }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 font-mono text-xs">
                                {{ $product->sku }}
                            </td>
                            <td class="px-5 py-4 text-gray-500">
                                {{ $product->category->name ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $stockVariant = $product->stock_quantity > 10 ? 'success' : ($product->stock_quantity > 0 ? 'pending' : 'danger');
                                @endphp
                                <x-ui.badge :variant="$stockVariant">
                                    {{ $product->stock_quantity }}
                                </x-ui.badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5">
                                    @if($product->is_featured)
                                        <x-ui.badge variant="success">Featured</x-ui.badge>
                                    @endif
                                    @if($product->is_new)
                                        <x-ui.badge variant="info">New</x-ui.badge>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No products found. <a href="{{ route('admin.products.create') }}" class="text-blue-600 hover:underline">Create your first product</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $products->firstItem() }}</span> to <span class="font-medium">{{ $products->lastItem() }}</span> of <span class="font-bold text-gray-700">{{ $products->total() }}</span> products
                </p>
                <div>
                    {{ $products->links('vendor.pagination.admin') }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
