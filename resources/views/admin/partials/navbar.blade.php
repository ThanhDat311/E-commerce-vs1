<header class="h-16 border-b border-gray-200 bg-white flex items-center justify-between px-8 sticky top-0 z-10 backdrop-blur-md shadow-sm">
    <div class="flex items-center flex-1 max-w-lg">
        <div class="relative w-full">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input class="w-full bg-gray-100 border border-gray-200 rounded-lg py-2.5 pl-10 pr-4 text-sm placeholder-gray-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none"
                placeholder="Search orders, customers..." type="text" />
        </div>
    </div>

    <div class="flex items-center gap-6 ml-8">
        <button class="relative p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-full transition-all group">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white shadow-md"></span>
        </button>

        <button class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-gray-100 rounded-full transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </button>

        <div class="h-8 w-px bg-gray-200"></div>

        <p class="text-sm font-semibold text-gray-700 min-w-max">
            {{ \Carbon\Carbon::now()->format('l, M d') }}
        </p>
    </div>
</header>