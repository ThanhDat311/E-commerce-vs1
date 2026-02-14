<x-base-layout>
    <x-store.navbar />

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8 text-gray-500 text-sm">
                <a href="{{ route('home') }}" class="hover:text-gray-900">Home</a>
                <span class="mx-2">&rsaquo;</span>
                <span class="text-gray-900 font-medium">Account</span>
            </nav>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    @include('profile.partials.sidebar')
                </div>

                <!-- Main Content -->
                <div class="md:col-span-3 space-y-8">
                    <!-- Personal Information -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 md:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <!-- Saved Addresses -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 md:p-8">
                        @include('profile.partials.address-list')
                    </div>

                    <!-- Payment Methods -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 md:p-8">
                         @include('profile.partials.payment-methods')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    @if (session('status') === 'profile-updated')
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             x-init="setTimeout(() => show = false, 3000)"
             class="fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3 z-50">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="font-medium">{{ __('Profile updated successfully!') }}</span>
        </div>
    @endif

    <x-store.footer />
</x-base-layout>
