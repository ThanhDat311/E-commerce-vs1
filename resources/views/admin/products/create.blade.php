@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
            <p class="text-gray-600 mt-2">Create and configure a new product for your store.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-semibold text-gray-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>
    </div>
</div>

<!-- Form -->
<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Basic Information</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Product Name *</label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all @error('name') border-red-500 @enderror" placeholder="Enter product name" value="{{ old('name') }}" required>
                        @error('name')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">SKU (Code) *</label>
                            <input type="text" name="sku" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all @error('sku') border-red-500 @enderror" placeholder="e.g., PRO-9902" value="{{ old('sku') }}" required>
                            @error('sku')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                            <select name="category_id" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all cursor-pointer @error('category_id') border-red-500 @enderror" required>
                                <option value="">Select a category</option>
                                @forelse($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @empty
                                <option value="">No categories available</option>
                                @endforelse
                            </select>
                            @error('category_id')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all resize-none @error('description') border-red-500 @enderror" placeholder="Enter product description">{{ old('description') }}</textarea>
                        @error('description')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Pricing & Stock -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Pricing & Stock</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price ($) *</label>
                        <input type="number" step="0.01" name="price" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all @error('price') border-red-500 @enderror" placeholder="0.00" value="{{ old('price') }}" required>
                        @error('price')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Quantity *</label>
                        <input type="number" name="stock_quantity" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all @error('stock_quantity') border-red-500 @enderror" placeholder="0" value="{{ old('stock_quantity') }}" required>
                        @error('stock_quantity')<span class="text-red-500 text-xs mt-1">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Product Image -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Product Image</h2>

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-indigo-500 hover:bg-indigo-50 transition-all cursor-pointer" id="imageDropZone">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-700 font-medium">Drop your image here or click to browse</p>
                    <p class="text-gray-500 text-sm mt-1">PNG, JPG up to 5MB</p>
                    <input type="file" name="image" class="absolute opacity-0 pointer-events-none" id="imageInput" accept="image/*">
                </div>
                <div id="imagePreview" class="mt-4 hidden">
                    <img id="previewImg" src="" alt="Preview" class="max-w-xs rounded-lg" />
                </div>
                @error('image')<span class="text-red-500 text-xs mt-2 block">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Product Status -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Status</h3>

                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_featured" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 cursor-pointer" {{ old('is_featured') ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Featured Product</span>
                    </label>
                    <p class="text-xs text-gray-500 ml-7">This product will appear on homepage</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <div class="space-y-3">
                    <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Publish Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="w-full px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('imageDropZone');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        dropZone.addEventListener('click', () => imageInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                imageInput.files = files;
                handleImageChange();
            }
        });

        imageInput.addEventListener('change', handleImageChange);

        function handleImageChange() {
            const file = imageInput.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        }
    });
</script>
@endsection