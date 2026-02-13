<x-admin-layout :pageTitle="'Vendor Management'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Vendor Management' => route('admin.vendors.index')]">

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Platform Vendors</h1>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="p-5">
            <form method="GET" action="{{ route('admin.vendors.index') }}" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="relative flex-1 w-full">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search vendors by shop name or ID..."
                           class="pl-10 pr-4 py-2.5 w-full rounded-lg border border-gray-200 text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="flex items-center gap-3">
                    <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 rounded-lg border border-gray-200 text-sm text-gray-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    <button type="submit" class="p-2.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-y border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3 font-semibold">Vendor ID</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Shop Name</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Owner Name</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Total Products</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Total Sales</th>
                        <th scope="col" class="px-5 py-3 font-semibold">Status</th>
                        <th scope="col" class="px-5 py-3 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($vendors as $vendor)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <span class="font-medium text-gray-500">#VEN-{{ str_pad($vendor->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $initials = collect(explode(' ', $vendor->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                                        $colors = ['bg-blue-100 text-blue-600', 'bg-emerald-100 text-emerald-600', 'bg-purple-100 text-purple-600', 'bg-amber-100 text-amber-600', 'bg-rose-100 text-rose-600', 'bg-cyan-100 text-cyan-600'];
                                        $color = $colors[$vendor->id % count($colors)];
                                    @endphp
                                    <div class="h-9 w-9 rounded-full {{ $color }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.vendors.show', $vendor) }}" class="font-semibold text-gray-900 hover:text-blue-600 transition-colors">{{ $vendor->name }}</a>
                                        <p class="text-xs text-gray-400">{{ $vendor->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-700">{{ $vendor->name }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5 text-gray-700">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    {{ $vendor->products_count }}
                                </div>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900">
                                ${{ number_format($vendor->total_sales ?? 0, 0) }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusVariant = $vendor->is_active ? 'success' : 'danger';
                                    $statusLabel = $vendor->is_active ? 'Approved' : 'Suspended';
                                @endphp
                                <x-ui.badge :variant="$statusVariant" :dot="true">{{ $statusLabel }}</x-ui.badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.vendors.show', $vendor) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="View Details">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.vendors.toggle-status', $vendor) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg transition-colors {{ $vendor->is_active ? 'text-gray-400 hover:text-red-600 hover:bg-red-50' : 'text-gray-400 hover:text-green-600 hover:bg-green-50' }}" title="{{ $vendor->is_active ? 'Suspend' : 'Activate' }}">
                                            @if($vendor->is_active)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No vendors found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vendors->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $vendors->firstItem() }}</span> to <span class="font-medium">{{ $vendors->lastItem() }}</span> of <span class="font-bold text-gray-700">{{ $vendors->total() }}</span> results
                </p>
                <div>{{ $vendors->links() }}</div>
            </div>
        @endif
    </div>
</x-admin-layout>
