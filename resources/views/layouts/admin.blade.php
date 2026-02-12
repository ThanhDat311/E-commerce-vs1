<x-base-layout>
    <div class="min-h-screen bg-gray-100 flex" x-data="{ isSidebarOpen: false }">
        <!-- Sidebar -->
        <x-shared.sidebar>
            @if(($sidebarType ?? 'admin') === 'staff')
                <x-shared.sidebar-link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')">
                    <x-slot:icon>
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </x-slot:icon>
                    Dashboard
                </x-shared.sidebar-link>
                
                <x-shared.sidebar-link :href="route('staff.orders.index')" :active="request()->routeIs('staff.orders.*')">
                    <x-slot:icon>
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </x-slot:icon>
                    Orders
                </x-shared.sidebar-link>
            @else
                <x-shared.sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <x-slot:icon>
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </x-slot:icon>
                    Dashboard
                </x-shared.sidebar-link>
                
                <x-shared.sidebar-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                    <x-slot:icon>
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </x-slot:icon>
                    Products
                </x-shared.sidebar-link>

                <x-shared.sidebar-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                    <x-slot:icon>
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </x-slot:icon>
                    Categories
                </x-shared.sidebar-link>

                 <x-shared.sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <x-slot:icon>
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </x-slot:icon>
                    Users
                </x-shared.sidebar-link>
                
                <x-shared.sidebar-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                    <x-slot:icon>
                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </x-slot:icon>
                    Orders
                </x-shared.sidebar-link>
            @endif
        </x-shared.sidebar>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <x-shared.header :breadcrumbs="$breadcrumbs ?? []" />

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-base-layout>
