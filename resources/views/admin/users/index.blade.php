<!-- htmlhint style-disabled-in-attr: false, script-disabled-in-attr: false -->
@extends('admin.layout.admin')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">User Management</h1>
            <p class="text-gray-600 text-sm mt-1">Manage user accounts, roles, and access permissions</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-admin-primary hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors">
            <i class="fas fa-plus"></i>
            <span>Add New User</span>
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex gap-3">
            <div class="flex-shrink-0 text-green-600">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex gap-3">
            <div class="flex-shrink-0 text-red-600">
                <i class="fas fa-exclamation-circle text-lg"></i>
            </div>
            <div>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Role Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-shield-alt mr-1"></i> Filter by Role
                    </label>
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-admin-primary focus:border-transparent">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected(request('role')==$role->id)>
                            {{ ucfirst($role->name) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-1"></i> Filter by Status
                    </label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-admin-primary focus:border-transparent">
                        <option value="">All Statuses</option>
                        <option value="active" @selected(request('status')==='active' )>Active</option>
                        <option value="locked" @selected(request('status')==='locked' )>Locked</option>
                    </select>
                </div>

                <!-- Apply Filters -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-admin-primary hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                        <i class="fas fa-filter mr-1"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 hover:bg-gray-50 rounded-lg font-medium transition-colors">
                        <i class="fas fa-redo mr-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-users mr-2"></i> Users List
                </h2>
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                    <i class="fas fa-database text-xs"></i> {{ $users->total() }} users
                </span>
            </div>
        </div>

        @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Role</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wide">Joined</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- User Avatar & Name -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-admin-primary to-blue-700 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">ID: #{{ $user->id }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-envelope text-gray-400 text-sm"></i>
                                <span class="text-gray-700 text-sm">{{ $user->email }}</span>
                            </div>
                        </td>

                        <!-- Role Badge -->
                        <td class="px-6 py-4">
                            @php
                            $roleBadges = [
                            'admin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'icon' => 'crown'],
                            'staff' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'user-tie'],
                            'vendor' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'store'],
                            'customer' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'user'],
                            ];
                            $role = strtolower($user->assignedRole->name ?? 'unknown');
                            $badge = $roleBadges[$role] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'user'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $badge['bg'] }} {{ $badge['text'] }}">
                                <i class="fas fa-{{ $badge['icon'] }} text-xs"></i>
                                {{ ucfirst($user->assignedRole->name ?? 'Unknown') }}
                            </span>
                        </td>

                        <!-- Account Status -->
                        <td class="px-6 py-4 text-center">
                            @if($user->is_active)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <span class="w-2 h-2 rounded-full bg-green-600"></span>
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                Locked
                            </span>
                            @endif
                        </td>

                        <!-- Joined Date -->
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar text-gray-400 text-xs"></i>
                                {{ $user->created_at->format('M d, Y') }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <!-- Edit Role Button -->
                                <button onclick="openRoleModal(this.dataset.userId, this.dataset.userName, this.dataset.roleId)"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    data-role-id="{{ $user->role_id }}"
                                    class="p-2 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors"
                                    title="Edit Role">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <!-- Lock/Unlock Button -->
                                @if($user->id !== auth()->id())
                                @if($user->is_active)
                                <button onclick="confirmLock(this.dataset.userId, this.dataset.userName)"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    class="p-2 hover:bg-red-100 text-red-600 rounded-lg transition-colors"
                                    title="Lock Account">
                                    <i class="fas fa-lock"></i>
                                </button>
                                @else
                                <button onclick="confirmUnlock(this.dataset.userId, this.dataset.userName)"
                                    data-user-id="{{ $user->id }}"
                                    data-user-name="{{ $user->name }}"
                                    class="p-2 hover:bg-green-100 text-green-600 rounded-lg transition-colors"
                                    title="Unlock Account">
                                    <i class="fas fa-lock-open"></i>
                                </button>
                                @endif
                                @else
                                <div class="p-2 text-gray-400" title="Cannot lock your own account">
                                    <i class="fas fa-ban"></i>
                                </div>
                                @endif

                                <!-- View Profile -->
                                <a href="{{ route('admin.users.edit', $user) }}"
                                    class="p-2 hover:bg-gray-100 text-gray-600 rounded-lg transition-colors"
                                    title="View Profile">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Showing <span class="font-semibold">{{ $users->firstItem() }}</span> to
                <span class="font-semibold">{{ $users->lastItem() }}</span> of
                <span class="font-semibold">{{ $users->total() }}</span> users
            </div>
            <div class="flex gap-2">
                @if($users->onFirstPage())
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
                @else
                <a href="{{ $users->previousPageUrl() }}" class="px-3 py-2 border border-gray-300 hover:bg-gray-100 rounded-lg text-gray-600 transition-colors">
                    <i class="fas fa-chevron-left"></i>
                </a>
                @endif

                @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                @if($page == $users->currentPage())
                <button class="px-3 py-2 bg-admin-primary text-white rounded-lg font-medium">{{ $page }}</button>
                @else
                <a href="{{ $url }}" class="px-3 py-2 border border-gray-300 hover:bg-gray-100 rounded-lg text-gray-600 transition-colors">{{ $page }}</a>
                @endif
                @endforeach

                @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-3 py-2 border border-gray-300 hover:bg-gray-100 rounded-lg text-gray-600 transition-colors">
                    <i class="fas fa-chevron-right"></i>
                </a>
                @else
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </button>
                @endif
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="px-6 py-12 text-center">
            <div class="text-gray-400 text-5xl mb-4">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No users found</h3>
            <p class="text-gray-600 mb-6">Try adjusting your filters or create a new user</p>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-admin-primary hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                <i class="fas fa-plus"></i> Create New User
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal: Edit Role -->
<div id="roleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-3">
            <div class="text-2xl text-admin-primary">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Change User Role</h3>
                <p class="text-sm text-gray-600"><span id="roleModalUserName"></span></p>
            </div>
        </div>

        <form id="roleForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Role</label>
                <select name="role_id" id="roleSelect" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-admin-primary focus:border-transparent">
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-xs text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Security Note:</strong> Role changes are logged for audit purposes.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeRoleModal()" class="flex-1 px-4 py-2 border border-gray-300 hover:bg-gray-50 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-admin-primary hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Update Role
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Confirm Lock -->
<div id="lockModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-red-200 bg-red-50 flex items-center gap-3">
            <div class="text-2xl text-red-600">
                <i class="fas fa-lock"></i>
            </div>
            <h3 class="font-bold text-gray-900">Lock Account</h3>
        </div>

        <div class="p-6 space-y-4">
            <p class="text-gray-700">
                Are you sure you want to lock <strong id="lockModalUserName" class="text-gray-900"></strong>'s account?
                <br><br>
                They will not be able to access the system until the account is unlocked.
            </p>

            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                <p class="text-xs text-red-800">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Warning:</strong> This action is logged in the audit trail.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeLockModal()" class="flex-1 px-4 py-2 border border-gray-300 hover:bg-gray-50 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <form id="lockForm" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                        Lock Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Confirm Unlock -->
<div id="unlockModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-green-200 bg-green-50 flex items-center gap-3">
            <div class="text-2xl text-green-600">
                <i class="fas fa-lock-open"></i>
            </div>
            <h3 class="font-bold text-gray-900">Unlock Account</h3>
        </div>

        <div class="p-6 space-y-4">
            <p class="text-gray-700">
                Are you sure you want to unlock <strong id="unlockModalUserName" class="text-gray-900"></strong>'s account?
                <br><br>
                They will regain full access to the system immediately.
            </p>

            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                <p class="text-xs text-green-800">
                    <i class="fas fa-shield-alt mr-1"></i>
                    <strong>Note:</strong> This action is logged in the audit trail.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeUnlockModal()" class="flex-1 px-4 py-2 border border-gray-300 hover:bg-gray-50 rounded-lg font-medium transition-colors">
                    Cancel
                </button>
                <form id="unlockForm" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                        Unlock Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Role Modal Functions
    function openRoleModal(userId, userName, currentRole) {
        document.getElementById('roleModal').classList.remove('hidden');
        document.getElementById('roleModalUserName').textContent = userName;
        document.getElementById('roleSelect').value = currentRole;

        const form = document.getElementById('roleForm');
        form.action = `/admin/users/${userId}/update-role`;
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.add('hidden');
    }

    // Lock Modal Functions
    function confirmLock(userId, userName) {
        document.getElementById('lockModal').classList.remove('hidden');
        document.getElementById('lockModalUserName').textContent = userName;

        const form = document.getElementById('lockForm');
        form.action = `/admin/users/${userId}/toggle-status`;
    }

    function closeLockModal() {
        document.getElementById('lockModal').classList.add('hidden');
    }

    // Unlock Modal Functions
    function confirmUnlock(userId, userName) {
        document.getElementById('unlockModal').classList.remove('hidden');
        document.getElementById('unlockModalUserName').textContent = userName;

        const form = document.getElementById('unlockForm');
        form.action = `/admin/users/${userId}/toggle-status`;
    }

    function closeUnlockModal() {
        document.getElementById('unlockModal').classList.add('hidden');
    }

    // Close modals on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeRoleModal();
            closeLockModal();
            closeUnlockModal();
        }
    });
</script>

@endsection