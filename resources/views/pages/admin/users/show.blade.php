<x-admin-layout :pageTitle="'User Profile'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Users' => route('admin.users.index'), $user->name => '#']">
    
    <!-- Header Actions -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Profile</h1>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="mailto:{{ $user->email }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Send Message
            </a>
            
            <form action="{{ route('admin.users.reset_password', $user) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Reset Password
                </button>
            </form>

            <form action="{{ route('admin.users.toggle_status', $user) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center px-4 py-2 {{ $user->is_active ? 'bg-red-50 text-red-700 border border-red-200 hover:bg-red-100' : 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100' }} rounded-lg font-medium shadow-sm transition-colors">
                    {{ $user->is_active ? 'Suspend User' : 'Activate User' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Profile Summary Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="relative">
                <div class="h-24 w-24 rounded-full bg-gray-100 ring-4 ring-white shadow-sm overflow-hidden flex items-center justify-center">
                    <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=475569&background=e2e8f0&size=96" alt="{{ $user->name }}">
                </div>
            </div>
            <div class="flex-1 text-center md:text-left">
                <div class="flex flex-col md:flex-row md:items-center gap-2 mb-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    @if($user->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 w-fit mx-auto md:mx-0">Active</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 w-fit mx-auto md:mx-0">Suspended</span>
                    @endif
                </div>
                <p class="text-gray-500 mb-4">{{ $user->email }}</p>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-8 border-t border-gray-100 pt-4 md:border-t-0 md:pt-0">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">User ID</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">#USR-{{ 1000 + $user->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Orders</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Spent Total</p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">${{ number_format($stats['total_spent'], 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                 <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-400 hover:text-gray-600 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="space-y-6 lg:col-span-1">
            <!-- Account Information -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Account Information</h3>
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Join Date</p>
                        <p class="text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }} <span class="text-gray-400">({{ $user->created_at->diffForHumans() }})</span></p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Phone Number</p>
                        <p class="text-sm text-gray-900">{{ $user->phone_number ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Shipping Address</p>
                        <p class="text-sm text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Platform Role</p>
                        <div class="flex items-center gap-2 mt-1">
                             <span class="inline-flex items-center text-sm font-medium text-gray-700">
                                @if($user->assignedRole->name === 'Admin')
                                    <svg class="w-4 h-4 mr-1.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                @elseif($user->assignedRole->name === 'Vendor')
                                    <svg class="w-4 h-4 mr-1.5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg>
                                @else
                                    <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" /></svg>
                                @endif
                                {{ $user->assignedRole->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                 <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Activity Log</h3>
                    <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-800">View All</a>
                </div>
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($activityLogs as $log)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Logged in from <span class="font-medium text-gray-900">{{ $log->ip_address }}</span></p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $log->created_at }}">{{ $log->created_at->diffForHumans() }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li><p class="text-sm text-gray-500">No recent activity.</p></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column (Orders) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
                    <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-800">View All Orders</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">Order ID</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Date</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Status</th>
                                <th scope="col" class="px-6 py-3 font-semibold text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                             @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-blue-600">
                                        #ORD-{{ 8000 + $order->id }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'completed' => 'bg-green-100 text-green-700',
                                                'delivered' => 'bg-green-100 text-green-700',
                                                'processing' => 'bg-blue-100 text-blue-700',
                                                'shipped' => 'bg-purple-100 text-purple-700',
                                                'pending' => 'bg-amber-100 text-amber-700',
                                                'cancelled' => 'bg-gray-100 text-gray-700',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-gray-900">
                                        ${{ number_format($order->total, 2) }}
                                    </td>
                                </tr>
                             @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">No recent orders found.</td>
                                </tr>
                             @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50/50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-sm text-gray-500">Latest transactions only</span>
                    <button class="flex items-center text-sm font-medium text-gray-700 hover:text-gray-900">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download History
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
