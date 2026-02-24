<x-admin-layout :pageTitle="'Deals Management'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Deals' => route('admin.deals.index')]">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-900">Deals Management</h1>
        <a href="{{ route('admin.deals.create') }}">
            <x-ui.button variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Deal
            </x-ui.button>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Name</th>
                        <th class="px-5 py-3 font-semibold">Type</th>
                        <th class="px-5 py-3 font-semibold">Value</th>
                        <th class="px-5 py-3 font-semibold">Scope</th>
                        <th class="px-5 py-3 font-semibold">Period</th>
                        <th class="px-5 py-3 font-semibold">Usage</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($deals as $deal)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4 font-medium text-gray-900">
                                {{ $deal->name }}
                                @if($deal->vendor_id)
                                    <span class="ml-1 text-xs text-purple-600">[Vendor]</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-500 uppercase text-xs font-mono">{{ $deal->discount_type }}</td>
                            <td class="px-5 py-4 text-gray-700 font-semibold">
                                @if($deal->discount_type === 'percent' || $deal->discount_type === 'flash')
                                    {{ $deal->discount_value }}%
                                @elseif($deal->discount_type === 'fixed')
                                    ${{ number_format($deal->discount_value, 2) }}
                                @else
                                    BOGO
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-500">{{ ucfirst($deal->apply_scope) }}</td>
                            <td class="px-5 py-4 text-gray-500 text-xs">
                                {{ $deal->start_date->format('d/m/Y') }}<br>→ {{ $deal->end_date->format('d/m/Y') }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 text-xs">
                                {{ $deal->usage_count }}
                                @if($deal->usage_limit) / {{ $deal->usage_limit }} @endif
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $badge = match($deal->status) {
                                        'active'  => 'success',
                                        'pending' => 'pending',
                                        'expired' => 'danger',
                                        default   => 'default',
                                    };
                                @endphp
                                <x-ui.badge :variant="$badge">{{ ucfirst($deal->status) }}</x-ui.badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    {{-- Approve (pending vendor deals) --}}
                                    @if($deal->status === 'pending')
                                        <form action="{{ route('admin.deals.approve', $deal) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-2 py-1 text-xs rounded bg-green-100 text-green-700 hover:bg-green-200 transition">Approve</button>
                                        </form>
                                    @endif
                                    {{-- Toggle status --}}
                                    <form action="{{ route('admin.deals.toggle_status', $deal) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                            {{ $deal->status === 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.deals.edit', $deal) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.deals.destroy', $deal) }}" method="POST" onsubmit="return confirm('Delete this deal?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">No deals yet. <a href="{{ route('admin.deals.create') }}" class="text-blue-600 hover:underline">Create one</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deals->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $deals->links('vendor.pagination.admin') }}
            </div>
        @endif
    </div>
</x-admin-layout>
