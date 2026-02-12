<x-admin-layout>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Dispute #{{ $dispute->id }}</h1>
            <p class="mt-1 text-sm text-gray-600">Submitted on {{ $dispute->created_at->format('M d, Y \a\t H:i') }}</p>
        </div>
        <a href="{{ route('admin.disputes.index') }}">
            <x-ui.button variant="secondary">
                Back to Disputes
            </x-ui.button>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Dispute Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Dispute Information</h2>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-700">Reason:</span>
                        <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $dispute->reason)) }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700">Description:</span>
                        <p class="text-sm text-gray-900 mt-1">{{ $dispute->description }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-700">Status:</span>
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
                    </div>
                    @if($dispute->admin_response)
                        <div class="mt-4 p-4 bg-blue-50 rounded">
                            <span class="text-sm font-medium text-gray-700">Admin Response:</span>
                            <p class="text-sm text-gray-900 mt-1">{{ $dispute->admin_response }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Order -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Related Order</h2>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium">Order #{{ $dispute->order->id }}</p>
                        <p class="text-sm text-gray-600">Total: ${{ number_format($dispute->order->total, 2) }}</p>
                        <p class="text-sm text-gray-600">Status: {{ ucfirst($dispute->order->order_status) }}</p>
                    </div>
                    <a href="{{ route('admin.orders.show', $dispute->order) }}">
                        <x-ui.button variant="secondary" size="sm">
                            View Order
                        </x-ui.button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="font-medium">Name:</span> {{ $dispute->user->name }}</p>
                    <p><span class="font-medium">Email:</span> {{ $dispute->user->email }}</p>
                </div>
            </div>

            <!-- Actions -->
            @if($dispute->status === 'pending')
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                    <form action="{{ route('admin.disputes.review', $dispute) }}" method="POST">
                        @csrf
                        <x-ui.button type="submit" variant="primary" class="w-full mb-3">
                            Mark as Under Review
                        </x-ui.button>
                    </form>
                </div>
            @endif

            @if(in_array($dispute->status, ['pending', 'under_review']))
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Resolve Dispute</h2>
                    <form action="{{ route('admin.disputes.resolve', $dispute) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Resolution</label>
                                <select name="resolution" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select...</option>
                                    <option value="approve_refund">Approve Full Refund</option>
                                    <option value="partial_refund">Approve Partial Refund</option>
                                    <option value="reject">Reject Dispute</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Refund Amount</label>
                                <input type="number" name="refund_amount" step="0.01" max="{{ $dispute->order->total }}" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Response</label>
                                <textarea name="admin_response" rows="4" required
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>
                            <x-ui.button type="submit" variant="primary" class="w-full">
                                Submit Resolution
                            </x-ui.button>
                        </div>
                    </form>

                    <form action="{{ route('admin.disputes.reject', $dispute) }}" method="POST" class="mt-4">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                                <textarea name="admin_response" rows="3" required
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>
                            <x-ui.button type="submit" variant="danger" class="w-full">
                                Reject Dispute
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Refund Info -->
            @if($dispute->refund)
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Refund</h2>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium">Amount:</span> ${{ number_format($dispute->refund->amount, 2) }}</p>
                        <p><span class="font-medium">Status:</span> {{ ucfirst($dispute->refund->status) }}</p>
                        @if($dispute->refund->processed_at)
                            <p><span class="font-medium">Processed:</span> {{ $dispute->refund->processed_at->format('M d, Y') }}</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
