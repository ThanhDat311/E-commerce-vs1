<x-admin-layout>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-900">Users Management</h1>
        <a href="{{ route('admin.users.create') }}">
            <x-ui.button variant="primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New User
            </x-ui.button>
        </a>
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

    <!-- Filters -->
    <div class="mb-6 bg-white shadow rounded-lg p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Search by name, email, or ID..."
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex-1">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                    <th scope="col" class="px-6 py-3">Name</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Role</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Joined</th>
                    <th scope="col" class="px-6 py-3">Actions</th>
                </x-slot:thead>
                <x-slot:tbody>
                    @forelse($users as $user)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <x-ui.badge variant="neutral">
                                    {{ $user->assignedRole->name ?? 'N/A' }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_active)
                                    <x-ui.badge variant="success">Active</x-ui.badge>
                                @else
                                    <x-ui.badge variant="danger">Inactive</x-ui.badge>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-500">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}">
                                        <x-ui.button variant="outline" size="sm">
                                            View
                                        </x-ui.button>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}">
                                        <x-ui.button variant="secondary" size="sm">
                                            Edit
                                        </x-ui.button>
                                    </a>
                                    <form action="{{ route('admin.users.toggle_status', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <x-ui.button type="submit" variant="outline" size="sm">
                                            {{ $user->is_active ? 'Suspend' : 'Activate' }}
                                        </x-ui.button>
                                    </form>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="danger" size="sm">
                                            Delete
                                        </x-ui.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </x-slot:tbody>
            </x-ui.table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
