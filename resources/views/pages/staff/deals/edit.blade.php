<x-staff-layout :pageTitle="'Edit Deal'" :breadcrumbs="['Staff' => route('staff.dashboard'), 'Deals' => route('staff.deals.index'), 'Edit' => '#']">
    <div class="max-w-3xl">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Edit Deal</h1>
            <a href="{{ route('staff.deals.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Back to Deals
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Deal Info (Read-only) --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-gray-700">Deal Information</h2>
                @php
                    $badge = match($deal->status) { 'active' => 'success', 'pending' => 'pending', 'expired' => 'danger', default => 'default' };
                @endphp
                <x-ui.badge :variant="$badge">{{ ucfirst($deal->status) }}</x-ui.badge>
            </div>

            <div class="grid grid-cols-2 gap-x-8 gap-y-4 text-sm">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Name</p>
                    <p class="text-gray-900 font-medium">{{ $deal->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Discount</p>
                    <p class="text-gray-900">
                        @if($deal->discount_type === 'percent')
                            {{ $deal->discount_value }}% off
                        @elseif($deal->discount_type === 'fixed')
                            ${{ number_format($deal->discount_value, 2) }} off
                        @else
                            {{ strtoupper($deal->discount_type) }}
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Period</p>
                    <p class="text-gray-900">
                        {{ $deal->start_date->format('d M Y, H:i') }}
                        <span class="text-gray-400 mx-1">→</span>
                        {{ $deal->end_date->format('d M Y, H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Usage</p>
                    <p class="text-gray-900">
                        {{ $deal->usage_count }}
                        @if($deal->usage_limit)
                            / {{ $deal->usage_limit }}
                        @else
                            <span class="text-gray-400 text-xs">(unlimited)</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Apply Scope</p>
                    <p class="text-gray-900 capitalize">{{ $deal->apply_scope }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Priority</p>
                    <p class="text-gray-900">{{ $deal->priority }}</p>
                </div>
                @if($deal->vendor_id)
                    <div class="col-span-2">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Vendor</p>
                        <p class="text-gray-900">{{ $deal->vendor?->name ?? '—' }}</p>
                        @if($deal->status === 'pending')
                            <p class="text-xs text-amber-600 mt-1">⚠ This is a vendor deal pending admin approval. You cannot activate it.</p>
                        @endif
                    </div>
                @endif
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Created by</p>
                    <p class="text-gray-900">{{ $deal->creator?->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Created at</p>
                    <p class="text-gray-900">{{ $deal->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <p class="mt-4 text-xs text-gray-400 italic">
                * Only description can be edited by Staff. Contact Admin for other changes.
            </p>
        </div>

        {{-- Edit Form (description only) --}}
        <form action="{{ route('staff.deals.update', $deal) }}" method="POST"
              class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
            @csrf @method('PUT')

            <h2 class="text-base font-semibold text-gray-700">Edit Description</h2>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="5"
                    placeholder="Enter deal description..."
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-400 @enderror">{{ old('description', $deal->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" variant="primary">Save Changes</x-ui.button>
                <a href="{{ route('staff.deals.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</x-staff-layout>
