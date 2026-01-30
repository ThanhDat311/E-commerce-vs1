@extends('layouts.vendor')

@section('title', 'Vendor Profile')

@section('content')
<div x-data="{ activeTab: 'profile' }">
    
    {{-- Tabs Navigation --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button 
                @click="activeTab = 'profile'"
                :class="{ 'border-admin-primary text-admin-primary': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'profile' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                <i class="fas fa-user mr-2"></i> Personal Info
            </button>
            <button 
                @click="activeTab = 'shop'"
                :class="{ 'border-admin-primary text-admin-primary': activeTab === 'shop', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'shop' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                <i class="fas fa-store mr-2"></i> Shop Settings
            </button>
        </nav>
    </div>

    {{-- Profile Tab --}}
    <div x-show="activeTab === 'profile'" class="space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>

    {{-- Shop Settings Tab --}}
    <div x-show="activeTab === 'shop'" class="space-y-6" style="display: none;">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Shop Information') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __("Manage your shop details visible to customers.") }}
                </p>
            </header>

            <form method="post" action="#" class="mt-6 space-y-6">
                <!-- Shop Name -->
                <div>
                    <x-input-label for="shop_name" :value="__('Shop Name')" />
                    <x-text-input id="shop_name" name="shop_name" type="text" class="mt-1 block w-full" :value="old('shop_name', $user->name . ' Shop')" required autofocus />
                    <p class="text-xs text-gray-500 mt-1">This name will be displayed on your products.</p>
                </div>

                <!-- Shop Description -->
                <div>
                    <x-input-label for="shop_description" :value="__('Description')" />
                    <textarea id="shop_description" name="shop_description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                </div>

                <!-- Support Phone -->
                <div>
                    <x-input-label for="support_phone" :value="__('Support Phone')" />
                    <x-text-input id="support_phone" name="support_phone" type="text" class="mt-1 block w-full" :value="old('support_phone', $user->phone_number)" />
                </div>

                <!-- Logo (Mock) -->
                <div>
                    <x-input-label for="shop_logo" :value="__('Shop Logo')" />
                    <div class="mt-2 flex items-center gap-x-3">
                        <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <i class="fas fa-image"></i>
                        </div>
                        <button type="button" class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Change</button>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button type="button" onclick="alert('This is a simulated form for demonstration.')">{{ __('Save Shop Settings') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
