<aside class="w-64 bg-white border-r border-gray-200 h-screen overflow-y-auto flex-shrink-0">
    <div class="h-16 flex items-center justify-center border-b border-gray-200 px-4">
        <h2 class="text-xl font-bold text-gray-800">Vendor Portal</h2>
    </div>
    
    <nav class="mt-6 px-2 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('vendor.dashboard') }}" 
           class="{{ request()->routeIs('vendor.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
            <svg class="{{ request()->routeIs('vendor.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 h-5 w-5 flex-shrink-0 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <!-- Products -->
        <a href="{{ route('vendor.products.index') }}" 
           class="{{ request()->routeIs('vendor.products.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
            <svg class="{{ request()->routeIs('vendor.products.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 h-5 w-5 flex-shrink-0 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Products
        </a>

        <!-- Orders -->
        <a href="{{ route('vendor.orders.index') }}" 
           class="{{ request()->routeIs('vendor.orders.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors">
            <svg class="{{ request()->routeIs('vendor.orders.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }} mr-3 h-5 w-5 flex-shrink-0 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            Orders
        </a>
    </nav>
</aside>
