<x-base-layout>
    <div class="min-h-screen flex flex-col font-sans antialiased text-gray-900 bg-gray-50">
        <!-- Store Navbar -->
        <x-store.navbar />

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Store Footer -->
        <x-store.footer />
    </div>
    
    @livewireScripts
</x-base-layout>
