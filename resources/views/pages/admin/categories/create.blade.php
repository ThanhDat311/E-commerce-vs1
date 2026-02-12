<x-admin-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Add New Category</h1>
        <p class="mt-1 text-sm text-gray-600">Create a new category for your products</p>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white shadow rounded-lg p-6 space-y-6">
            <!-- Category Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Category Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter category name">
            </div>

            <!-- Slug and Parent Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="auto-generated-from-name">
                    <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from name</p>
                </div>

                <div>
                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                    <select name="parent_id" id="parent_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">None (Top Level)</option>
                        @foreach(\App\Models\Category::whereNull('parent_id')->orderBy('name')->get() as $cat)
                            <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter category description (optional)">{{ old('description') }}</textarea>
            </div>

            <!-- Image Upload -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF, or SVG. Max 5MB.</p>
            </div>

            <!-- Active Checkbox -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-700">Active (visible to customers)</label>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.categories.index') }}">
                    <x-ui.button type="button" variant="secondary">
                        Cancel
                    </x-ui.button>
                </a>
                <x-ui.button type="submit" variant="primary">
                    Create Category
                </x-ui.button>
            </div>
        </div>
    </form>
</x-admin-layout>
