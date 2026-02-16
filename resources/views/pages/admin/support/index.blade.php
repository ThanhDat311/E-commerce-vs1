<x-admin-layout :pageTitle="'Support Helpdesk'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Helpdesk' => route('admin.support.index')]">

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Open Tickets</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['open']) }}</p>
            </div>
            <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">In Progress</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending']) }}</p>
            </div>
            <div class="h-10 w-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Resolved</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['resolved']) }}</p>
            </div>
            <div class="h-10 w-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Avg Response Time</p>
                <p class="text-2xl font-bold text-gray-900">{{ round($stats['avg_response'], 1) }} hr</p>
            </div>
            <div class="h-10 w-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- Filters & List -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-5 border-b border-gray-100">
            <form method="GET" action="{{ route('admin.support.index') }}" class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search tickets by subject, ID, or user..."
                           class="pl-10 pr-4 py-2 w-full rounded-lg border border-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="flex gap-3 overflow-x-auto pb-1 lg:pb-0">
                    <select name="status" class="py-2 pl-3 pr-8 rounded-lg border border-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <select name="priority" class="py-2 pl-3 pr-8 rounded-lg border border-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    <select name="assignee" class="py-2 pl-3 pr-8 rounded-lg border border-gray-200 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">All Assignees</option>
                        <option value="me" {{ request('assignee') == 'me' ? 'selected' : '' }}>Assigned to Me</option>
                        <option value="unassigned" {{ request('assignee') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-semibold w-16">ID</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Subject</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Requester</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Priority</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Last Updated</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-right">Assigned To</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50/50 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.support.show', $ticket) }}'">
                            <td class="px-5 py-4 font-medium text-gray-500">#{{ $ticket->id }}</td>
                            <td class="px-5 py-4">
                                <span class="font-semibold text-gray-900 line-clamp-1">{{ $ticket->subject }}</span>
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
                            <td class="px-5 py-4">
                                @php
                                    $statusColors = [
                                        'open' => 'bg-blue-100 text-blue-700',
                                        'in_progress' => 'bg-amber-100 text-amber-700',
                                        'resolved' => 'bg-green-100 text-green-700',
                                        'closed' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $statusLabel = ucfirst(str_replace('_', ' ', $ticket->status));
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
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
                            <td class="px-5 py-4 text-gray-500">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($ticket->assignedTo)
                                    <span class="text-xs font-medium text-gray-700 bg-gray-100 px-2 py-1 rounded-md">
                                        {{ $ticket->assignedTo->name }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Unassigned</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No support tickets found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tickets->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $tickets->firstItem() }}</span> to <span class="font-medium">{{ $tickets->lastItem() }}</span> of <span class="font-bold text-gray-700">{{ $tickets->total() }}</span> tickets
                </p>
                <div>{{ $tickets->links('vendor.pagination.admin') }}</div>
            </div>
        @endif
    </div>
</x-admin-layout>
