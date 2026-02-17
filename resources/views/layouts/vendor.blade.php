@props(['pageTitle' => '', 'breadcrumbs' => []])

<x-base-layout>
    <div class="flex h-screen bg-gray-50 overflow-hidden">
        <x-shared.sidebar title="Vendor Portal">
            <div class="space-y-1">
                <x-shared.sidebar-link :href="route('vendor.dashboard')" :active="request()->routeIs('vendor.dashboard')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                    </x-slot:icon>
                    Dashboard
                </x-shared.sidebar-link>

                <x-shared.sidebar-link :href="route('vendor.products.index')" :active="request()->routeIs('vendor.products.*')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3.75h3.75M12 11.25H8.25m3.75-9.75h3.75m3.75 0v11.25c0 1.243-1.007 2.25-2.25 2.25h-3.75a2.25 2.25 0 01-2.25-2.25V1.5m-6 11.25h6m-6 0v-4.5h6" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25" />
                        </svg>
                    </x-slot:icon>
                    Products
                </x-shared.sidebar-link>

                <x-shared.sidebar-link :href="route('vendor.orders.index')" :active="request()->routeIs('vendor.orders.*')">
                    <x-slot:icon>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM12 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3z" />
                        </svg>
                    </x-slot:icon>
                    Orders
                </x-shared.sidebar-link>
            </div>
        </x-shared.sidebar>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Shared Header -->
            <x-shared.header :breadcrumbs="$breadcrumbs" :page-title="$pageTitle" />

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-base-layout>
