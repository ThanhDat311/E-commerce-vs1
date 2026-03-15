<x-admin-layout :pageTitle="'Semantic Search'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Semantic Search' => route('admin.ai.semantic-search.index')]">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Semantic Search</h1>
        <p class="text-sm text-gray-500 mt-1">Manage search synonyms to improve AI-powered search accuracy.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Add Synonym Form --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">➕ Add Synonym Pair</h2>
                <p class="text-xs text-gray-400 mt-1">Define equivalent terms so searches for one also match the other.</p>
            </div>
            <form action="{{ route('admin.ai.semantic-search.synonyms.add') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="term" class="block text-sm font-medium text-gray-700 mb-1">Term</label>
                        <input type="text" id="term" name="term" placeholder="e.g. phone" required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('term') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="synonym" class="block text-sm font-medium text-gray-700 mb-1">Synonym</label>
                        <input type="text" id="synonym" name="synonym" placeholder="e.g. smartphone" required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @error('synonym') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Synonym
                </button>
            </form>
        </div>

        {{-- No-Results Queries --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">🔍 "No Results" Queries</h2>
                <p class="text-xs text-gray-400 mt-1">Searches that returned no products. Add synonyms to resolve them.</p>
            </div>
            <div class="p-5">
                @if(count($noResultsQueries))
                    <ul class="space-y-2">
                        @foreach($noResultsQueries as $query)
                            <li class="flex items-center gap-2 text-sm text-gray-700">
                                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span class="font-mono text-xs bg-gray-100 px-2 py-0.5 rounded">{{ $query }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm text-gray-400">No failed searches logged yet.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Synonym List --}}
    <div class="mt-6 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">📚 Configured Synonyms ({{ count($synonyms) }})</h2>
        </div>
        @if(count($synonyms))
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Term</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Synonym</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($synonyms as $index => $pair)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-5 py-3 text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-3">
                                    <span class="font-mono text-xs bg-blue-50 text-blue-700 border border-blue-100 px-2 py-0.5 rounded">{{ $pair['term'] }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="font-mono text-xs bg-purple-50 text-purple-700 border border-purple-100 px-2 py-0.5 rounded">{{ $pair['synonym'] }}</span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <form action="{{ route('admin.ai.semantic-search.synonyms.remove', $index) }}" method="POST"
                                          onsubmit="return confirm('Remove this synonym pair?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 border border-red-200 px-2.5 py-1 rounded-lg transition-colors font-semibold">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-5 py-10 text-center text-sm text-gray-400">
                No synonyms configured yet. Add your first synonym pair above.
            </div>
        @endif
    </div>

</x-admin-layout>
