<x-admin-layout :pageTitle="'DSP-' . $dispute->id" :breadcrumbs="['Disputes' => route('admin.disputes.index'), 'DSP-' . $dispute->id => '#']">

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Order Header Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-xl font-bold text-gray-900">Order #ORD-{{ $dispute->order_id }}</h1>
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
                </div>
                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        {{ $dispute->created_at->format('M d, Y') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        DSP-{{ $dispute->id }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ${{ number_format($dispute->order->total, 2) }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.orders.show', $dispute->order) }}">
                <x-ui.button variant="outline">
                    View Order Details
                </x-ui.button>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left 2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Dispute Information / Conversation -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">Dispute Information</h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Buyer Message -->
                    <div class="flex gap-3">
                        <div class="h-9 w-9 rounded-full bg-slate-200 flex items-center justify-center text-xs font-semibold text-slate-600 flex-shrink-0">
                            @php $initials = collect(explode(' ', $dispute->user->name))->map(fn($n) => strtoupper(substr($n, 0, 1)))->take(2)->implode(''); @endphp
                            {{ $initials }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-sm text-gray-900">{{ $dispute->user->name }}</span>
                                <span class="text-xs text-gray-400">(Buyer)</span>
                                <span class="text-xs text-gray-400">{{ $dispute->created_at->format('M d, h:i A') }}</span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 border border-gray-100">
                                <p class="font-medium text-gray-900 mb-1">{{ ucfirst(str_replace('_', ' ', $dispute->reason)) }}</p>
                                {{ $dispute->description }}
                            </div>
                        </div>
                    </div>

                    @if($dispute->admin_response)
                        <!-- Admin Response -->
                        <div class="flex gap-3">
                            <div class="h-9 w-9 rounded-full bg-blue-100 flex items-center justify-center text-xs font-semibold text-blue-600 flex-shrink-0">
                                AD
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-sm text-gray-900">Admin</span>
                                    <span class="text-xs text-gray-400">{{ $dispute->updated_at->format('M d, h:i A') }}</span>
                                </div>
                                <div class="bg-blue-50 rounded-lg p-4 text-sm text-gray-700 border border-blue-100">
                                    {{ $dispute->admin_response }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Message Input -->
                @if(in_array($dispute->status, ['pending', 'under_review']))
                    <div class="px-6 pb-6">
                        <form action="{{ route('admin.disputes.review', $dispute) }}" method="POST">
                            @csrf
                            <div class="relative">
                                <textarea name="admin_note" rows="3" placeholder="Type an internal note or message to both parties..."
                                          class="w-full rounded-lg border border-gray-200 px-4 py-3 pr-14 text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"></textarea>
                                <button type="submit" class="absolute bottom-3 right-3 p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Related Order -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Related Order</h2>
                <div class="flex items-center justify-between">
                    <div class="space-y-1 text-sm">
                        <p><span class="font-medium text-gray-700">Order ID:</span> <span class="text-gray-900">#{{ $dispute->order->id }}</span></p>
                        <p><span class="font-medium text-gray-700">Total:</span> <span class="text-gray-900">${{ number_format($dispute->order->total, 2) }}</span></p>
                        <p><span class="font-medium text-gray-700">Status:</span> <span class="text-gray-900">{{ ucfirst($dispute->order->order_status) }}</span></p>
                    </div>
                    <a href="{{ route('admin.orders.show', $dispute->order) }}">
                        <x-ui.button variant="outline" size="sm">
                            View Order
                        </x-ui.button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar (Right 1/3) -->
        <div class="space-y-6">
            <!-- Case Summary -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4">Case Summary</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Reason for Dispute</p>
                        <div class="bg-gray-50 rounded-lg px-3 py-2 text-sm font-medium text-gray-900 border border-gray-100">
                            {{ ucfirst(str_replace('_', ' ', $dispute->reason)) }}
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Expected Resolution</p>
                        <p class="text-sm text-gray-900">Full Refund (${{ number_format($dispute->order->total, 2) }})</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Customer</p>
                        <div class="space-y-1 text-sm">
                            <p class="text-gray-900 font-medium">{{ $dispute->user->name }}</p>
                            <p class="text-gray-500">{{ $dispute->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Refund Info -->
            @if($dispute->refund)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h2 class="text-base font-semibold text-gray-900 mb-4">Refund</h2>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium text-gray-700">Amount:</span> <span class="text-gray-900 font-semibold">${{ number_format($dispute->refund->amount, 2) }}</span></p>
                        <p><span class="font-medium text-gray-700">Status:</span> <span class="text-gray-900">{{ ucfirst($dispute->refund->status) }}</span></p>
                        @if($dispute->refund->processed_at)
                            <p><span class="font-medium text-gray-700">Processed:</span> <span class="text-gray-900">{{ $dispute->refund->processed_at->format('M d, Y') }}</span></p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Bottom Action Bar -->
    @if(in_array($dispute->status, ['pending', 'under_review']))
        <div class="mt-6 bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-500 italic">
                    @if($dispute->admin_response)
                        Admin Note: "{{ Str::limit($dispute->admin_response, 60) }}"
                    @else
                        No admin note yet.
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    @if($dispute->status === 'pending')
                        <form action="{{ route('admin.disputes.review', $dispute) }}" method="POST">
                            @csrf
                            <x-ui.button type="submit" variant="outline">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                </svg>
                                Request More Info
                            </x-ui.button>
                        </form>
                    @endif

                    <form action="{{ route('admin.disputes.reject', $dispute) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this dispute?');">
                        @csrf
                        <input type="hidden" name="admin_response" value="Dispute has been rejected after review.">
                        <x-ui.button type="submit" variant="danger-outline">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reject Dispute
                        </x-ui.button>
                    </form>

                    <!-- Issue Refund Button (opens modal) -->
                    <div x-data="{ showRefundModal: false }">
                        <x-ui.button variant="success" @click="showRefundModal = true">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Issue Refund
                        </x-ui.button>

                        <!-- Refund Modal -->
                        <div x-show="showRefundModal" x-cloak
                             class="fixed inset-0 z-50 flex items-center justify-center p-4"
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                            <div class="fixed inset-0 bg-gray-500/75" @click="showRefundModal = false"></div>

                            <div class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full z-10 overflow-hidden"
                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Approve Refund</h3>
                                    </div>
                                    <button @click="showRefundModal = false" class="p-1 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <form action="{{ route('admin.disputes.resolve', $dispute) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="resolution" value="approve_refund">

                                    <div class="px-6 py-5 space-y-5">
                                        <!-- Refund Summary -->
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-sm font-medium text-gray-600">Refund Amount</span>
                                                <span class="text-2xl font-bold text-green-600">${{ number_format($dispute->order->total, 2) }}</span>
                                            </div>
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-500">Order ID</span>
                                                    <span class="font-medium text-gray-900">#ORD-{{ $dispute->order_id }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Refund Amount Input -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Refund Amount</label>
                                            <input type="number" name="refund_amount" step="0.01" max="{{ $dispute->order->total }}" value="{{ $dispute->order->total }}"
                                                   class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                        </div>

                                        <!-- Admin Note -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Note to Buyer</label>
                                            <textarea name="admin_response" rows="4" required maxlength="500"
                                                      placeholder="Hello, We have reviewed your dispute and approved a refund..."
                                                      class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none"></textarea>
                                            <p class="text-xs text-gray-400 mt-1">This note will be sent via email to the buyer.</p>
                                        </div>

                                        <!-- Warning -->
                                        <div class="flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-lg p-3">
                                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            <p class="text-xs text-blue-700">This action cannot be undone. The dispute status will be closed.</p>
                                        </div>
                                    </div>

                                    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-3 bg-gray-50/50">
                                        <x-ui.button variant="outline" type="button" @click="showRefundModal = false">
                                            Cancel
                                        </x-ui.button>
                                        <x-ui.button variant="success" type="submit">
                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Confirm & Issue Refund
                                        </x-ui.button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</x-admin-layout>
