<x-admin-layout :pageTitle="'Content Generation & SEO'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Content Generation' => route('admin.ai.content-generation.index')]">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Content Generation & SEO</h1>
        <p class="text-sm text-gray-500 mt-1">Find products with missing or weak descriptions and generate AI-powered content.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Total Products</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
            <p class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1.5">No Description</p>
            <h3 class="text-3xl font-bold text-red-700">{{ number_format($stats['missing_desc']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-5">
            <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider mb-1.5">Short Description</p>
            <h3 class="text-3xl font-bold text-amber-700">{{ number_format($stats['short_desc']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-5">
            <p class="text-xs font-semibold text-green-500 uppercase tracking-wider mb-1.5">Complete</p>
            <h3 class="text-3xl font-bold text-green-700">{{ number_format($stats['complete']) }}</h3>
        </div>
    </div>

    {{-- How It Works Banner --}}
    <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-start gap-3">
        <div class="p-2 bg-blue-100 rounded-lg flex-shrink-0">
            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="text-sm text-blue-800">
            <strong>How it works:</strong> Click <em>Generate Description</em> on any product. The AI microservice will craft a product description based on the product name, price, and category. Results are saved automatically.
        </div>
    </div>

    {{-- Product Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5 text-left">Product</th>
                        <th class="px-5 py-3.5 text-center">Price</th>
                        <th class="px-5 py-3.5 text-left">Current Description</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($productsWithoutDesc as $product)
                        @php
                            $descLen = strlen($product->description ?? '');
                            $statusColor = $descLen === 0 ? 'border-l-red-500' : 'border-l-amber-500';
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="px-5 py-4 border-l-4 {{ $statusColor }}">
                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">ID: {{ $product->id }}</div>
                            </td>
                            <td class="px-5 py-4 text-center font-semibold text-gray-700">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                @if($descLen === 0)
                                    <span class="text-xs text-red-500 font-medium bg-red-50 px-2 py-0.5 rounded">No description</span>
                                @else
                                    <p class="text-xs text-gray-500 line-clamp-2">{{ $product->description }}</p>
                                    <span class="text-xs text-amber-600 mt-1 inline-block">{{ $descLen }} chars (too short)</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.ai.content-generation.generate', $product) }}" method="POST" class="inline" onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').textContent='Generating…'">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            Generate Description
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-lg transition-colors">
                                        Edit Manually
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-green-100 rounded-full">
                                        <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">All products have complete descriptions!</p>
                                        <p class="text-xs text-gray-400 mt-1">Your catalog is SEO-ready.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($productsWithoutDesc->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $productsWithoutDesc->links() }}
            </div>
        @endif
    </div>

</x-admin-layout>
