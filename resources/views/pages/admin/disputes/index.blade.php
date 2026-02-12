<x-admin-layout>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Disputes Management</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="mb-6 bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('admin.disputes.index') }}" class="flex gap-4">
            <div class="flex-1">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="flex items-end">
                <x-ui.button type="submit" variant="primary">
                    Filter
                </x-ui.button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="p-6">
            <x-ui.table>
                <x-slot:thead>
                    <th scope="col" class="px-6 py-3">Dispute ID</th>
                    <th scope="col" class="px-6 py-3">Order ID</th>
                    <th scope="col" class="px-6 py-3">Customer</th>
                    <th scope="col" class="px-6 py-3">Reason</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Date</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </x-slot:thead>
                <x-slot:tbody>
                    @forelse($disputes as $dispute)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                #{{ $dispute->id }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $dispute->order_id) }}" class="text-blue-600 hover:underline">
                                    #{{ $dispute->order_id }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $dispute->user->name }}</div>
                                    <div class="text-gray-500">{{ $dispute->user->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{ ucfirst(str_replace('_', ' ', $dispute->reason)) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusVariant = match($dispute->status) {
                                        'resolved' => 'success',
                                        'under_review' => 'pending',
                                        'rejected' => 'danger',
                                        default => 'neutral'
                                    };
                                @endphp
                                <x-ui.badge :variant="$statusVariant">
                                    {{ ucfirst(str_replace('_', ' ', $dispute->status)) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $dispute->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.disputes.show', $dispute) }}">
                                    <x-ui.button variant="secondary" size="sm">
                                        Review
                                    </x-ui.button>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                No disputes found.
                            </td>
                        </tr>
                    @endforelse
                </x-slot:tbody>
            </x-ui.table>
        </div>

        @if($disputes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $disputes->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
