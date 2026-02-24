<x-admin-layout :pageTitle="isset($deal) ? 'Edit Deal' : 'Create Deal'"
    :breadcrumbs="['Admin' => route('admin.dashboard'), 'Deals' => route('admin.deals.index'), isset($deal) ? 'Edit' : 'Create' => '#']">

    <div class="max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ isset($deal) ? 'Edit Deal' : 'Create New Deal' }}</h1>

        <form action="{{ isset($deal) ? route('admin.deals.update', $deal) : route('admin.deals.store') }}" method="POST"
              class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
            @csrf
            @if(isset($deal)) @method('PUT') @endif

            @if($errors->any())
                <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            {{-- Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $deal->name ?? '') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">{{ old('description', $deal->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Discount Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type <span class="text-red-500">*</span></label>
                    <select name="discount_type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                        @foreach(['percent' => 'Percentage (%)', 'fixed' => 'Fixed Amount ($)', 'bogo' => 'Buy X Get Y (BOGO)', 'flash' => 'Flash Sale'] as $val => $label)
                            <option value="{{ $val }}" {{ old('discount_type', $deal->discount_type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Discount Value --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value <span class="text-red-500">*</span></label>
                    <input type="number" name="discount_value" step="0.01" min="0"
                        value="{{ old('discount_value', $deal->discount_value ?? '') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Start Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_date"
                        value="{{ old('start_date', isset($deal) ? $deal->start_date->format('Y-m-d\TH:i') : '') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                </div>

                {{-- End Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="end_date"
                        value="{{ old('end_date', isset($deal) ? $deal->end_date->format('Y-m-d\TH:i') : '') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                {{-- Apply Scope --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Apply Scope</label>
                    <select name="apply_scope" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        @foreach(['product' => 'Product', 'category' => 'Category', 'vendor' => 'Vendor', 'global' => 'Global'] as $val => $label)
                            <option value="{{ $val }}" {{ old('apply_scope', $deal->apply_scope ?? 'product') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Usage Limit --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usage Limit</label>
                    <input type="number" name="usage_limit" min="0"
                        value="{{ old('usage_limit', $deal->usage_limit ?? '') }}"
                        placeholder="∞ Unlimited"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                </div>

                {{-- Priority --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <input type="number" name="priority" min="0" max="9999"
                        value="{{ old('priority', $deal->priority ?? 0) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @foreach(['draft' => 'Draft', 'active' => 'Active', 'pending' => 'Pending Approval', 'expired' => 'Expired'] as $val => $label)
                        <option value="{{ $val }}" {{ old('status', $deal->status ?? 'draft') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Assign Products --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Products</label>
                <select name="product_ids[]" multiple class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm h-32">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}"
                            {{ isset($deal) && $deal->products->contains($product->id) ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">Hold Ctrl/Cmd to select multiple.</p>
            </div>

            {{-- Assign Categories --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign Categories</label>
                <select name="category_ids[]" multiple class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm h-24">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ isset($deal) && $deal->categories->contains($category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" variant="primary">
                    {{ isset($deal) ? 'Update Deal' : 'Create Deal' }}
                </x-ui.button>
                <a href="{{ route('admin.deals.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</x-admin-layout>
