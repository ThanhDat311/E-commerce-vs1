<x-admin-layout>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">User Details</h1>
                <p class="mt-1 text-sm text-gray-600">View and manage user information</p>
            </div>
            <a href="{{ route('admin.users.index') }}">
                <x-ui.button variant="secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Users
                </x-ui.button>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - User Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->phone_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->address ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Role</label>
                        <p class="mt-1">
                            <x-ui.badge variant="neutral">{{ $user->assignedRole->name ?? 'N/A' }}</x-ui.badge>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Account Status</label>
                        <p class="mt-1">
                            @if($user->is_active)
                                <x-ui.badge variant="success">Active</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger">Inactive</x-ui.badge>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Login History -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Login History</h2>
                @if($user->authLogs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date/Time</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Device</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Risk</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->authLogs as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $log->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ $log->ip_address }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">
                                            {{ Str::limit($log->user_agent, 40) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($log->is_successful)
                                                <x-ui.badge variant="success">Success</x-ui.badge>
                                            @else
                                                <x-ui.badge variant="danger">Failed</x-ui.badge>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($log->risk_level === 'high')
                                                <x-ui.badge variant="danger">High</x-ui.badge>
                                            @elseif($log->risk_level === 'medium')
                                                <x-ui.badge variant="warning">Medium</x-ui.badge>
                                            @else
                                                <x-ui.badge variant="success">Low</x-ui.badge>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No login history available.</p>
                @endif
            </div>
        </div>

        <!-- Right Column - Stats & Actions -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Total Orders</label>
                        <p class="mt-1 text-2xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Active Orders</label>
                        <p class="mt-1 text-2xl font-bold text-blue-600">{{ $stats['active_orders'] }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Total Spent</label>
                        <p class="mt-1 text-2xl font-bold text-green-600">${{ number_format($stats['total_spent'], 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Member Since</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Last Login</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $stats['last_login'] ? $stats['last_login']->format('M d, Y H:i') : 'Never' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Security Actions Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Security Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="block">
                        <x-ui.button variant="primary" class="w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit User
                        </x-ui.button>
                    </a>

                    <form action="{{ route('admin.users.reset_password', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to reset this user\'s password?');">
                        @csrf
                        <x-ui.button type="submit" variant="outline" class="w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Reset Password
                        </x-ui.button>
                    </form>

                    <form action="{{ route('admin.users.force_logout', $user) }}" method="POST" onsubmit="return confirm('Force logout this user from all devices?');">
                        @csrf
                        <x-ui.button type="submit" variant="outline" class="w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Force Logout
                        </x-ui.button>
                    </form>

                    <form action="{{ route('admin.users.toggle_status', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <x-ui.button type="submit" variant="{{ $user->is_active ? 'warning' : 'success' }}" class="w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            {{ $user->is_active ? 'Suspend Account' : 'Activate Account' }}
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
