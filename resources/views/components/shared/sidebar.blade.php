@props(['title' => 'Zentro Admin'])

<div x-data="{ isSidebarOpen: false }"
     @sidebar-toggle.window="isSidebarOpen = !isSidebarOpen"
     class="flex h-screen overflow-hidden">

    <!-- Mobile sidebar backdrop -->
    <div x-show="isSidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm md:hidden"
         @click="isSidebarOpen = false"></div>

    <!-- Sidebar -->
    <div :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         class="fixed inset-y-0 z-40 flex w-64 flex-col bg-slate-900 text-white transition-transform duration-300 ease-in-out md:static md:translate-x-0">

        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center gap-3 px-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-sm font-bold text-white shadow-sm shadow-blue-500/30">
                Z
            </div>
            <h1 class="text-base font-bold tracking-wide text-white">{{ $title }}</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            {{ $slot }}
        </nav>

        <!-- User Profile Footer -->
        <div class="border-t border-slate-700/50 p-4">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-full bg-slate-600 flex items-center justify-center text-sm font-medium overflow-hidden ring-2 ring-slate-700">
                   <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&color=7F9CF5&background=EBF4FF" alt="">
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ ucfirst(Auth::user()->assignedRole->name ?? 'Super Admin') }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="event.preventDefault(); this.closest('form').submit();" class="p-2 -mr-2 text-slate-400 hover:text-white hover:bg-slate-700/50 rounded-lg transition-colors" title="Log Out">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
