@php
$commerceOpen = request()->routeIs('admin.orders.*', 'admin.products.*', 'admin.categories.*', 'admin.users.*');
$aiOpen = request()->routeIs('admin.ai.*');
$analyticsOpen = request()->routeIs('admin.analytics.*');
@endphp

<!-- SIDEBAR: h-screen = full viewport height, flex col to position header/nav/footer -->


<aside class="w-64 h-screen bg-gray-800 text-white flex flex-col shadow-2xl border-r border-gray-700/50">

    <!-- HEADER -->
    <div class="p-6 flex items-center gap-3 border-b border-gray-700/50 shrink-0">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-2.5 rounded-xl shadow-lg">
            <i class="fas fa-bolt"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold bg-gradient-to-r from-blue-400 to-blue-300 bg-clip-text text-transparent">
                Electro Admin
            </h1>
            <p class="text-xs text-gray-300">AI-Powered Commerce</p>
        </div>
    </div>

    <!-- NAV -->
    <nav class="flex-1 px-3 py-6 space-y-4 overflow-y-auto">

        <!-- Dashboard -->
        <x-admin.sidebar-link
            href="{{ route('admin.dashboard') }}"
            :active="request()->routeIs('admin.dashboard')"
            icon="chart-line"
        >
            Dashboard
        </x-admin.sidebar-link>

        {{-- ================= COMMERCE ================= --}}
        <x-admin.sidebar-section label="Commerce">
            <x-admin.sidebar-group title="Commerce" icon="shopping-bag" :open="$commerceOpen">
                <x-admin.sidebar-link
                    href="{{ route('admin.products.index') }}"
                    :active="request()->routeIs('admin.products.*')"
                    icon="box"
                    compact
                >
                    Products
                </x-admin.sidebar-link>

                <x-admin.sidebar-link
                    href="{{ route('admin.categories.index') }}"
                    :active="request()->routeIs('admin.categories.*')"
                    icon="tags"
                    compact
                >
                    Categories
                </x-admin.sidebar-link>

                <x-admin.sidebar-link
                    href="{{ route('admin.orders.index') }}"
                    :active="request()->routeIs('admin.orders.*')"
                    icon="cart-shopping"
                    compact
                >
                    Orders
                </x-admin.sidebar-link>

                    <x-admin.sidebar-link
                        href="{{ route('admin.users.index') }}"
                        :active="request()->routeIs('admin.users.*')"
                        icon="users"
                        compact
                    >
                        Users
                    </x-admin.sidebar-link>
            </x-admin.sidebar-group>
        </x-admin.sidebar-section>

        {{-- ================= ELECTRO AI ================= --}}
        <x-admin.sidebar-section label="Electro AI">
            <x-admin.sidebar-group title="AI System" icon="brain" :open="$aiOpen">
                <x-admin.sidebar-link
                    href="{{ route('admin.risk-rules.index') }}"
                    :active="request()->routeIs('admin.risk-rules.*')"
                    compact
                >
                    Risk Rules
                </x-admin.sidebar-link>

                <x-admin.sidebar-link
                    href="{{ route('admin.price-suggestions.index') }}"
                    :active="request()->routeIs('admin.price-suggestions.*')"
                    compact
                >
                    Price Suggestions
                </x-admin.sidebar-link>

                <x-admin.sidebar-link
                    href="{{ route('admin.audit-logs.index') }}"
                    :active="request()->routeIs('admin.audit-logs.*')"
                    compact
                >
                    Audit Logs
                </x-admin.sidebar-link>
            </x-admin.sidebar-group>
        </x-admin.sidebar-section>

        {{-- ================= ANALYTICS ================= --}}
        <x-admin.sidebar-section label="Analytics">
            <x-admin.sidebar-group title="Reports" icon="chart-column" :open="$analyticsOpen">
                <x-admin.sidebar-link
                    href="{{ route('admin.reports.revenue') }}"
                    :active="request()->routeIs('admin.reports.revenue')"
                    compact
                >
                    Revenue
                </x-admin.sidebar-link>

                <x-admin.sidebar-link
                    href="{{ route('admin.reports.top_products') }}"
                    :active="request()->routeIs('admin.reports.top_products')"
                    compact
                >
                    Top Products
                </x-admin.sidebar-link>

                <x-admin.sidebar-link
                    href="{{ route('admin.reports.low_stock') }}"
                    :active="request()->routeIs('admin.reports.low_stock')"
                    compact
                >
                    Low Stock
                </x-admin.sidebar-link>
            </x-admin.sidebar-group>
        </x-admin.sidebar-section>

    </nav>

    <!-- FOOTER -->
    <div class="p-4 border-t border-gray-700/50 shrink-0">
        <div class="flex items-center gap-3">
            <img class="w-10 h-10 rounded-full border border-gray-600"
                 src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}"
                 alt="{{ Auth::user()->name }}">
            <div class="flex-1">
                <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-300">Administrator</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-admin.button
                    variant="danger"
                    size="sm"
                    type="submit"
                    icon="right-from-bracket"
                >
                    Đăng xuất
                </x-admin.button>
            </form>
        </div>
    </div>
</aside>