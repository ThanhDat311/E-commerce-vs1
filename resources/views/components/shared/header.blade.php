@props(['breadcrumbs' => [], 'pageTitle' => ''])

<header class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-4 shadow-sm sm:px-6 lg:px-8">
    <div class="flex items-center gap-4">
        <!-- Mobile Sidebar Toggle -->
        <button type="button"
                @click="$dispatch('sidebar-toggle')"
                class="inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 md:hidden">
            <span class="sr-only">Open sidebar</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <!-- Hamburger for Desktop (collapse sidebar) -->
        <button type="button"
                @click="$dispatch('sidebar-toggle')"
                class="hidden md:inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <!-- Page Title / Breadcrumb -->
        @if(!empty($pageTitle))
            <h1 class="text-lg font-semibold text-gray-900">{{ $pageTitle }}</h1>
        @endif

        @if(!empty($breadcrumbs))
            <nav class="hidden sm:flex items-center text-sm text-gray-500" aria-label="Breadcrumb">
                @foreach($breadcrumbs as $label => $url)
                    @if(!$loop->first)
                        <svg class="mx-2 h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    @endif
                    @if($loop->last)
                        <span class="font-medium text-blue-600">{{ $label }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-gray-700">{{ $label }}</a>
                    @endif
                @endforeach
            </nav>
        @endif
    </div>

    <div class="flex items-center gap-3">
        <!-- Notification Bell -->
        <button type="button" class="relative rounded-full p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <span class="sr-only">View notifications</span>
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
            </svg>
            <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
        </button>
    </div>
</header>
