@php
// Tự động xác định trạng thái mở của các nhóm menu dựa trên Route hiện tại
$productsOpen = request()->routeIs('vendor.products.*');
$ordersOpen = request()->routeIs('vendor.orders.*');
@endphp

<aside class="w-64 h-screen bg-slate-900 text-white flex flex-col shadow-2xl border-r border-blue-900/30">

    <div class="p-6 flex items-center gap-3 border-b border-slate-800 shrink-0">
        <div class="bg-gradient-to-br from-orange-500 to-red-600 p-2.5 rounded-xl shadow-lg shadow-orange-900/20">
            <i class="fas fa-store text-white"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold bg-gradient-to-r from-orange-400 to-red-300 bg-clip-text text-transparent">
                Vendor Portal
            </h1>
            <p class="text-xs text-orange-300/70 font-medium uppercase tracking-tighter">Shop Manager</p>
        </div>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-4 overflow-y-auto custom-scrollbar">

        <x-admin.sidebar-link
            href="{{ route('vendor.dashboard') }}"
            :active="request()->routeIs('vendor.dashboard')"
            icon="house"
        >
            Dashboard
        </x-admin.sidebar-link>

        <x-admin.sidebar-section title="Quản lý cửa hàng">
            <x-admin.sidebar-group title="Sản phẩm" icon="box" :open="$productsOpen">
                <x-admin.sidebar-link href="{{ route('vendor.products.index') }}" :active="request()->routeIs('vendor.products.index')" compact>
                    Danh sách sản phẩm
                </x-admin.sidebar-link>
                <x-admin.sidebar-link href="{{ route('vendor.products.create') }}" :active="request()->routeIs('vendor.products.create')" compact>
                    Thêm sản phẩm mới
                </x-admin.sidebar-link>
            </x-admin.sidebar-group>

            <x-admin.sidebar-group title="Đơn hàng" icon="shopping-cart" :open="$ordersOpen">
                <x-admin.sidebar-link href="{{ route('vendor.orders.index') }}" :active="request()->routeIs('vendor.orders.index')" compact>
                    Đơn hàng mới
                </x-admin.sidebar-link>
                 {{-- Route history doesn't exist explicitly in route list, maybe just index with params --}}
            </x-admin.sidebar-group>

            {{-- Assuming vendor.shop.index logic needs to be verified or created if not existing. 
                 User request mentioned: Hồ sơ Shop -> `route('vendor.shop.index')` (hoặc profile).
                 Based on route list, we have `profile.edit` (User profile). 
                 Wait, I don't see `vendor.shop.index` in the `route/web.php` file I read earlier.
                 I only see `vendor.products` and `vendor.orders`.
                 
                 I will link to Profile Edit for now or create a placeholder.
                 Actually, looking at route list again:
                 Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
                 
                 There is no `vendor.shop.index`. I will use `profile.edit` but label it "Hồ sơ Shop".
                 Or maybe just omit if it's confusing.
                 The user said: "Hồ sơ Shop -> `route('vendor.shop.index')` (hoặc profile)."
                 I'll use `profile.edit`.
            --}}
            <x-admin.sidebar-link
                href="{{ route('profile.edit') }}"
                :active="request()->routeIs('profile.edit')"
                icon="store"
            >
                Hồ sơ Shop
            </x-admin.sidebar-link>
        </x-admin.sidebar-section>

    </nav>

    <div class="p-4 border-t border-slate-800 shrink-0 bg-slate-900/50">
        <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-800/50 transition-colors group">
            <img class="w-10 h-10 rounded-full border-2 border-orange-500/50 shadow-sm"
                 src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=f97316&color=fff' }}"
                 alt="{{ Auth::user()->name }}">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold truncate text-gray-100">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-orange-400 font-black uppercase tracking-wider">Vendor</p>
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