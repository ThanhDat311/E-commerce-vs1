@php
// Tự động xác định trạng thái mở của các nhóm menu dựa trên Route hiện tại
$commerceOpen = request()->routeIs('admin.orders.*', 'admin.products.*', 'admin.categories.*', 'admin.users.*');
$aiOpen = request()->routeIs('admin.ai.*', 'admin.risk-rules.*', 'admin.price-suggestions.*');
$analyticsOpen = request()->routeIs('admin.reports.*', 'admin.audit-logs.*');
@endphp

<aside class="w-64 h-screen bg-slate-900 text-white flex flex-col shadow-2xl border-r border-blue-900/30">

    <div class="p-6 flex items-center gap-3 border-b border-slate-800 shrink-0">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-2.5 rounded-xl shadow-lg shadow-blue-900/20">
            <i class="fas fa-bolt text-white"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold bg-gradient-to-r from-blue-400 to-indigo-300 bg-clip-text text-transparent">
                Electro Admin
            </h1>
            <p class="text-xs text-blue-300/70 font-medium uppercase tracking-tighter">AI-Powered System</p>
        </div>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-4 overflow-y-auto custom-scrollbar">

        <x-admin.sidebar-link
            href="{{ route('admin.dashboard') }}"
            :active="request()->routeIs('admin.dashboard')"
            icon="house"
        >
            Dashboard
        </x-admin.sidebar-link>

        <x-admin.sidebar-section title="Quản lý cửa hàng">
            <x-admin.sidebar-group title="E-Commerce" icon="cart-shopping" :open="$commerceOpen">
                <x-admin.sidebar-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')" compact>
                    Order Management
                </x-admin.sidebar-link>
                <x-admin.sidebar-link href="{{ route('admin.products.index') }}" :active="request()->routeIs('admin.products.*')" compact>
                    Product Management
                </x-admin.sidebar-link>
                <x-admin.sidebar-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')" compact>
                    Category Management
                </x-admin.sidebar-link>
                <x-admin.sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" compact>
                    User Management
                </x-admin.sidebar-link>
            </x-admin.sidebar-group>
        </x-admin.sidebar-section>

        <x-admin.sidebar-section title="Trí tuệ nhân tạo">
            <x-admin.sidebar-group title="AI & Risk" icon="brain" :open="$aiOpen">
                <x-admin.sidebar-link href="{{ route('admin.risk-rules.index') }}" :active="request()->routeIs('admin.risk-rules.*')" compact>
                    Risk Control
                </x-admin.sidebar-link>
                <x-admin.sidebar-link href="{{ route('admin.price-suggestions.index') }}" :active="request()->routeIs('admin.price-suggestions.*')" compact>
                    Price Suggestions
                </x-admin.sidebar-link>
            </x-admin.sidebar-group>
        </x-admin.sidebar-section>

        <x-admin.sidebar-section title="Báo cáo & Dữ liệu">
            <x-admin.sidebar-group title="Analytics" icon="chart-pie" :open="$analyticsOpen">
                <x-admin.sidebar-link href="{{ route('admin.reports.revenue') }}" :active="request()->routeIs('admin.reports.revenue')" compact>
                    Revenue Report
                </x-admin.sidebar-link>
                <x-admin.sidebar-link href="{{ route('admin.audit-logs.index') }}" :active="request()->routeIs('admin.audit-logs.*')" compact>
                    Audit Logs
                </x-admin.sidebar-link>
                <x-admin.sidebar-link href="{{ route('admin.reports.top_products') }}" :active="request()->routeIs('admin.reports.top_products')" compact>
                    Top Products
                </x-admin.sidebar-link>
            </x-admin.sidebar-group>
        </x-admin.sidebar-section>

    </nav>

    <div class="p-4 border-t border-slate-800 shrink-0 bg-slate-900/50">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-800/50 transition-colors group">
            <img class="w-10 h-10 rounded-full border-2 border-blue-500/50 shadow-sm"
                 src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=3b82f6&color=fff' }}"
                 alt="{{ Auth::user()->name }}">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold truncate text-gray-100">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-blue-400 font-black uppercase tracking-wider">Administrator</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-red-400 transition-colors" title="Đăng xuất">
                    <i class="fas fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<style>
    /* Tùy chỉnh thanh cuộn cho Sidebar tông màu tối */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #1e293b;
        border-radius: 10px;
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background: #334155;
    }
</style>