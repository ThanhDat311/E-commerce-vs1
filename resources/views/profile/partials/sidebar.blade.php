<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <!-- User Summary -->
    <div class="p-6 text-center border-b border-gray-100">
        <div class="relative inline-block">
            <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md mx-auto"
                 src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}"
                 alt="{{ $user->name }}">
        </div>
        <h3 class="mt-4 text-lg font-bold text-gray-900">{{ $user->name }}</h3>
        <p class="text-sm text-gray-500">{{ $user->email }}</p>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-1 bg-gray-50/50">
        <a href="{{ route('profile.edit') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
           {{ request()->routeIs('profile.edit') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('profile.edit') ? 'text-orange-500' : 'text-gray-400' }}"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Account Info
        </a>

        <a href="{{ route('orders.index') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
           {{ request()->routeIs('orders.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('orders.*') ? 'text-orange-500' : 'text-gray-400' }}"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            Order History
        </a>

        <a href="{{ route('addresses.index') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
           {{ request()->routeIs('addresses.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('addresses.*') ? 'text-orange-500' : 'text-gray-400' }}"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            Saved Addresses
        </a>

        <a href="{{ route('payment-methods.index') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
           {{ request()->routeIs('payment-methods.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('payment-methods.*') ? 'text-orange-500' : 'text-gray-400' }}"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            Payment Methods
        </a>

        <a href="{{ route('notifications.settings') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
           {{ request()->routeIs('notifications.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
             <svg class="mr-3 h-5 w-5 {{ request()->routeIs('notifications.*') ? 'text-orange-500' : 'text-gray-400' }}"
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            Notifications
        </a>

        <a href="{{ route('security.index') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
           {{ request()->routeIs('security.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('security.*') ? 'text-orange-500' : 'text-gray-400' }}"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Account Security
        </a>

        <a href="{{ route('wishlist.index') }}"
           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200
           {{ request()->routeIs('wishlist.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="mr-3 h-5 w-5 {{ request()->routeIs('wishlist.*') ? 'text-orange-500' : 'text-gray-400' }}"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            Wishlist
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-gray-100 pt-4">
            @csrf
            <button type="submit"
                    class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-all duration-200">
                <svg class="mr-3 h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Log Out
            </button>
        </form>
    </nav>
</div>
