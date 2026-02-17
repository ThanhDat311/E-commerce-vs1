<x-vendor-layout :page-title="'Add New Product'" :breadcrumbs="['Vendor' => route('vendor.dashboard'), 'Products' => route('vendor.products.index'), 'Create' => route('vendor.products.create')]">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Add New Product') }}</h1>
        <a href="{{ route('vendor.products.index') }}">
            <x-ui.button variant="secondary">
                &larr; Back to Products
            </x-ui.button>
        </a>
    </div>

    <div class="max-w-5xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 bg-white border-b border-gray-200">
                <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Basic Info -->
                        <div class="col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Basic Information</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <x-input-label for="name" :value="__('Product Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="description" :value="__('Description')" />
                                    <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('description') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Inventory -->
                        <div class="col-span-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Pricing & Inventory</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="price" :value="__('Price ($)')" />
                                    <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price')" step="0.01" required />
                                    <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="discount_price" :value="__('Discount Price ($) (Optional)')" />
                                    <x-text-input id="discount_price" class="block mt-1 w-full" type="number" name="discount_price" :value="old('discount_price')" step="0.01" />
                                    <x-input-error :messages="$errors->get('discount_price')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="sku" :value="__('SKU')" />
                                    <x-text-input id="sku" class="block mt-1 w-full" type="text" name="sku" :value="old('sku')" required />
                                    <x-input-error :messages="$errors->get('sku')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="quantity" :value="__('Stock Quantity')" />
                                    <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity')" required />
                                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Category & Image -->
                        <div class="col-span-1">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b">Categorization & Media</h3>
                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="category_id" :value="__('Category')" />
                                    <select id="category_id" name="category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Select a Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="image" :value="__('Product Image')" />
                                    <input id="image" type="file" name="image" class="block mt-1 w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100" />
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>

                                <div class="mt-6 space-y-3 bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <input id="is_new" type="checkbox" name="is_new" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_new') ? 'checked' : '' }}>
                                        <label for="is_new" class="ml-2 block text-sm text-gray-900">Mark as New Arrival</label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="is_featured" type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label for="is_featured" class="ml-2 block text-sm text-gray-900">Mark as Featured</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t pt-6 gap-3">
                        <a href="{{ route('vendor.products.index') }}">
                            <x-ui.button variant="secondary" type="button">
                                Cancel
                            </x-ui.button>
                        </a>
                        <x-ui.button variant="primary" type="submit">
                            {{ __('Create Product') }}
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-vendor-layout>
