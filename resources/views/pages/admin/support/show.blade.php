<x-admin-layout :pageTitle="'Ticket #'.$ticket->id" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Helpdesk' => route('admin.support.index'), '#'.$ticket->id => route('admin.support.show', $ticket)]">

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-200px)]">
        
        <!-- Left Column: Chat History -->
        <div class="lg:col-span-2 flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Ticket Header (Mobile only) -->
            <div class="p-4 border-b border-gray-100 lg:hidden">
                <h1 class="text-lg font-bold text-gray-900">{{ $ticket->subject }}</h1>
                <p class="text-sm text-gray-500">
                    Started by <span class="font-medium text-gray-900">{{ $ticket->user->name }}</span>
                    · {{ $ticket->created_at->format('M d, H:i') }}
                </p>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50" id="messages-container">
                <!-- Original Ticket Description -->
                <div class="flex flex-col gap-1">
                    <div class="flex items-end gap-2">
                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 flex-shrink-0">
                            {{ substr($ticket->user->name, 0, 1) }}
                        </div>
                        <div class="bg-white p-4 rounded-2xl rounded-bl-sm shadow-sm max-w-[85%] border border-gray-100">
                            <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $ticket->subject }}</p>
                            <!-- If we had a 'description' field, it would go here. For now subject acts as title/desc -->
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
                            
                            <div class="p-4 rounded-2xl shadow-sm max-w-[85%] {{ $isMe ? 'bg-blue-600 text-white rounded-br-sm' : 'bg-white text-gray-800 rounded-bl-sm border border-gray-100' }}">
                                <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                                @if($msg->attachment)
                                    <a href="{{ Storage::url($msg->attachment) }}" target="_blank" class="mt-2 flex items-center gap-2 p-2 rounded-lg {{ $isMe ? 'bg-blue-500 hover:bg-blue-400' : 'bg-gray-100 hover:bg-gray-200' }} transition-colors text-xs">
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
            <div class="p-4 bg-white border-t border-gray-200">
                <form action="{{ route('admin.support.reply', $ticket) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="flex items-start gap-4">
                        <div class="flex-1 relative">
                            <textarea name="message" rows="3" placeholder="Type your reply..." required
                                      class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 resize-none text-sm p-3"></textarea>
                            
                            <!-- Attachment Button -->
                            <div class="absolute bottom-3 right-3">
                                <label for="attachment" class="cursor-pointer text-gray-400 hover:text-blue-600 transition-colors p-1 rounded-md hover:bg-blue-50 block">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                </label>
                                <input type="file" name="attachment" id="attachment" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0] ? this.files[0].name : ''">
                            </div>
                        </div>
                        <button type="submit" class="self-end px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <span>Send</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        </button>
                    </div>
                    <div class="mt-1 text-xs text-gray-500 pl-1" id="file-name"></div>
                </form>
            </div>
        </div>

        <!-- Right Sidebar: Details -->
        <div class="flex flex-col gap-6">
            <!-- Ticket Info -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4">Ticket Details</h2>
                
                <h3 class="text-sm text-gray-500 mb-1">Subject</h3>
                <p class="text-sm font-medium text-gray-900 mb-4">{{ $ticket->subject }}</p>

                <form action="{{ route('admin.support.update', $ticket) }}" method="POST" id="update-form">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</label>
                            <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Priority</label>
                            <select name="priority" onchange="this.form.submit()" class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Assigned To</label>
                            <select name="assigned_to" onchange="this.form.submit()" class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Unassigned --</option>
                                @foreach($staffMembers as $staff)
                                    <option value="{{ $staff->id }}" {{ $ticket->assigned_to == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Requester Info -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4">Requester info</h2>
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center text-sm font-bold text-gray-600">
                        {{ substr($ticket->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $ticket->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ucfirst($ticket->user->role_id === 3 ? 'Customer' : ($ticket->user->role_id === 4 ? 'Vendor' : 'User'))}}</p>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        {{ $ticket->user->email }}
                    </div>
                    @if($ticket->user->phone_number)
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            {{ $ticket->user->phone_number }}
                        </div>
                    @endif
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        Joined {{ $ticket->user->created_at->format('M d, Y') }}
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                    <a href="{{ $ticket->user->role_id === 4 ? route('admin.vendors.show', $ticket->user) : route('admin.users.index', ['search' => $ticket->user->email]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        View User Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        // Scroll to bottom of chat
        const msgContainer = document.getElementById('messages-container');
        msgContainer.scrollTop = msgContainer.scrollHeight;
    </script>
    @endpush

</x-admin-layout>
