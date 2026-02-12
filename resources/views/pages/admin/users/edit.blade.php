<x-admin-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit User</h1>
        <p class="mt-1 text-sm text-gray-600">Update user information</p>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role_id" id="role_id" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Address
                    </label>
                    <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Active Status -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Active Account</span>
                    </label>
                </div>

                <!-- Password (Optional) -->
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-700 mb-3">Change Password (leave blank to keep current password)</p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password
                    </label>
                    <input type="password" name="password" id="password"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- User Info -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-700 mb-3">User Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="ml-2 text-gray-900">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Last Updated:</span>
                        <span class="ml-2 text-gray-900">{{ $user->updated_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">User ID:</span>
                        <span class="ml-2 text-gray-900">#{{ $user->id }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}">
                    <x-ui.button type="button" variant="secondary">
                        Cancel
                    </x-ui.button>
                </a>
                <x-ui.button type="submit" variant="primary">
                    Update User
                </x-ui.button>
            </div>
        </form>
    </div>
</x-admin-layout>
