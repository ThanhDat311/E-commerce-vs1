<x-base-layout>
    <div class="h-screen bg-gray-50 flex overflow-hidden" x-data="{ isSidebarOpen: false }">
        <!-- Sidebar -->
        <x-shared.sidebar>
            {{-- Dashboard --}}
            <x-shared.sidebar-link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')">
                <x-slot:icon>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                </x-slot:icon>
                Dashboard
            </x-shared.sidebar-link>

            {{-- Orders --}}
            <x-shared.sidebar-link :href="route('staff.orders.index')" :active="request()->routeIs('staff.orders.*')">
                <x-slot:icon>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </x-slot:icon>
                Orders
            </x-shared.sidebar-link>

            {{-- Support Queue --}}
            <x-shared.sidebar-link :href="route('staff.support.index')" :active="request()->routeIs('staff.support.*')">
                <x-slot:icon>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </x-slot:icon>
                Support Queue
            </x-shared.sidebar-link>

            {{-- Products (with sub-menu) --}}
            <x-shared.sidebar-link
                :hasSubmenu="true"
                :active="request()->routeIs('staff.products.*') || request()->routeIs('staff.categories.*')"
                :submenuOpen="request()->routeIs('staff.products.*') || request()->routeIs('staff.categories.*')"
            >
                <x-slot:icon>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </x-slot:icon>
                <x-slot:label>Products</x-slot:label>
                <x-slot:submenu>
                    <x-shared.sidebar-link :href="route('staff.products.index')" :active="request()->routeIs('staff.products.*')">
                        All Products
                    </x-shared.sidebar-link>
                    <x-shared.sidebar-link :href="route('staff.categories.index')" :active="request()->routeIs('staff.categories.*')">
                        Categories
                    </x-shared.sidebar-link>
                </x-slot:submenu>
            </x-shared.sidebar-link>

            {{-- Deals --}}
            <x-shared.sidebar-link :href="route('staff.deals.index')" :active="request()->routeIs('staff.deals.*')">
                <x-slot:icon>
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                    </svg>
                </x-slot:icon>
                Deals
            </x-shared.sidebar-link>
        </x-shared.sidebar>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <x-shared.header :breadcrumbs="$breadcrumbs ?? []" :page-title="$pageTitle ?? ''" />

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-base-layout>
