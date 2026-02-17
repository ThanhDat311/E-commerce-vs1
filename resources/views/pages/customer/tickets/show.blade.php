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
                <a href="{{ route('tickets.index') }}" class="hover:text-gray-900">Support Tickets</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900 font-medium">Ticket #{{ $ticket->id }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-200px)]">
                
                <!-- Left Column: Chat History -->
                <div class="lg:col-span-2 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <!-- Ticket Header (Mobile only) -->
                    <div class="p-4 border-b border-gray-100 lg:hidden">
                        <h1 class="text-lg font-bold text-gray-900">{{ $ticket->subject }}</h1>
                        <p class="text-sm text-gray-500">
                            Ticket #{{ $ticket->id }} · {{ $ticket->created_at->format('M d, H:i') }}
                        </p>
                    </div>

                    <!-- Messages Area -->
                    <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50" id="messages-container">
                        <!-- Original Ticket Description -->
                        <div class="flex flex-col gap-1">
                            <div class="flex items-end gap-2">
                                <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-xs font-bold text-orange-600 flex-shrink-0">
                                    {{ substr($ticket->user->name, 0, 1) }}
                                </div>
                                <div class="bg-white p-4 rounded-2xl rounded-bl-sm shadow-sm max-w-[85%] border border-gray-100">
                                    <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $ticket->subject }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 ml-10">{{ $ticket->created_at->format('M d, H:i') }}</span>
                        </div>

                        @foreach($ticket->messages as $msg)
                            @php
                                $isMe = $msg->user_id === auth()->id();
                                $isStaff = in_array($msg->user->role_id, [1, 2]); // Admin or Staff
                            @endphp
                            <div class="flex flex-col gap-1 {{ $isMe ? 'items-end' : 'items-start' }}">
                                <div class="flex items-end gap-2 {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                                    @if(!$isMe)
                                        <div class="h-8 w-8 rounded-full {{ $isStaff ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-600' }} flex items-center justify-center text-xs font-bold flex-shrink-0" title="{{ $msg->user->name }}">
                                            {{ substr($msg->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    
                                    <div class="p-4 rounded-2xl shadow-sm max-w-[85%] {{ $isMe ? 'bg-orange-600 text-white rounded-br-sm' : 'bg-white text-gray-800 rounded-bl-sm border border-gray-100' }}">
                                        @if(!$isMe && $isStaff)
                                            <p class="text-xs font-semibold mb-1 {{ $isMe ? 'text-orange-200' : 'text-blue-600' }}">{{ $msg->user->name }} (Support Team)</p>
                                        @endif
                                        <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                                        @if($msg->attachment)
                                            <a href="{{ Storage::url($msg->attachment) }}" target="_blank" class="mt-2 flex items-center gap-2 p-2 rounded-lg {{ $isMe ? 'bg-orange-500 hover:bg-orange-400' : 'bg-gray-100 hover:bg-gray-200' }} transition-colors text-xs">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                Attachment
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400 {{ $isMe ? 'mr-0' : 'ml-10' }}">{{ $msg->created_at->format('M d, H:i') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Reply Box -->
                    @if(!in_array($ticket->status, ['closed']))
                        <div class="p-4 bg-white border-t border-gray-200">
                            <form action="{{ route('tickets.messages.store', $ticket) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="flex items-start gap-4">
                                    <div class="flex-1 relative">
                                        <textarea name="message" rows="3" placeholder="Type your reply..." required
                                                  class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 resize-none text-sm p-3"></textarea>
                                        
                                        <!-- Attachment Button -->
                                        <div class="absolute bottom-3 right-3">
                                            <label for="attachment" class="cursor-pointer text-gray-400 hover:text-orange-600 transition-colors p-1 rounded-md hover:bg-orange-50 block">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                            </label>
                                            <input type="file" name="attachment" id="attachment" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0] ? this.files[0].name : ''">
                                        </div>
                                    </div>
                                    <button type="submit" class="self-end px-4 py-2 bg-orange-600 text-white rounded-lg font-medium hover:bg-orange-700 transition-colors flex items-center gap-2">
                                        <span>Send</span>
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                    </button>
                                </div>
                                <div class="mt-1 text-xs text-gray-500 pl-1" id="file-name"></div>
                            </form>
                        </div>
                    @else
                        <div class="p-4 bg-gray-50 border-t border-gray-200 text-center">
                            <p class="text-sm text-gray-500">This ticket is closed. Contact support to reopen.</p>
                        </div>
                    @endif
                </div>

                <!-- Right Sidebar: Details -->
                <div class="flex flex-col gap-6">
                    <!-- Ticket Info -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                        <h2 class="text-base font-bold text-gray-900 mb-4">Ticket Details</h2>
                        
                        <h3 class="text-sm text-gray-500 mb-1">Subject</h3>
                        <p class="text-sm font-medium text-gray-900 mb-4">{{ $ticket->subject }}</p>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                                @php
                                    $statusColors = [
                                        'open' => 'bg-blue-100 text-blue-700',
                                        'in_progress' => 'bg-amber-100 text-amber-700',
                                        'resolved' => 'bg-green-100 text-green-700',
                                        'closed' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $statusLabel = ucfirst(str_replace('_', ' ', $ticket->status));
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Priority</label>
                                @php
                                    $priorityColors = [
                                        'low' => 'text-gray-600',
                                        'medium' => 'text-blue-600',
                                        'high' => 'text-orange-600',
                                        'urgent' => 'text-red-600 font-bold',
                                    ];
                                @endphp
                                <span class="text-sm font-medium {{ $priorityColors[$ticket->priority] ?? 'text-gray-600' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Category</label>
                                <span class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $ticket->category)) }}</span>
                            </div>

                            @if($ticket->order_id)
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Related Order</label>
                                    <a href="{{ route('orders.show', $ticket->order_id) }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                                        Order #{{ $ticket->order_id }}
                                    </a>
                                </div>
                            @endif

                            @if($ticket->assignedTo)
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Assigned To</label>
                                    <span class="text-sm text-gray-900">{{ $ticket->assignedTo->name }}</span>
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Created</label>
                                <span class="text-sm text-gray-900">{{ $ticket->created_at->format('M d, Y H:i') }}</span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Last Updated</label>
                                <span class="text-sm text-gray-900">{{ $ticket->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-store.footer />
    
    @push('scripts')
    <script>
        // Scroll to bottom of chat
        const msgContainer = document.getElementById('messages-container');
        msgContainer.scrollTop = msgContainer.scrollHeight;
    </script>
    @endpush

</x-base-layout>
