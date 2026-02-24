<x-vendor-layout :pageTitle="'My Deals'" :breadcrumbs="['Vendor' => route('vendor.dashboard'), 'Deals' => route('vendor.deals.index')]">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">My Deals</h1>
        <a href="{{ route('vendor.deals.create') }}">
            <x-ui.button variant="primary">New Deal</x-ui.button>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Name</th>
                    <th class="px-5 py-3 font-semibold">Type / Value</th>
                    <th class="px-5 py-3 font-semibold">Period</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($deals as $deal)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 font-medium text-gray-900">{{ $deal->name }}</td>
                        <td class="px-5 py-4 text-gray-500">
                            {{ strtoupper($deal->discount_type) }} –
                            {{ $deal->discount_type === 'percent' || $deal->discount_type === 'flash' ? $deal->discount_value.'%' : '$'.number_format($deal->discount_value,2) }}
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs">
                            {{ $deal->start_date->format('d/m/Y') }} → {{ $deal->end_date->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $badge = match($deal->status) { 'active' => 'success', 'pending' => 'pending', 'expired' => 'danger', default => 'default' };
                            @endphp
                            <x-ui.badge :variant="$badge">{{ ucfirst($deal->status) }}</x-ui.badge>
                            @if($deal->status === 'pending')
                                <p class="text-xs text-gray-400 mt-0.5">Waiting admin approval</p>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('vendor.deals.edit', $deal) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </a>
                                @if($deal->status === 'draft')
                                    <form action="{{ route('vendor.deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('Delete this deal?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No deals yet. <a href="{{ route('vendor.deals.create') }}" class="text-blue-600 hover:underline">Create one</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($deals->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $deals->links() }}</div>
        @endif
    </div>
</x-vendor-layout>
