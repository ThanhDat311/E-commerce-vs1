<x-staff-layout :pageTitle="'Support Tickets'" :breadcrumbs="['Staff' => route('staff.dashboard'), 'Support Tickets' => route('staff.support.index')]">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Support Tickets</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and resolve customer queries assigned to you.</p>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Open/In Progress System Wide</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['open_total']) }}</p>
            </div>
            <div class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 00-2-2m0 0V5a2 2 0 012-2h14a2 2 0 012 2v2v2" /></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-blue-100 shadow-sm p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-1">Assigned to Me</p>
                <p class="text-2xl font-bold text-blue-700">{{ number_format($stats['assigned_to_me']) }}</p>
            </div>
            <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Resolved by Me</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['resolved_by_me']) }}</p>
            </div>
            <div class="h-10 w-10 bg-gray-50 text-gray-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- Filters & List -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between">
            
            <div class="flex gap-2 p-1 bg-gray-100 rounded-lg w-full md:w-auto">
                <a href="{{ route('staff.support.index', array_merge(request()->except('filter'), ['filter' => 'my_tickets'])) }}" 
                   class="flex-1 md:flex-none text-center px-4 py-1.5 rounded-md text-sm font-medium transition-colors {{ $filter === 'my_tickets' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                    My Tickets
                </a>
                <a href="{{ route('staff.support.index', array_merge(request()->except('filter'), ['filter' => 'unassigned'])) }}" 
                   class="flex-1 md:flex-none text-center px-4 py-1.5 rounded-md text-sm font-medium transition-colors {{ $filter === 'unassigned' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                    Unassigned
                </a>
                <a href="{{ route('staff.support.index', array_merge(request()->except('filter'), ['filter' => 'all'])) }}" 
                   class="flex-1 md:flex-none text-center px-4 py-1.5 rounded-md text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                    All Tickets
                </a>
            </div>

            <form method="GET" action="{{ route('staff.support.index') }}" class="flex flex-1 w-full md:w-auto gap-3 items-center">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, Subject..."
                           class="pl-9 pr-3 py-2 text-sm w-full rounded-lg border-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <select name="status" class="py-2 pl-3 pr-8 rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-semibold">ID</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Subject / Category</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Requester</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-center">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-center">Priority</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Last Updated</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4 font-medium text-gray-500">#{{ $ticket->id }}</td>
                            <td class="px-5 py-4">
                                <a href="{{ route('staff.support.show', $ticket) }}" class="font-semibold text-gray-900 hover:text-blue-600 line-clamp-1 block">
                                    {{ $ticket->subject }}
                                </a>
                                <span class="text-xs text-gray-500">{{ $ticket->category }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600">
                                        {{ substr($ticket->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-gray-700">{{ $ticket->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'open' => 'bg-blue-100 text-blue-700',
                                        'in_progress' => 'bg-amber-100 text-amber-700',
                                        'resolved' => 'bg-green-100 text-green-700',
                                        'closed' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $statusLabel = ucfirst(str_replace('_', ' ', $ticket->status));
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$ticket->status] ?? 'bg-gray-100' }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @php
                                    $priorityColors = [
                                        'low' => 'text-gray-500',
                                        'medium' => 'text-blue-600',
                                        'high' => 'text-orange-600',
                                        'urgent' => 'text-red-600 font-bold',
                                    ];
                                @endphp
                                <span class="text-xs {{ $priorityColors[$ticket->priority] ?? 'text-gray-500' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-500 text-xs">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if(is_null($ticket->assigned_to))
                                    <form method="POST" action="{{ route('staff.support.update', $ticket) }}" class="inline-block">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="claim">
                                        <button type="submit" class="px-3 py-1 bg-green-50 text-green-600 hover:bg-green-100 rounded text-xs font-semibold transition-colors">
                                            Claim
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('staff.support.show', $ticket) }}" class="inline-block px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded text-xs font-semibold transition-colors ml-1">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <p class="text-gray-500">No support tickets found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</x-staff-layout>
