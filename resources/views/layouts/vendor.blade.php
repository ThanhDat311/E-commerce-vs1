<x-base-layout>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Vendor Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 min-h-screen">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Vendor Portal</h2>
            </div>
            <nav class="mt-4">
                <a href="#" class="block py-2.5 px-4 text-gray-700 hover:bg-gray-100">
                    Dashboard
                </a>
                <!-- Add more vendor links here -->
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-lg font-medium text-gray-900">
                        {{ $header ?? 'Vendor Dashboard' }}
                    </h1>
                </div>
            </header>

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-base-layout>
