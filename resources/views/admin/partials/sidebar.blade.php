@php
$commerceOpen = request()->routeIs('admin.orders.*', 'admin.products.*', 'admin.categories.*', 'admin.users.*');
$aiOpen = request()->routeIs('admin.ai.*');
$analyticsOpen = request()->routeIs('admin.analytics.*');
@endphp

<!-- SIDEBAR: h-screen = full viewport height, flex col to position header/nav/footer -->


<aside class="w-64 h-screen bg-admin-primary text-white flex flex-col shadow-2xl border-r border-slate-700/50">

    <!-- HEADER -->
    <div class="p-6 flex items-center gap-3 border-b border-slate-700/50 shrink-0">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2.5 rounded-xl shadow-lg">
            <i class="fas fa-bolt"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold bg-gradient-to-r from-blue-400 to-blue-300 bg-clip-text text-transparent">
                Electro Admin
            </h1>
            <p class="text-xs text-slate-400">AI-Powered Commerce</p>
        </div>
    </div>

    <!-- NAV -->
    <nav class="flex-1 px-3 py-6 space-y-4 overflow-y-auto">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl transition
           {{ request()->routeIs('admin.dashboard')
                ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30'
                : 'text-slate-400 hover:bg-slate-700/50 hover:text-white' }}">
            <i class="fas fa-chart-line"></i>
            <span class="text-sm font-medium">Dashboard</span>
        </a>

        {{-- ================= COMMERCE ================= --}}
        @can('manage-commerce')
        <div class="pt-4 border-t border-slate-700/50">
            <p class="px-4 text-xs font-bold text-slate-500 uppercase mb-2">Commerce</p>

            <details {{ $commerceOpen ? 'open' : '' }} class="group">
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer rounded-xl
                    text-slate-400 hover:text-white hover:bg-slate-700/50 transition">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="text-sm font-medium">Commerce</span>
                    </div>
                    <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                </summary>

                <div class="mt-2 ml-4 space-y-1">
                    <a href="{{ route('admin.products.index') }}"
                       class="menu-link {{ request()->routeIs('admin.products.*') }}">
                        <i class="fas fa-box"></i> Products
                    </a>

                    <a href="{{ route('admin.categories.index') }}"
                       class="menu-link {{ request()->routeIs('admin.categories.*') }}">
                        <i class="fas fa-tags"></i> Categories
                    </a>

                    <a href="{{ route('admin.orders.index') }}"
                       class="menu-link {{ request()->routeIs('admin.orders.*') }}">
                        <i class="fas fa-cart-shopping"></i> Orders
                    </a>

                    @role('admin')
                    <a href="{{ route('admin.users.index') }}"
                       class="menu-link {{ request()->routeIs('admin.users.*') }}">
                        <i class="fas fa-users"></i> Users
                    </a>
                    @endrole
                </div>
            </details>
        </div>
        @endcan

        {{-- ================= ELECTRO AI ================= --}}
        @role('admin')
        <div class="pt-4 border-t border-slate-700/50">
            <p class="px-4 text-xs font-bold text-purple-400 uppercase mb-2">Electro AI</p>

            <details {{ $aiOpen ? 'open' : '' }} class="group">
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer rounded-xl
                    text-slate-400 hover:text-white hover:bg-slate-700/50 transition">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-brain"></i>
                        <span class="text-sm font-medium">AI System</span>
                    </div>
                    <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                </summary>

                <div class="mt-2 ml-4 space-y-1">
                    <a href="{{ route('admin.risk-rules.index') }}"
                       class="menu-link {{ request()->routeIs('admin.risk-rules.*') }}">
                        Risk Rules
                    </a>

                    <a href="{{ route('admin.price-suggestions.index') }}"
                       class="menu-link {{ request()->routeIs('admin.price-suggestions.*') }}">
                        Price Suggestions
                    </a>

                    <a href="{{ route('admin.audit-logs.index') }}"
                       class="menu-link {{ request()->routeIs('admin.audit-logs.*') }}">
                        Audit Logs
                    </a>
                </div>
            </details>
        </div>
        @endrole

        {{-- ================= ANALYTICS ================= --}}
        <div class="pt-4 border-t border-slate-700/50">
            <p class="px-4 text-xs font-bold text-emerald-400 uppercase mb-2">Analytics</p>

            <details {{ $analyticsOpen ? 'open' : '' }} class="group">
                <summary class="flex items-center justify-between px-4 py-3 cursor-pointer rounded-xl
                    text-slate-400 hover:text-white hover:bg-slate-700/50 transition">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-chart-column"></i>
                        <span class="text-sm font-medium">Reports</span>
                    </div>
                    <i class="fas fa-chevron-down group-open:rotate-180 transition"></i>
                </summary>

                <div class="mt-2 ml-4 space-y-1">
                    <a href="{{ route('admin.reports.revenue') }}"
                       class="menu-link {{ request()->routeIs('admin.reports.revenue') }}">
                        Revenue
                    </a>

                    <a href="{{ route('admin.reports.top_products') }}"
                       class="menu-link {{ request()->routeIs('admin.reports.top_products') }}">
                        Top Products
                    </a>

                    <a href="{{ route('admin.reports.low_stock') }}"
                       class="menu-link {{ request()->routeIs('admin.reports.low_stock') }}">
                        Low Stock
                    </a>
                </div>
            </details>
        </div>

    </nav>

    <!-- FOOTER -->
    <div class="p-4 border-t border-slate-700/50 shrink-0">
        <div class="flex items-center gap-3">
            <img class="w-10 h-10 rounded-full border border-slate-600"
                 src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}">
            <div class="flex-1">
                <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400">Administrator</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-red-400 hover:text-red-300">
                    <i class="fas fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</aside>