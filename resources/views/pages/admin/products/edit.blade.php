<x-admin-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Product</h1>
        <p class="mt-1 text-sm text-gray-600">Update product information</p>
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

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white shadow rounded-lg p-6 space-y-6">
            <!-- Product Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter product name">
            </div>

            <!-- SKU and Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="e.g., PROD-001">
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" id="category_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Price and Quantity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                        Price ($) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" 
                        step="0.01" min="0.01" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="0.00">
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                        Stock Quantity <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->stock_quantity) }}" 
                        min="0" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="0">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="4" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Current Image & Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Product Image</label>
                
                @if($product->image_url)
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" 
                            class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                    </div>
                @endif

                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1 text-xs text-gray-500">JPG, PNG, or GIF. Max 2MB. Leave empty to keep current image.</p>
            </div>

            <!-- Checkboxes -->
            <div class="flex items-center gap-6">
                <div class="flex items-center">
                    <input type="checkbox" name="is_new" id="is_new" value="1" 
                        {{ old('is_new', $product->is_new) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_new" class="ml-2 block text-sm text-gray-700">Mark as New</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                        {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_featured" class="ml-2 block text-sm text-gray-700">Mark as Featured</label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.products.index') }}">
                    <x-ui.button type="button" variant="secondary">
                        Cancel
                    </x-ui.button>
                </a>
                <x-ui.button type="submit" variant="primary">
                    Update Product
                </x-ui.button>
            </div>
        </div>
    </form>
</x-admin-layout>
