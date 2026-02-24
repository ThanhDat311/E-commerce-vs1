<x-staff-layout :pageTitle="'Deals'" :breadcrumbs="['Staff' => route('staff.dashboard'), 'Deals' => route('staff.deals.index')]">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Deals</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Name</th>
                    <th class="px-5 py-3 font-semibold">Type</th>
                    <th class="px-5 py-3 font-semibold">Period</th>
                    <th class="px-5 py-3 font-semibold">Usage</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($deals as $deal)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 font-medium text-gray-900">{{ $deal->name }}</td>
                        <td class="px-5 py-4 text-gray-500 uppercase text-xs">{{ $deal->discount_type }}</td>
                        <td class="px-5 py-4 text-gray-500 text-xs">
                            {{ $deal->start_date->format('d/m/Y') }} → {{ $deal->end_date->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs">
                            {{ $deal->usage_count }}{{ $deal->usage_limit ? ' / '.$deal->usage_limit : '' }}
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $badge = match($deal->status) { 'active' => 'success', 'pending' => 'pending', 'expired' => 'danger', default => 'default' };
                            @endphp
                            <x-ui.badge :variant="$badge">{{ ucfirst($deal->status) }}</x-ui.badge>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-1">
                                <form action="{{ route('staff.deals.toggle_status', $deal) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700 hover:bg-gray-200">
                                        {{ $deal->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <a href="{{ route('staff.deals.edit', $deal) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">No deals found.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($deals->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $deals->links() }}</div>
        @endif
    </div>
</x-staff-layout>
