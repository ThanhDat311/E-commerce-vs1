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
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 md:hidden"
         @click="isSidebarOpen = false"></div>

    <!-- Sidebar -->
    <div :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
         class="fixed inset-y-0 z-40 flex w-64 flex-col bg-gray-800 text-white transition-transform duration-300 ease-in-out md:static md:translate-x-0">
        
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center bg-gray-900 px-4 shadow-sm">
            <h1 class="text-xl font-bold tracking-wider text-white">ZENTRO</h1>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto px-2 py-4 space-y-1">
            {{ $slot }}
        </nav>

        <!-- Use User Profile in Sidebar Footer as well -->
        <div class="border-t border-gray-700 p-4">
            <div class="flex items-center">
                <div class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center text-sm font-medium overflow-hidden">
                   <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&color=7F9CF5&background=EBF4FF" alt="">
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-white">Sign out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
