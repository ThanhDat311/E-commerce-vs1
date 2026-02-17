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
                <span class="text-gray-900 font-medium">New Ticket</span>
            </nav>

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Sidebar -->
                <div class="w-full md:w-1/4 shrink-0 sticky top-4 self-start">
                    @include('profile.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 md:p-8">
                        <!-- Header -->
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Create Support Ticket</h2>
                            <p class="text-sm text-gray-500 mt-1">Describe your issue and our team will assist you.</p>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                    Subject <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-orange-500 focus:border-orange-500 @error('subject') border-red-500 @enderror"
                                       placeholder="Brief description of your issue">
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="category" id="category" required
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-orange-500 focus:border-orange-500 @error('category') border-red-500 @enderror">
                                    <option value="">Select a category</option>
                                    <option value="order_issue" {{ old('category') == 'order_issue' ? 'selected' : '' }}>Order Issue</option>
                                    <option value="account" {{ old('category') == 'account' ? 'selected' : '' }}>Account</option>
                                    <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical</option>
                                    <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Billing</option>
                                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Order (Optional) -->
                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Related Order (Optional)
                                </label>
                                <select name="order_id" id="order_id"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-orange-500 focus:border-orange-500 @error('order_id') border-red-500 @enderror">
                                    <option value="">None</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            Order #{{ $order->id }} - {{ $order->created_at->format('M d, Y') }} ({{ $order->currency_symbol }}{{ number_format($order->total_amount, 2) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('order_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                    Priority
                                </label>
                                <select name="priority" id="priority"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-orange-500 focus:border-orange-500 @error('priority') border-red-500 @enderror">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                    Message <span class="text-red-500">*</span>
                                </label>
                                <textarea name="message" id="message" rows="6" required
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-orange-500 focus:border-orange-500 @error('message') border-red-500 @enderror"
                                          placeholder="Please describe your issue in detail...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('tickets.index') }}"
                                   class="px-5 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="px-5 py-2.5 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                                    Create Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-store.footer />
</x-base-layout>
