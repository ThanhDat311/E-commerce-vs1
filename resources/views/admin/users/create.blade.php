@extends('layouts.admin')
@section('title', 'Create User')
@section('content')
<x-admin.header 
    title="Create User" 
    subtitle="Add a new user account to the system"
    icon="user-plus"
    background="blue"
/>

<div class="max-w-4xl mx-auto px-6 py-8">
    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center gap-2 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-700">Dashboard</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700">Users</a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-700 font-medium">Create</span>
    </div>

    <x-admin.card variant="white" border="left" borderColor="blue">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Name & Email Row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-admin.input 
                    name="name" 
                    label="Full Name"
                    type="text"
                    required
                    value="{{ old('name') }}"
                    placeholder="John Doe"
                    :error="$errors->first('name')"
                />
                <x-admin.input 
                    name="email" 
                    label="Email Address"
                    type="email"
                    required
                    value="{{ old('email') }}"
                    placeholder="john@example.com"
                    :error="$errors->first('email')"
                />
            </div>

            {{-- Phone & Role Row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-admin.input 
                    name="phone_number" 
                    label="Phone Number"
                    type="text"
                    value="{{ old('phone_number') }}"
                    placeholder="+1 (555) 123-4567"
                    :error="$errors->first('phone_number')"
                />
                <x-admin.select 
                    name="role_id" 
                    label="Role"
                    required
                    :error="$errors->first('role_id')"
                >
                    <option value="">-- Select Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </x-admin.select>
            </div>

            {{-- Address --}}
            <div>
                <x-admin.input 
                    name="address" 
                    label="Address"
                    type="text"
                    value="{{ old('address') }}"
                    placeholder="Street address, City, State, ZIP code"
                    :error="$errors->first('address')"
                />
            </div>

            {{-- Password & Confirmation Row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-admin.input 
                    name="password" 
                    label="Password"
                    type="password"
                    required
                    placeholder="Enter a secure password"
                    :error="$errors->first('password')"
                />
                <x-admin.input 
                    name="password_confirmation" 
                    label="Confirm Password"
                    type="password"
                    required
                    placeholder="Confirm password"
                    :error="$errors->first('password_confirmation')"
                />
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <x-admin.button 
                    type="submit" 
                    variant="primary"
                    icon="save"
                >
                    Create User
                </x-admin.button>
                <x-admin.button 
                    href="{{ route('admin.users.index') }}"
                    variant="secondary"
                    icon="times"
                >
                    Cancel
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>
</div>
@endsection