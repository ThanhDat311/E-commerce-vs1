<x-admin-layout :pageTitle="'Add New Category'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Categories' => route('admin.categories.index'), 'Create' => route('admin.categories.create')]">
    <div class="max-w-4xl mx-auto">
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

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Main Info Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-semibold text-gray-900">Category Information</h2>
                        <p class="text-sm text-gray-500">Details about the category structure</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Name & Parent (Grid) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Category Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                       placeholder="e.g. Electronics">
                            </div>

                            <!-- Parent Category -->
                            <div>
                                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Parent Category
                                </label>
                                <select name="parent_id" id="parent_id"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                    <option value="">None (Top Level)</option>
                                    @foreach(\App\Models\Category::whereNull('parent_id')->orderBy('name')->get() as $cat)
                                        <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                   placeholder="e.g. electronics (auto-generated if empty)">
                            <p class="mt-1 text-xs text-gray-500">URL-friendly version of the name. Leave empty to auto-generate.</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors"
                                      placeholder="Brief description of this category (optional)">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Media & Settings Card -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-semibold text-gray-900">Media & Visibility</h2>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                            <div class="w-full flex justify-center items-center px-6 py-8 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 transition-colors relative">
                                <input type="file" name="image" id="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                                <div class="text-center pointer-events-none">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <span class="font-medium text-blue-600 hover:text-blue-500">Upload a file</span>
                                        or drag and drop
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG, GIF, SVG up to 5MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Visibility Switch -->
                        <div>
                            <label class="flex items-center justify-between cursor-pointer group p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <span class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">Active Status</span>
                                    <span class="text-xs text-gray-500">Visible to customers in store</span>
                                </span>
                                <div class="relative">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="block w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-medium transition-colors shadow-sm">
                        Create Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
