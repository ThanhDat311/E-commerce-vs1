<x-admin-layout :pageTitle="'AI Risk Dashboard'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Dashboard' => route('admin.ai.dashboard.index')]">

    {{-- Page Header + Engine Status --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">AI Risk Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">Real-time feed from the Electro AI Engine.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{-- Period Filter --}}
            <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-lg shadow-sm p-1">
                @foreach (['1' => 'Today', '7' => '7D', '30' => '30D', '90' => '90D'] as $days => $label)
                    <a href="{{ route('admin.ai.dashboard.index', ['period' => $days]) }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-md transition-colors
                              {{ $period == $days ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Engine Health Badge --}}
            <div class="flex items-center gap-2 px-3 py-2 rounded-lg border shadow-sm text-sm font-medium
                        {{ $aiServiceOnline ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                <span class="relative flex h-2.5 w-2.5">
                    @if($aiServiceOnline)
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"></span>
                    @else
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    @endif
                </span>
                AI Engine: {{ $aiServiceOnline ? 'Online' : 'Offline' }}
            </div>
        </div>
    </div>

    {{-- Dashboard Title: Transaction Fraud --}}
    <h2 class="text-xl font-bold text-gray-800 mb-4">Transaction Fraud Evaluations</h2>

    {{-- Metrics Grid (Transactions) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        {{-- Total Evaluations --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Total Evaluations</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalEvaluations) }}</h3>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $period == '1' ? 'Today' : ($period == '7' ? 'Last 7 Days' : ($period == '30' ? 'Last 30 Days' : 'Last 90 Days')) }}
                    </p>
                </div>
                <div class="p-2.5 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Blocked --}}
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1.5">Blocked</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-red-700">{{ number_format($blockedTransactions) }}</h3>
                        <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">{{ $blockRate }}%</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Risk score ≥ 0.60</p>
                </div>
                <div class="p-2.5 bg-red-50 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Flagged --}}
        <div class="bg-white rounded-xl shadow-sm border border-yellow-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-yellow-500 uppercase tracking-wider mb-1.5">Flagged</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-yellow-700">{{ number_format($flaggedTransactions) }}</h3>
                        <span class="text-xs font-bold text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-full">{{ $flagRate }}%</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Score 0.35–0.59</p>
                </div>
                <div class="p-2.5 bg-yellow-50 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Avg Risk Score --}}
        <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-purple-400 uppercase tracking-wider mb-1.5">Avg Risk Score</p>
                    <h3 class="text-3xl font-bold text-purple-700">{{ $avgRiskScore }}</h3>
                    <p class="text-xs text-gray-400 mt-1">Out of 100</p>
                </div>
                <div class="p-2.5 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            {{-- Mini progress bar --}}
            <div class="mt-3 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-1.5 rounded-full bg-gradient-to-r from-green-400 via-yellow-400 to-red-500"
                     style="width: {{ min($avgRiskScore * 100, 100) }}%"></div>
            </div>
        </div>
    </div>

    {{-- Dashboard Title: Login Risk --}}
    <h2 class="text-xl font-bold text-gray-800 mb-4 mt-8">Login Risk Evaluations</h2>

    {{-- Metrics Grid (Logins) --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        {{-- Total Logins --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Total Logins</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($loginTotal) }}</h3>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $period == '1' ? 'Today' : ($period == '7' ? 'Last 7 Days' : ($period == '30' ? 'Last 30 Days' : 'Last 90 Days')) }}
                    </p>
                </div>
                <div class="p-2.5 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Blocked Logins --}}
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1.5">Blocked</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-red-700">{{ number_format($loginBlocked) }}</h3>
                        <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">{{ $loginBlockRate }}%</span>
                    </div>
                </div>
                <div class="p-2.5 bg-red-50 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Flagged Logins --}}
        <div class="bg-white rounded-xl shadow-sm border border-yellow-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-yellow-500 uppercase tracking-wider mb-1.5">Flagged</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-3xl font-bold text-yellow-700">{{ number_format($loginFlagged) }}</h3>
                        <span class="text-xs font-bold text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-full">{{ $loginFlagRate }}%</span>
                    </div>
                </div>
                <div class="p-2.5 bg-yellow-50 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Avg Login Risk Score --}}
        <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold text-purple-400 uppercase tracking-wider mb-1.5">Avg Score</p>
                    <h3 class="text-3xl font-bold text-purple-700">{{ $loginAvgScore }}</h3>
                </div>
                <div class="p-2.5 bg-purple-50 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-1.5 rounded-full bg-gradient-to-r from-green-400 via-yellow-400 to-red-500"
                     style="width: {{ min($loginAvgScore * 100, 100) }}%"></div>
            </div>
        </div>
    </div>

    {{-- Threat Level Overview --}}
    @if($totalEvaluations > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-semibold text-gray-700">Overall Threat Distribution</h4>
            <span class="text-xs text-gray-400">
                {{ $period == '1' ? 'Today' : ($period == '7' ? 'Last 7 Days' : ($period == '30' ? 'Last 30 Days' : 'Last 90 Days')) }}
                ({{ number_format($totalEvaluations) }} evaluations)
            </span>
        </div>
        <div class="flex h-3 rounded-full overflow-hidden gap-px">
            @php
                $safeCount = $totalEvaluations - $blockedTransactions - $flaggedTransactions;
                $safeRate = $totalEvaluations > 0 ? round(($safeCount / $totalEvaluations) * 100, 1) : 0;
            @endphp
            <div class="h-3 bg-green-400 transition-all" style="width: {{ $safeRate }}%" title="Safe: {{ $safeRate }}%"></div>
            <div class="h-3 bg-yellow-400 transition-all" style="width: {{ $flagRate }}%" title="Flagged: {{ $flagRate }}%"></div>
            <div class="h-3 bg-red-500 transition-all" style="width: {{ $blockRate }}%" title="Blocked: {{ $blockRate }}%"></div>
        </div>
        <div class="flex items-center gap-5 mt-2 text-xs text-gray-500">
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span> Safe ({{ $safeRate }}%)</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span> Flagged ({{ $flagRate }}%)</span>
            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span> Blocked ({{ $blockRate }}%)</span>
        </div>
    </div>
    @endif

    {{-- Recent High-Risk Logs --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-semibold text-gray-900">High-Risk Evaluations</h3>
                <p class="text-xs text-gray-400 mt-0.5">Showing last {{ count($highRiskEvaluations) }} events with risk score ≥ 0.35</p>
            </div>
            <a href="{{ route('admin.ai.risk-rules.index') }}"
               class="text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
                Manage Rules
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/80">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order / Target</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Risk Score</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Triggered Rules</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-indigo-500 uppercase tracking-wider">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                AI Insight
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($highRiskEvaluations as $log)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-5 py-3.5 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $log['date']->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $log['date']->format('H:i:s') }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-sm text-gray-500">
                                @if($log['type'] === 'transaction')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        Transaction
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                        Login
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if($log['target_url'])
                                    <a href="{{ $log['target_url'] }}" class="text-blue-600 hover:underline">
                                        {{ $log['target_label'] }}
                                    </a>
                                @else
                                    <span class="text-gray-400 italic text-xs">{{ $log['target_label'] }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-sm text-gray-500 font-mono text-xs">
                                {{ $log['ip_address'] ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($log['risk_score'] >= 0.60)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        {{ $log['risk_score'] }} BLOCKED
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                        {{ $log['risk_score'] }} FLAGGED
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-sm text-gray-500 max-w-[220px]">
                                @if(is_array($log['reasons']) && count($log['reasons']))
                                    <div class="space-y-0.5">
                                        @foreach(array_slice($log['reasons'], 0, 2) as $reason)
                                            <div class="text-xs bg-gray-100 rounded px-1.5 py-0.5 truncate">{{ $reason }}</div>
                                        @endforeach
                                        @if(count($log['reasons']) > 2)
                                            <div class="text-xs text-indigo-500 font-medium">+{{ count($log['reasons']) - 2 }} more</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-sm max-w-[200px]">
                                @if($log['ai_insight'])
                                    <p class="text-xs text-indigo-700 font-medium leading-tight" title="{{ $log['ai_insight'] }}">
                                        {{ Str::limit($log['ai_insight'], 60) }}
                                    </p>
                                @else
                                    <span class="text-gray-300 italic text-xs">Waiting…</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-green-50 rounded-full">
                                        <svg class="w-10 h-10 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">All Clear!</p>
                                        <p class="text-xs text-gray-400 mt-1">No high-risk transactions in the selected period.</p>
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
