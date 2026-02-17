<x-base-layout>
    <x-store.navbar />

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-gray-500 text-sm">
                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                <span class="mx-2">&rsaquo;</span>
                <a href="{{ route('profile.edit') }}" class="hover:text-gray-900">Account</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900 font-medium">Support Tickets</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar -->
                <div class="w-full md:w-1/4 shrink-0 sticky top-4 self-start">
                    @include('profile.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="flex-1 min-w-0">
                    <!-- Stats Row -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Open</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['open']) }}</p>
                        </div>
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">In Progress</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['in_progress']) }}</p>
                        </div>
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Resolved</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['resolved']) }}</p>
                        </div>
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                        </div>
                    </div>

                    <!-- Tickets List -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
                        <!-- Header -->
                        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">My Support Tickets</h2>
                                <p class="text-sm text-gray-500 mt-1">View and manage your support requests.</p>
                            </div>
                            <a href="{{ route('tickets.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                New Ticket
                            </a>
                        </div>

                        <!-- Filters -->
                        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                            <form method="GET" action="{{ route('tickets.index') }}" class="flex flex-col md:flex-row gap-3">
                                <div class="flex-1 relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                           placeholder="Search tickets..."
                                           class="pl-10 pr-4 py-2 w-full rounded-lg border border-gray-200 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                </div>
                                <select name="status" class="py-2 pl-3 pr-8 rounded-lg border border-gray-200 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                    <option value="">All Statuses</option>
                                    <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                <select name="category" class="py-2 pl-3 pr-8 rounded-lg border border-gray-200 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                                    <option value="">All Categories</option>
                                    <option value="order_issue" {{ request('category') == 'order_issue' ? 'selected' : '' }}>Order Issue</option>
                                    <option value="account" {{ request('category') == 'account' ? 'selected' : '' }}>Account</option>
                                    <option value="technical" {{ request('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="billing" {{ request('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition-colors">
                                    Filter
                                </button>
                            </form>
                        </div>

                        <!-- Tickets Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                                    <tr>
                                        <th scope="col" class="px-5 py-3 font-semibold w-16">ID</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Subject</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Priority</th>
                                        <th scope="col" class="px-5 py-3 font-semibold">Last Updated</th>
                                        <th scope="col" class="px-5 py-3 font-semibold text-right">Messages</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($tickets as $ticket)
                                        <tr class="hover:bg-gray-50/50 transition-colors cursor-pointer" onclick="window.location='{{ route('tickets.show', $ticket) }}'">
                                            <td class="px-5 py-4 font-medium text-gray-500">#{{ $ticket->id }}</td>
                                            <td class="px-5 py-4">
                                                <span class="font-semibold text-gray-900 line-clamp-1">{{ $ticket->subject }}</span>
                                                <span class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</span>
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
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                                    {{ $ticket->messages_count }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                <p class="mt-2 text-sm text-gray-500">No support tickets found.</p>
                                                <a href="{{ route('tickets.create') }}" class="mt-3 inline-block text-sm font-medium text-orange-600 hover:text-orange-700">
                                                    Create your first ticket
                                                </a>
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
                </div>
            </div>
        </div>
    </div>

    <x-store.footer />
</x-base-layout>
