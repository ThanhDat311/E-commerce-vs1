<x-admin-layout :pageTitle="'Add New Product'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Products' => route('admin.products.index'), 'Create' => route('admin.products.create')]">
    <div class="max-w-5xl mx-auto">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mt-0.5 shrink-0">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <p class="font-medium">Please correct the following errors:</p>
                    <ul class="list-disc list-inside mt-1 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column (Main Info) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Product Details -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-lg font-semibold text-gray-900">Product Details</h2>
                            <p class="text-sm text-gray-500">Basic information about the product</p>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Product Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                       placeholder="e.g. Wireless Noise Cancelling Headphones">
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" id="description" rows="5" required
                                          class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                          placeholder="Detailed description of the product...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Inventory -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-lg font-semibold text-gray-900">Pricing & Inventory</h2>
                        </div>
                        
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Price ($) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0.01" required
                                           class="block w-full rounded-lg border-gray-300 pl-7 focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                           placeholder="0.00">
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                                    Stock Quantity <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="0" required
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                       placeholder="0">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Organization & Media) -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Organization -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-lg font-semibold text-gray-900">Organization</h2>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <!-- SKU -->
                            <div>
                                <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                                <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                       placeholder="e.g. PROD-001">
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" id="category_id" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Switches -->
                            <div class="space-y-3 pt-2">
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <span class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">Mark as New</span>
                                        <span class="text-xs text-gray-500">Display 'New' badge</span>
                                    </span>
                                    <input type="checkbox" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>

                                <label class="flex items-center justify-between cursor-pointer group">
                                    <span class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-900">Featured</span>
                                        <span class="text-xs text-gray-500">Show on homepage</span>
                                    </span>
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h2 class="text-lg font-semibold text-gray-900">Media</h2>
                        </div>
                        
                        <div class="p-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                            <div class="w-full flex justify-center items-center px-6 py-8 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 transition-colors relative">
                                <input type="file" name="image" id="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/jpeg,image/png,image/jpg,image/gif">
                                <div class="text-center pointer-events-none">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <span class="font-medium text-blue-600 hover:text-blue-500">Upload a file</span>
                                        or drag and drop
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium transition-colors shadow-sm">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
