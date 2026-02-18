<x-staff-layout :pageTitle="'Add New Product'" :breadcrumbs="['Staff' => route('staff.dashboard'), 'Products' => route('staff.products.index'), 'Create' => route('staff.products.create')]">
    <div class="max-w-5xl mx-auto">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-medium">Please verify the following errors:</h3>
                    <ul class="list-disc list-inside text-sm mt-1 ml-1 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('staff.products.store') }}" method="POST" enctype="multipart/form-data" id="create-product-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Product Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- General Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 border-b border-gray-200 bg-gray-50/50">
                            <h2 class="font-semibold text-gray-900">General Information</h2>
                        </div>
                        <div class="p-6 space-y-5">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400"
                                    placeholder="e.g., Wireless Noise-Cancelling Headphones">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                                <textarea name="description" id="description" rows="5" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400"
                                    placeholder="Detailed description of the product...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Inventory -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 border-b border-gray-200 bg-gray-50/50">
                            <h2 class="font-semibold text-gray-900">Pricing & Inventory</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price ($) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                                        class="w-full rounded-lg border-gray-300 pl-7 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400"
                                        placeholder="0.00">
                                </div>
                            </div>

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                                <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" min="0" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400"
                                    placeholder="0">
                            </div>

                            <div class="md:col-span-2">
                                <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU (Stock Keeping Unit)</label>
                                <input type="text" name="sku" id="sku" value="{{ old('sku') }}" 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400"
                                    placeholder="e.g., WH-1000XM5-BLK">
                                <p class="mt-1 text-xs text-gray-500">Unique identifier for this product.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Organization & Media -->
                <div class="space-y-6">
                    <!-- Organization -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 border-b border-gray-200 bg-gray-50/50">
                            <h2 class="font-semibold text-gray-900">Organization</h2>
                        </div>
                        <div class="p-6 space-y-5">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                                <select name="category_id" id="category_id" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-3 pt-2">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="is_new" name="is_new" type="checkbox" value="1" {{ old('is_new') ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_new" class="font-medium text-gray-700">Mark as New</label>
                                        <p class="text-gray-500 text-xs">Display a "New" badge on product card.</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_featured" class="font-medium text-gray-700">Mark as Featured</label>
                                        <p class="text-gray-500 text-xs">Promote this product on home page.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Images -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 border-b border-gray-200 bg-gray-50/50">
                            <h2 class="font-semibold text-gray-900">Product Images</h2>
                        </div>
                        <div class="p-6 space-y-5">
                            <!-- Main Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Main Image</label>
                                <div x-data="{ preview: null }" class="flex flex-col items-center justify-center w-full">
                                    <label for="image" class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors relative overflow-hidden group">
                                        
                                        <!-- Placeholder Content -->
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!preview">
                                            <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> main image</p>
                                            <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 2MB)</p>
                                        </div>

                                        <!-- Preview Image -->
                                        <img x-show="preview" :src="preview" class="absolute inset-0 w-full h-full object-cover" />
                                        
                                        <!-- Overlay on Hover when Preview Exists -->
                                        <div x-show="preview" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <p class="text-white text-sm font-medium">Change Image</p>
                                        </div>

                                        <input id="image" name="image" type="file" class="hidden" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])" />
                                    </label>
                                </div>
                            </div>

                            <!-- Gallery Images -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                                <input type="file" name="gallery[]" id="gallery" multiple accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors cursor-pointer border border-gray-200 rounded-lg focus:outline-none">
                                <p class="mt-1 text-xs text-gray-500">Hold Ctrl/Cmd to select multiple images.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                <a href="{{ route('staff.products.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 font-medium transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium transition-all shadow-md shadow-blue-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Create Product
                </button>
            </div>
        </form>
    </div>
</x-staff-layout>
