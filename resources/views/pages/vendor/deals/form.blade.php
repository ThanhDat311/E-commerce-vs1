<x-vendor-layout :pageTitle="isset($deal) ? 'Edit Deal' : 'Create Deal'"
    :breadcrumbs="['Vendor' => route('vendor.dashboard'), 'Deals' => route('vendor.deals.index'), isset($deal) ? 'Edit' : 'Create' => '#']">

    <div class="max-w-2xl">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ isset($deal) ? 'Edit Deal' : 'New Deal' }}</h1>

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ isset($deal) ? route('vendor.deals.update', $deal) : route('vendor.deals.store') }}" method="POST"
              class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
            @csrf
            @if(isset($deal)) @method('PUT') @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deal Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $deal->name ?? '') }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">{{ old('description', $deal->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type <span class="text-red-500">*</span></label>
                    <select name="discount_type" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                        @foreach(['percent' => 'Percentage (%)', 'fixed' => 'Fixed Amount ($)'] as $val => $label)
                            <option value="{{ $val }}" {{ old('discount_type', $deal->discount_type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount Value <span class="text-red-500">*</span></label>
                    <input type="number" name="discount_value" step="0.01" min="0"
                        value="{{ old('discount_value', $deal->discount_value ?? '') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_date"
                        value="{{ old('start_date', isset($deal) ? $deal->start_date->format('Y-m-d\TH:i') : '') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="end_date"
                        value="{{ old('end_date', isset($deal) ? $deal->end_date->format('Y-m-d\TH:i') : '') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm" required>
                </div>
            </div>

            <input type="hidden" name="apply_scope" value="product">

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
                <p class="text-xs text-gray-400 mt-1">Only your own products are listed.</p>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-700">
                <strong>Note:</strong> Your deal will be submitted for Admin approval before going live.
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" variant="primary">{{ isset($deal) ? 'Update Deal' : 'Submit Deal' }}</x-ui.button>
                <a href="{{ route('vendor.deals.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</x-vendor-layout>
