@extends('layouts.vendor')

@section('title', 'Create Product')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="mb-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Create New Product</h1>
        <a href="{{ route('vendor.products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
             &larr; Back to Products
        </a>
    </div>

    <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Section 1: Basic Information --}}
        <x-vendor.form-section title="Basic Information" description="Enter the main details of your product.">
            <div class="grid grid-cols-6 gap-6">
                <!-- Product Type (Example Radio - Assume controller handles type if added later, for now just UI) -->
                {{-- 
                <div class="col-span-6">
                     <x-vendor.input-label value="Product Type" />
                     <x-vendor.radio-group name="product_type" :options="['simple' => 'Simple Product', 'variable' => 'Variable Product']" selected="simple" />
                </div>
                --}}

                <!-- Name -->
                <div class="col-span-6">
                    <x-vendor.input-label for="name" value="Product Name" required />
                    <x-vendor.text-input id="name" name="name" type="text" :value="old('name')" required autofocus :error="$errors->has('name')" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <!-- Slug (Optional) - If controller generates it automatically, maybe hide or make optional -->
                {{-- 
                <div class="col-span-6 sm:col-span-4">
                     <x-vendor.input-label for="slug" value="Slug (Optional)" />
                     <x-vendor.text-input id="slug" name="slug" type="text" :value="old('slug')" />
                     <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from name.</p>
                </div>
                --}}

                <!-- Category -->
                <div class="col-span-6 sm:col-span-3">
                    <x-vendor.input-label for="category_id" value="Category" required />
                    <x-vendor.select-input id="category_id" name="category_id" :error="$errors->has('category_id')">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-vendor.select-input>
                    <x-input-error for="category_id" class="mt-2" />
                </div>

                <!-- Brand (Placeholder if not in DB yet) -->
                {{-- 
                <div class="col-span-6 sm:col-span-3">
                     <x-vendor.input-label for="brand_id" value="Brand" />
                     <x-vendor.select-input id="brand_id" name="brand_id">
                         <option value="">Select Brand</option>
                          Add brand loop here 
                     </x-vendor.select-input>
                </div>
                --}}

                <!-- Description -->
                <div class="col-span-6">
                    <x-vendor.input-label for="description" value="Description" required />
                    <x-vendor.textarea-input id="description" name="description" rows="4" required :error="$errors->has('description')">{{ old('description') }}</x-vendor.textarea-input>
                    <x-input-error for="description" class="mt-2" />
                </div>
            </div>
        </x-vendor.form-section>

        <x-vendor.section-border />

        {{-- Section 2: Media --}}
        <x-vendor.form-section title="Media" description="Upload product images.">
            <div class="col-span-6">
                 <x-vendor.file-upload name="image" label="Upload Product Image" accept="image/*" />
                 <x-input-error for="image" class="mt-2" />
                 
                 <!-- Simple Preview with vanilla JS for now, echoing existing script logic roughly -->
                 <div class="mt-4" id="image-preview-container" style="display: none;">
                      <p class="text-sm text-gray-500 mb-2">Preview:</p>
                      <img id="image-preview" src="#" alt="Image Preview" class="h-40 w-auto rounded-lg shadow-sm border border-gray-200">
                 </div>
            </div>
        </x-vendor.form-section>

        <x-vendor.section-border />

        {{-- Section 3: Price & Stock --}}
        <x-vendor.form-section title="Price & Inventory" description="Manage pricing and stock availability.">
             <div class="grid grid-cols-6 gap-6">
                <!-- Price -->
                <div class="col-span-6 sm:col-span-2">
                    <x-vendor.input-label for="price" value="Price ($)" required />
                    <x-vendor.text-input id="price" name="price" type="number" step="0.01" :value="old('price')" required :error="$errors->has('price')" />
                    <x-input-error for="price" class="mt-2" />
                </div>

                <!-- SKU -->
                <div class="col-span-6 sm:col-span-2">
                    <x-vendor.input-label for="sku" value="SKU" />
                    <x-vendor.text-input id="sku" name="sku" type="text" :value="old('sku')" :error="$errors->has('sku')" />
                    <x-input-error for="sku" class="mt-2" />
                </div>

                <!-- Quantity -->
                <div class="col-span-6 sm:col-span-2">
                    <x-vendor.input-label for="quantity" value="Stock Quantity" required />
                    <x-vendor.text-input id="quantity" name="quantity" type="number" min="0" :value="old('quantity')" required :error="$errors->has('quantity')" />
                    <x-input-error for="quantity" class="mt-2" />
                </div>
             </div>
        </x-vendor.form-section>
        
        <x-vendor.section-border />

        {{-- Section 4: Settings --}}
        <x-vendor.form-section title="Settings" description="Configure visibility and other options.">
            <div class="grid grid-cols-6 gap-6">
                 <div class="col-span-6 sm:col-span-3">
                     <x-vendor.toggle-switch name="is_featured" label="Featured Product" description="Show this product in featured sections." :checked="old('is_featured') == '1'" />
                 </div>
                 
                 <div class="col-span-6 sm:col-span-3">
                    <x-vendor.toggle-switch name="is_new" label="New Arrival" description="Mark this product as new." :checked="old('is_new') == '1'" />
                </div>
            </div>
        </x-vendor.form-section>

        <!-- Footer Actions -->
        <div class="mt-8 border-t border-gray-200 pt-6 flex justify-end gap-3">
            <a href="{{ route('vendor.products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                Cancel
            </a>
            <x-admin.button type="submit" variant="primary" icon="save">
                Create Product
            </x-admin.button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.src = event.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>
@endpush
@endsection