<x-admin-layout :pageTitle="'Risk Rule Management'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Dashboard' => route('admin.ai-dashboard.index'), 'Risk Rules' => route('admin.risk-rules.index')]">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Risk Rule Management</h1>
            <p class="text-sm text-gray-500 mt-1">Configure scoring weights for AI fraud detection rules.</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Export --}}
            <a href="{{ route('admin.risk-rules.export') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export JSON
            </a>

            {{-- Import --}}
            <form method="POST" action="{{ route('admin.risk-rules.import') }}" enctype="multipart/form-data" class="inline-flex">
                @csrf
                <label class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Import JSON
                    <input type="file" name="file" accept=".json" class="hidden" onchange="this.closest('form').submit()">
                </label>
            </form>

            {{-- Reset --}}
            <form method="POST" action="{{ route('admin.risk-rules.reset') }}"
                  onsubmit="return confirm('Reset all rules to default values? This cannot be undone.')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Defaults
                </button>
            </form>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Rules</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_rules'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-green-100 shadow-sm p-4">
            <p class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">Active</p>
            <p class="text-2xl font-bold text-green-700">{{ $stats['active_rules'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Inactive</p>
            <p class="text-2xl font-bold text-gray-500">{{ $stats['inactive_rules'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-blue-100 shadow-sm p-4">
            <p class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">Avg. Weight</p>
            <p class="text-2xl font-bold text-blue-700">{{ number_format($stats['average_weight'] ?? 0, 1) }}</p>
        </div>
    </div>

    {{-- How It Works Banner --}}
    <div class="mb-6 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-3">
        <div class="p-2 bg-slate-200 rounded-lg flex-shrink-0 mt-0.5">
            <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="text-sm text-slate-700">
            <strong>How scoring works:</strong> Each active rule contributes its <em>weight</em> to the total risk score. A score ≥ 80 blocks the transaction; ≥ 50 flags it for review. Higher weight = greater impact on the final score.
        </div>
    </div>

    {{-- Rules Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Rule</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold">Description</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Risk Level</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Weight</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-center">Status</th>
                        <th scope="col" class="px-5 py-3.5 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rules as $rule)
                        @php
                            $levelColor = match($rule->risk_level) {
                                'critical' => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'bar' => 'bg-red-500'],
                                'high'     => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'bar' => 'bg-orange-500'],
                                'medium'   => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'bar' => 'bg-amber-500'],
                                'low'      => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'bar' => 'bg-green-500'],
                                default    => ['bg' => 'bg-gray-100',   'text' => 'text-gray-600',   'bar' => 'bg-gray-400'],
                            };
                        @endphp
                        <tr class="{{ $rule->is_active ? '' : 'opacity-50' }} hover:bg-gray-50/50 transition-colors">

                            {{-- Rule Key --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full {{ $rule->is_active ? 'bg-green-500' : 'bg-gray-300' }} flex-shrink-0"></div>
                                    <code class="font-mono text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                        {{ $rule->rule_key }}
                                    </code>
                                </div>
                            </td>

                            {{-- Description --}}
                            <td class="px-5 py-4 max-w-xs">
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $rule->description }}</p>
                            </td>

                            {{-- Risk Level --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $levelColor['bg'] }} {{ $levelColor['text'] }}">
                                    {{ $rule->getRiskLevelLabel() }}
                                </span>
                            </td>

                            {{-- Weight with Visual Bar --}}
                            <td class="px-5 py-4 text-center">
                                <div class="flex flex-col items-center gap-1.5 min-w-[80px]">
                                    <span class="text-lg font-bold text-gray-900">{{ $rule->weight }}</span>
                                    <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="{{ $levelColor['bar'] }} h-1.5 rounded-full"
                                             style="width: {{ min($rule->weight, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Status Toggle --}}
                            <td class="px-5 py-4 text-center">
                                <form method="POST" action="{{ route('admin.risk-rules.toggle', $rule) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border transition-colors cursor-pointer
                                                {{ $rule->is_active
                                                    ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100'
                                                    : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200' }}"
                                            title="{{ $rule->is_active ? 'Click to deactivate' : 'Click to activate' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $rule->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                        {{ $rule->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.risk-rules.edit', $rule) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                        </svg>
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-gray-100 rounded-full">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">No risk rules found.</p>
                                        <p class="text-xs text-gray-400 mt-1">Run the seeder to populate default rules.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-admin-layout>
