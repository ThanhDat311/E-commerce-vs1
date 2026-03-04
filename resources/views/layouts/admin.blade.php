<x-base-layout>
    <div class="h-screen bg-gray-50 flex overflow-hidden" x-data="{ isSidebarOpen: false }">
        <!-- Sidebar -->
        <x-shared.sidebar>
                {{-- Dashboard --}}
                <x-shared.sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                    </x-slot:icon>
                    Dashboard
                </x-shared.sidebar-link>

                {{-- Users (with sub-menu) --}}
                <x-shared.sidebar-link :hasSubmenu="true" :active="request()->routeIs('admin.users.*') || request()->routeIs('admin.audit-logs.*')" :submenuOpen="request()->routeIs('admin.users.*') || request()->routeIs('admin.audit-logs.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </x-slot:icon>
                    <x-slot:label>Users</x-slot:label>
                    <x-slot:submenu>
                        <x-shared.sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.index') || request()->routeIs('admin.users.show') || request()->routeIs('admin.users.edit')">
                            All Users
                        </x-shared.sidebar-link>
                        <x-shared.sidebar-link :href="route('admin.users.create')" :active="request()->routeIs('admin.users.create')">
                            Add New User
                        </x-shared.sidebar-link>
                        <x-shared.sidebar-link :href="route('admin.audit-logs.index')" :active="request()->routeIs('admin.audit-logs.*')">
                            Activity Logs
                        </x-shared.sidebar-link>
                    </x-slot:submenu>
                </x-shared.sidebar-link>

                {{-- Vendors --}}
                <x-shared.sidebar-link :href="route('admin.vendors.index')" :active="request()->routeIs('admin.vendors.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.15c0 .415.336.75.75.75z" />
                        </svg>
                    </x-slot:icon>
                    Vendors
                </x-shared.sidebar-link>

                {{-- Products (with sub-menu) --}}
                <x-shared.sidebar-link :hasSubmenu="true" :active="request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*')" :submenuOpen="request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </x-slot:icon>
                    <x-slot:label>Products</x-slot:label>
                    <x-slot:submenu>
                        <x-shared.sidebar-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                            All Products
                        </x-shared.sidebar-link>
                        <x-shared.sidebar-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                            Categories
                        </x-shared.sidebar-link>
                    </x-slot:submenu>
                </x-shared.sidebar-link>

                {{-- Deals --}}
                <x-shared.sidebar-link :href="route('admin.deals.index')" :active="request()->routeIs('admin.deals.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                    </x-slot:icon>
                    Deals
                </x-shared.sidebar-link>

                {{-- Orders (with sub-menu) --}}
                <x-shared.sidebar-link :hasSubmenu="true" :active="request()->routeIs('admin.orders.*') || request()->routeIs('admin.disputes.*')" :submenuOpen="request()->routeIs('admin.orders.*') || request()->routeIs('admin.disputes.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </x-slot:icon>
                    <x-slot:label>Orders</x-slot:label>
                    <x-slot:submenu>
                        <x-shared.sidebar-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.index')">
                            All Orders
                        </x-shared.sidebar-link>
                        <x-shared.sidebar-link :href="route('admin.disputes.index')" :active="request()->routeIs('admin.disputes.*')">
                            Disputes
                        </x-shared.sidebar-link>
                    </x-slot:submenu>
                </x-shared.sidebar-link>

                {{-- Finance --}}
                <x-shared.sidebar-link :href="route('admin.finance.index')" :active="request()->routeIs('admin.finance.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                    </x-slot:icon>
                    Finance
                </x-shared.sidebar-link>

                {{-- Reports (with sub-menu) --}}
                <x-shared.sidebar-link :hasSubmenu="true" :active="request()->routeIs('admin.reports.*') || request()->routeIs('admin.analytics.*') || request()->routeIs('admin.low-stock-alerts.*')" :submenuOpen="request()->routeIs('admin.reports.*') || request()->routeIs('admin.analytics.*') || request()->routeIs('admin.low-stock-alerts.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </x-slot:icon>
                    <x-slot:label>Reports</x-slot:label>
                    <x-slot:submenu>
                        <x-shared.sidebar-link :href="route('admin.reports.index')" :active="request()->routeIs('admin.reports.*')">
                            Sales Report
                        </x-shared.sidebar-link>
                        <x-shared.sidebar-link :href="route('admin.analytics.index')" :active="request()->routeIs('admin.analytics.*')">
                            Revenue Analytics
                        </x-shared.sidebar-link>
                        <x-shared.sidebar-link :href="route('admin.low-stock-alerts.index')" :active="request()->routeIs('admin.low-stock-alerts.*')">
                            Low Stock Alerts
                        </x-shared.sidebar-link>
                    </x-slot:submenu>
                </x-shared.sidebar-link>

                {{-- AI Risk Dashboard --}}
                <x-shared.sidebar-link :href="route('admin.ai-dashboard.index')" :active="request()->routeIs('admin.ai-dashboard.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </x-slot:icon>
                    AI Dashboard
                </x-shared.sidebar-link>

                {{-- AI Price Suggestions --}}
                <x-shared.sidebar-link :href="route('admin.price-suggestions.index')" :active="request()->routeIs('admin.price-suggestions.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </x-slot:icon>
                    Price Suggestions
                </x-shared.sidebar-link>

                {{-- Risk Rules --}}
                <x-shared.sidebar-link :href="route('admin.risk-rules.index')" :active="request()->routeIs('admin.risk-rules.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </x-slot:icon>
                    Risk Rules
                </x-shared.sidebar-link>

                {{-- Separator --}}
                <div class="my-3 border-t border-slate-700/50"></div>

                {{-- Support --}}
                <x-shared.sidebar-link :href="route('admin.support.index')" :active="request()->routeIs('admin.support.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.712 4.33a9.027 9.027 0 011.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 00-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 010 9.424m-4.138-5.976a3.736 3.736 0 00-.88-1.388 3.737 3.737 0 00-1.388-.88m2.268 2.268a3.765 3.765 0 010 2.528m-2.268-4.796a3.765 3.765 0 00-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 01-1.388.88m2.268-2.268l4.138 3.448m0 0a9.027 9.027 0 01-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0l-3.448-4.138m3.448 4.138a9.014 9.014 0 01-9.424 0m5.976-4.138a3.765 3.765 0 01-2.528 0m0 0a3.736 3.736 0 01-1.388-.88 3.737 3.737 0 01-.88-1.388m0 0a3.765 3.765 0 010-2.528m2.268 4.796l-4.138 3.448m0 0a9.027 9.027 0 01-1.652-1.306 9.027 9.027 0 01-1.306-1.652m0 0l4.138-3.448M4.33 16.712a9.014 9.014 0 010-9.424m4.138 5.976a3.765 3.765 0 010-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 011.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.027 9.027 0 00-1.652 1.306 9.027 9.027 0 00-1.306 1.652" />
                        </svg>
                    </x-slot:icon>
                    Support
                </x-shared.sidebar-link>

                {{-- System Settings --}}
                <x-shared.sidebar-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">
                    <x-slot:icon>
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </x-slot:icon>
                    System Settings
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
