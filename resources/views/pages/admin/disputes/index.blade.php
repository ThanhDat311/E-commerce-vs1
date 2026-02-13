<x-admin-layout :pageTitle="'Dispute & Refund Management'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Orders' => route('admin.orders.index'), 'Disputes' => route('admin.disputes.index')]">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dispute Resolution Center</h1>
        </div>
        <a href="{{ route('admin.orders.export', request()->all()) }}">
            <x-ui.button variant="outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export CSV
            </x-ui.button>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Tabs & Search -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-2 flex-wrap">
            @php
                $currentStatus = request('status', '');
                $tabs = [
                    '' => 'All',
                    'under_review' => 'Under Review',
                    'pending' => 'Pending',
                    'resolved' => 'Resolved',
                    'rejected' => 'Refunded',
                ];
            @endphp
            @foreach($tabs as $value => $tabLabel)
                <a href="{{ route('admin.disputes.index', array_merge(request()->except('status', 'page'), $value ? ['status' => $value] : [])) }}"
                   class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium transition-all duration-200
                          {{ $currentStatus === $value
                              ? 'bg-blue-600 text-white shadow-sm'
                              : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 hover:text-gray-900' }}">
                    {{ $tabLabel }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.disputes.index') }}" class="relative">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search dispute ID, buyer, or reason..."
                       class="pl-10 pr-4 py-2 w-full sm:w-80 rounded-lg border border-gray-200 bg-white text-sm text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
            </div>
        </form>
    </div>

    <!-- Disputes Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-4 py-3 w-10">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th scope="col" class="px-4 py-3 font-semibold">Order ID</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Buyer</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Reason</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Dispute Status</th>
                        <th scope="col" class="px-4 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($disputes as $dispute)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-4">
                                <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.orders.show', $dispute->order_id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    #ORD-{{ $dispute->order_id }}
                                </a>
                                <div class="text-xs text-gray-400 mt-0.5">Dispute #DSP-{{ $dispute->id }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-semibold text-slate-600 overflow-hidden flex-shrink-0">
                                        @php
                                            $initials = collect(explode(' ', $dispute->user->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode('');
                                        @endphp
                                        {{ $initials }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-900 truncate">{{ $dispute->user->name }}</div>
                                        <div class="text-xs text-gray-400 truncate">{{ $dispute->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $dispute->reason)) }}</div>
                                @if($dispute->description)
                                    <div class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $dispute->description }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusVariant = match($dispute->status) {
                                        'resolved' => 'success',
                                        'under_review' => 'warning',
                                        'rejected' => 'danger',
                                        default => 'pending'
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusVariant" :dot="true">
                                    {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.disputes.show', $dispute) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors" title="View Details">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    @if(in_array($dispute->status, ['pending', 'under_review']))
                                        <a href="{{ route('admin.disputes.show', $dispute) }}">
                                            <x-ui.button variant="primary" size="sm">
                                                Resolve
                                            </x-ui.button>
                                        </a>
                                    @else
                                        <span class="px-3 py-1.5 text-xs font-medium text-gray-400">
                                            {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No disputes found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($disputes->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $disputes->firstItem() }}</span> to <span class="font-medium">{{ $disputes->lastItem() }}</span> of <span class="font-bold text-gray-700">{{ $disputes->total() }}</span> disputes
                </p>
                <div>
                    {{ $disputes->links() }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
