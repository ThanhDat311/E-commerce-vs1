<x-admin-layout>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Categories Management</h1>
        <a href="{{ route('admin.categories.create') }}">
            <x-ui.button variant="primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Category
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
                    <th scope="col" class="px-6 py-3">Slug</th>
                    <th scope="col" class="px-6 py-3">Parent</th>
                    <th scope="col" class="px-6 py-3">Products</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </x-slot:thead>
                <x-slot:tbody>
                    @forelse($categories as $category)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                @if($category->image_url)
                                    <img src="{{ asset($category->image_url) }}" alt="{{ $category->name }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $category->slug }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $category->parent->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <x-ui.badge variant="neutral">
                                    {{ $category->products_count ?? 0 }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4">
                                @if($category->is_active)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Inactive</x-ui.badge>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}">
                                        <x-ui.button variant="secondary" size="sm">
                                            Edit
                                        </x-ui.button>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No categories found. <a href="{{ route('admin.categories.create') }}" class="text-blue-600 hover:underline">Create your first category</a>
                            </td>
                        </tr>
                    @endforelse
                </x-slot:tbody>
            </x-ui.table>
        </div>
    </div>
</x-admin-layout>
