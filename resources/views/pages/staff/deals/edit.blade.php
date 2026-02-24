<x-staff-layout :pageTitle="'Edit Deal'" :breadcrumbs="['Staff' => route('staff.dashboard'), 'Deals' => route('staff.deals.index'), 'Edit' => '#']">
    <div class="max-w-2xl">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Deal – {{ $deal->name }}</h1>

        <form action="{{ route('staff.deals.update', $deal) }}" method="POST"
              class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">{{ old('description', $deal->description) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" variant="primary">Save Changes</x-ui.button>
                <a href="{{ route('staff.deals.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
            </div>
        </form>
    </div>
</x-staff-layout>
