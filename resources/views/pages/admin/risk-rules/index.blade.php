<x-admin-layout :pageTitle="'Risk Rule Management'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Dashboard' => route('admin.ai.dashboard.index'), 'Risk Rules' => route('admin.ai.risk-rules.index')]">

    <div x-data="{
        search: '',
        levelFilter: 'all',
        rules: {{ Js::from($rules->map(fn($r) => ['key' => $r->rule_key, 'desc' => $r->description, 'level' => $r->risk_level, 'active' => $r->is_active, 'weight' => $r->weight])) }},
        simulating: false,
        showSimModal: false,
        simRuleData: { key: '', currentWeight: 0, newWeight: 0 },
        simResult: null,
        openSimulateModal(rule) {
            this.simRuleData = { key: rule.rule_key, currentWeight: rule.weight, newWeight: rule.weight };
            this.simResult = null;
            this.simulating = false;
            this.showSimModal = true;
        },
        async runSimulation() {
            this.simulating = true;
            this.simResult = null;
            try {
                const resp = await fetch('{{ route('admin.ai.risk-rules.simulate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        rule_key: this.simRuleData.key,
                        weight: this.simRuleData.newWeight
                    })
                });
                this.simResult = await resp.json();
            } catch (e) {
                console.error(e);
            } finally {
                this.simulating = false;
            }
        },
        get filtered() {
            return this.rules.filter(r => {
                const matchesSearch = !this.search ||
                    r.key.toLowerCase().includes(this.search.toLowerCase()) ||
                    r.desc.toLowerCase().includes(this.search.toLowerCase());
                const matchesLevel = this.levelFilter === 'all' || r.level === this.levelFilter;
                return matchesSearch && matchesLevel;
            });
        },
        isVisible(key, level) {
            return this.filtered.some(r => r.key === key);
        }
    }">

        {{-- Page Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Risk Rule Management</h1>
                <p class="text-sm text-gray-500 mt-1">Configure scoring weights for AI fraud detection rules.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Export --}}
                <a href="{{ route('admin.ai.risk-rules.export') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export JSON
                </a>

                {{-- Import --}}
                <form method="POST" action="{{ route('admin.ai.risk-rules.import') }}" enctype="multipart/form-data" class="inline-flex">
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
                <form method="POST" action="{{ route('admin.ai.risk-rules.reset') }}"
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
                <strong>How scoring works:</strong> Each active rule contributes its <em>weight</em> to the total risk score.
                A score ≥ 80 blocks the transaction; ≥ 50 flags it for review. Higher weight = greater impact on the final score.
            </div>
        </div>

        {{-- Search + Filter Bar --}}
        <div class="mb-4 flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" x-model="search" placeholder="Search by rule key or description…"
                       class="pl-9 block w-full rounded-lg border border-gray-200 bg-white text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 py-2.5">
            </div>

            {{-- Level Filter Tabs --}}
            <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-lg shadow-sm p-1 flex-shrink-0">
                @foreach(['all' => 'All', 'critical' => 'Critical', 'high' => 'High', 'medium' => 'Medium', 'low' => 'Low'] as $val => $label)
                    <button @click="levelFilter = '{{ $val }}'"
                            :class="levelFilter === '{{ $val }}' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-800'"
                            class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Result count --}}
        <p class="text-xs text-gray-400 mb-3" x-text="`Showing ${filtered.length} of {{ $rules->count() }} rules`"></p>

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
                            <tr x-show="isVisible('{{ $rule->rule_key }}', '{{ $rule->risk_level }}')"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                class="{{ $rule->is_active ? '' : 'opacity-50' }} hover:bg-gray-50/50 transition-colors">

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

                                {{-- Weight --}}
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
                                    <form method="POST" action="{{ route('admin.ai.risk-rules.toggle', $rule) }}">
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
                                        <button @click="openSimulateModal({{ Js::from($rule) }})" type="button"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                            Simulate
                                        </button>
                                        <a href="{{ route('admin.ai.risk-rules.edit', $rule) }}"
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

            {{-- No results from filter --}}
            <div x-show="filtered.length === 0 && {{ $rules->count() }} > 0"
                 class="px-6 py-10 text-center text-sm text-gray-400">
                No rules match your current search or filter.
            </div>
        </div>

        {{-- Simulate Modal --}}
        <div x-show="showSimModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showSimModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50" @click="showSimModal = false"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="showSimModal" x-transition.scale.origin.bottom class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-xl shadow-xl border sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-6 relative">
                    
                    <button @click="showSimModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <h3 class="text-lg font-bold leading-6 text-gray-900 mb-2">Rule Simulation</h3>
                    <p class="text-sm text-gray-500 mb-5">
                        Test the impact of changing <code class="font-mono bg-gray-100 text-gray-800 px-1 rounded" x-text="simRuleData.key"></code> 
                        against the last 100 actual orders.
                    </p>

                    <div class="mb-5 flex gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Weight</label>
                            <input type="number" readonly x-model="simRuleData.currentWeight" class="block w-full rounded-md border-gray-300 bg-gray-50 text-gray-500 shadow-sm sm:text-sm">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Weight to Simulate</label>
                            <input type="number" x-model.number="simRuleData.newWeight" min="0" max="100" class="block w-full rounded-md border-blue-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm sm:text-sm">
                        </div>
                    </div>

                    <div class="flex justify-start mb-6">
                        <button type="button" @click="runSimulation()" :disabled="simulating"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm disabled:opacity-50">
                            <span x-show="simulating">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                            </span>
                            <span x-show="!simulating">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <span x-text="simulating ? 'Analyzing past 100 orders...' : 'Run Backtest Simulation'"></span>
                        </button>
                    </div>

                    {{-- Results Area --}}
                    <div x-show="simResult" x-transition.opacity class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-bold text-gray-900 mb-3 border-b pb-2">Simulation Results</h4>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h5 class="text-xs font-semibold text-gray-500 uppercase">Original</h5>
                                <ul class="mt-2 text-sm space-y-1">
                                    <li>Blocked: <span class="font-medium text-red-600" x-text="simResult?.original_blocked"></span></li>
                                    <li>Flagged: <span class="font-medium text-amber-600" x-text="simResult?.original_flagged"></span></li>
                                </ul>
                            </div>
                            <div>
                                <h5 class="text-xs font-semibold text-gray-500 uppercase">If new rules applied</h5>
                                <ul class="mt-2 text-sm space-y-1">
                                    <li>Blocked: <span class="font-medium text-red-600" x-text="simResult?.simulated_blocked"></span> <span class="text-xs font-bold" :class="simResult?.diff_blocked > 0 ? 'text-red-500' : (simResult?.diff_blocked < 0 ? 'text-green-500' : 'text-gray-400')" x-text="simResult?.diff_blocked > 0 ? '(+' + simResult?.diff_blocked + ')' : (simResult?.diff_blocked < 0 ? '(' + simResult?.diff_blocked + ')' : '(0)')"></span></li>
                                    <li>Flagged: <span class="font-medium text-amber-600" x-text="simResult?.simulated_flagged"></span> <span class="text-xs font-bold" :class="simResult?.diff_flagged > 0 ? 'text-amber-500' : (simResult?.diff_flagged < 0 ? 'text-green-500' : 'text-gray-400')" x-text="simResult?.diff_flagged > 0 ? '(+' + simResult?.diff_flagged + ')' : (simResult?.diff_flagged < 0 ? '(' + simResult?.diff_flagged + ')' : '(0)')"></span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

</x-admin-layout>
