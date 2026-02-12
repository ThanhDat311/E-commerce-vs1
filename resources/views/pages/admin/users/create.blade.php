<x-admin-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create New User</h1>
        <p class="mt-1 text-sm text-gray-600">Add a new user to the system</p>
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
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Role -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role_id" id="role_id" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
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
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}">
                    <x-ui.button type="button" variant="secondary">
                        Cancel
                    </x-ui.button>
                </a>
                <x-ui.button type="submit" variant="primary">
                    Create User
                </x-ui.button>
            </div>
        </form>
    </div>
</x-admin-layout>
