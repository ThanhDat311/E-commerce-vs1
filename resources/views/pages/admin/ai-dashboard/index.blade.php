<x-admin-layout :pageTitle="'AI Risk Dashboard'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Dashboard' => route('admin.ai.dashboard.index')]">


    <style>
        .risk-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .risk-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0,0,0,0.1), 0 4px 10px -5px rgba(0,0,0,0.04);
        }
        .chart-container {
            position: relative;
        }
        .table-row-anim {
            animation: fadeSlideIn 0.3s ease both;
        }
        @keyframes fadeSlideIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .risk-badge-blocked {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
        }
        .risk-badge-flagged {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
        }
        .pulse-dot {
            animation: pulseDot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>

    {{-- ══ Page Header ══════════════════════════════════════════════════════════ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </span>
                AI Risk Dashboard
            </h1>
            <p class="text-sm text-gray-500 mt-1">Real-time feed from the Electro AI Engine.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{-- Period Filter --}}
            <div class="flex items-center gap-1 bg-white border border-gray-200 rounded-lg shadow-sm p-1">
                @foreach (['1' => 'Today', '7' => '7D', '30' => '30D', '90' => '90D'] as $days => $label)
                    <a href="{{ route('admin.ai.dashboard.index', ['period' => $days]) }}"
                       class="px-3 py-1.5 text-xs font-semibold rounded-md transition-all
                              {{ $period == $days ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
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
                        <span class="pulse-dot relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                    @endif
                </span>
                AI Engine: {{ $aiServiceOnline ? 'Online' : 'Offline' }}
            </div>
        </div>
    </div>

    {{-- ══ Section: Transaction Fraud ═══════════════════════════════════════════ --}}
    <div class="flex items-center gap-3 mb-4">
        <h2 class="text-lg font-bold text-gray-800">Transaction Fraud Evaluations</h2>
        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ number_format($totalEvaluations) }} total</span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Evaluations --}}
        <div class="risk-card bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full opacity-60"></div>
            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Evaluations</p>
            <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($totalEvaluations) }}</h3>
            <p class="text-xs text-gray-400 mt-1">
                {{ $period == '1' ? 'Today' : ($period == '7' ? 'Last 7 Days' : ($period == '30' ? 'Last 30 Days' : 'Last 90 Days')) }}
            </p>
            <div class="absolute top-3 right-3">
                <div class="p-1.5 bg-blue-100 rounded-lg">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Blocked --}}
        <div class="risk-card bg-white rounded-xl shadow-sm border border-red-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-red-50 rounded-bl-full opacity-60"></div>
            <p class="text-[11px] font-semibold text-red-400 uppercase tracking-wider mb-1">Blocked</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-2xl font-extrabold text-red-700">{{ number_format($blockedTransactions) }}</h3>
                <span class="text-[10px] font-bold text-white bg-red-500 px-1.5 py-0.5 rounded-full">{{ $blockRate }}%</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Risk score ≥ 0.60</p>
            <div class="absolute top-3 right-3">
                <div class="p-1.5 bg-red-100 rounded-lg">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Flagged --}}
        <div class="risk-card bg-white rounded-xl shadow-sm border border-yellow-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-50 rounded-bl-full opacity-60"></div>
            <p class="text-[11px] font-semibold text-yellow-500 uppercase tracking-wider mb-1">Flagged</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-2xl font-extrabold text-yellow-700">{{ number_format($flaggedTransactions) }}</h3>
                <span class="text-[10px] font-bold text-white bg-yellow-500 px-1.5 py-0.5 rounded-full">{{ $flagRate }}%</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Score 0.35–0.59</p>
            <div class="absolute top-3 right-3">
                <div class="p-1.5 bg-yellow-100 rounded-lg">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Avg Risk Score --}}
        <div class="risk-card bg-white rounded-xl shadow-sm border border-purple-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-purple-50 rounded-bl-full opacity-60"></div>
            <p class="text-[11px] font-semibold text-purple-400 uppercase tracking-wider mb-1">Avg Risk Score</p>
            <h3 class="text-2xl font-extrabold text-purple-700">{{ $avgRiskScore }}</h3>
            <p class="text-xs text-gray-400 mt-1">Out of 1.0</p>
            <div class="absolute top-3 right-3">
                <div class="p-1.5 bg-purple-100 rounded-lg">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-2.5 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-1.5 rounded-full bg-gradient-to-r from-green-400 via-yellow-400 to-red-500 transition-all duration-700"
                     style="width: {{ min($avgRiskScore * 100, 100) }}%"></div>
            </div>
        </div>
    </div>

    {{-- ══ Section: Login Risk ═══════════════════════════════════════════════════ --}}
    <div class="flex items-center gap-3 mb-4 mt-8">
        <h2 class="text-lg font-bold text-gray-800">Login Risk Evaluations</h2>
        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">{{ number_format($loginTotal) }} total</span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Total Logins --}}
        <div class="risk-card bg-white rounded-xl shadow-sm border border-gray-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full opacity-60"></div>
            <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Logins</p>
            <h3 class="text-2xl font-extrabold text-gray-900">{{ number_format($loginTotal) }}</h3>
            <p class="text-xs text-gray-400 mt-1">
                {{ $period == '1' ? 'Today' : ($period == '7' ? 'Last 7 Days' : ($period == '30' ? 'Last 30 Days' : 'Last 90 Days')) }}
            </p>
            <div class="absolute top-3 right-3">
                <div class="p-1.5 bg-blue-100 rounded-lg">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Blocked Logins --}}
        <div class="risk-card bg-white rounded-xl shadow-sm border border-red-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-red-50 rounded-bl-full opacity-60"></div>
            <p class="text-[11px] font-semibold text-red-400 uppercase tracking-wider mb-1">Blocked</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-2xl font-extrabold text-red-700">{{ number_format($loginBlocked) }}</h3>
                <span class="text-[10px] font-bold text-white bg-red-500 px-1.5 py-0.5 rounded-full">{{ $loginBlockRate }}%</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Access blocked by policy</p>
            <div class="absolute top-3 right-3">
                <div class="p-1.5 bg-red-100 rounded-lg">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Flagged Logins --}}
        <div class="risk-card bg-white rounded-xl shadow-sm border border-yellow-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-50 rounded-bl-full opacity-60"></div>
            <p class="text-[11px] font-semibold text-yellow-500 uppercase tracking-wider mb-1">Flagged</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-2xl font-extrabold text-yellow-700">{{ number_format($loginFlagged) }}</h3>
                <span class="text-[10px] font-bold text-white bg-yellow-500 px-1.5 py-0.5 rounded-full">{{ $loginFlagRate }}%</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">OTP / Biometric</p>
            <div class="absolute top-3 right-3">
                <div class="p-1.5 bg-yellow-100 rounded-lg">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Avg Login Risk Score --}}
        <div class="risk-card bg-white rounded-xl shadow-sm border border-purple-100 p-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-purple-50 rounded-bl-full opacity-60"></div>
            <p class="text-[11px] font-semibold text-purple-400 uppercase tracking-wider mb-1">Avg Score</p>
            <h3 class="text-2xl font-extrabold text-purple-700">{{ $loginAvgScore }}</h3>
            <p class="text-xs text-gray-400 mt-1">Out of 1.0</p>
            <div class="absolute top-3 right-3">
                <div class="p-1.5 bg-purple-100 rounded-lg">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-2.5 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-1.5 rounded-full bg-gradient-to-r from-green-400 via-yellow-400 to-red-500 transition-all duration-700"
                     style="width: {{ min($loginAvgScore * 100, 100) }}%"></div>
            </div>
        </div>
    </div>

    {{-- ══ Charts Row 1: Risk Trend + Donut ════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

        {{-- Risk Score Trend (Line Chart) — 2-col wide --}}
        <div class="md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">📈 Risk Score Trend</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Daily average risk score</p>
                </div>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-0.5 bg-blue-500 rounded"></span>Transaction</span>
                    <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-0.5 bg-purple-500 rounded"></span>Login</span>
                </div>
            </div>
            <div class="chart-container" style="height: 180px;">
                <canvas id="riskTrendChart"></canvas>
            </div>
        </div>

        {{-- Threat Distribution Donut --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-gray-800">🍩 Threat Distribution</h3>
                <p class="text-xs text-gray-400 mt-0.5">Decision breakdown</p>
            </div>
            <div class="flex gap-2">
                <div class="flex-1 text-center border-r border-gray-50 pr-2">
                    <p class="text-[11px] font-semibold text-blue-600 mb-1">Transaction</p>
                    <div class="chart-container mx-auto" style="height: 90px; width: 90px;">
                        <canvas id="txDonutChart"></canvas>
                    </div>
                </div>
                <div class="flex-1 text-center pl-2">
                    <p class="text-[11px] font-semibold text-purple-600 mb-1">Login</p>
                    <div class="chart-container mx-auto" style="height: 90px; width: 90px;">
                        <canvas id="loginDonutChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap items-center justify-center gap-3 text-xs text-gray-500 bg-gray-50 rounded-lg p-2">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-sm bg-emerald-400 inline-block"></span>Safe</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-sm bg-amber-400 inline-block"></span>Flagged</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-sm bg-red-500 inline-block"></span>Blocked</span>
            </div>
        </div>
    </div>

    {{-- ══ Charts Row 2: Score Distribution + Hourly ═══════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

        {{-- Risk Score Distribution Bar Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">📊 Risk Score Distribution</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Events grouped by risk score range</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-blue-400 inline-block"></span>Tx</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-purple-400 inline-block"></span>Login</span>
                </div>
            </div>
            <div class="chart-container" style="height: 170px;">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>

        {{-- Hourly Heatmap Bar Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">🕒 Hourly Activity (Today)</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Events per hour for today</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-sky-400 inline-block"></span>Tx</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-violet-400 inline-block"></span>Login</span>
                </div>
            </div>
            <div class="chart-container" style="height: 170px;">
                <canvas id="hourlyChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ══ Threat Level Overview Bar ════════════════════════════════════════════ --}}
    @if($totalEvaluations > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex items-center justify-between mb-2.5">
            <h4 class="text-sm font-semibold text-gray-700">Overall Threat Distribution</h4>
            <span class="text-xs text-gray-400">
                {{ $period == '1' ? 'Today' : ($period == '7' ? 'Last 7 Days' : ($period == '30' ? 'Last 30 Days' : 'Last 90 Days')) }}
                ({{ number_format($totalEvaluations) }} evaluations)
            </span>
        </div>
        @php
            $safeCount = $totalEvaluations - $blockedTransactions - $flaggedTransactions;
            $safeRate = $totalEvaluations > 0 ? round(($safeCount / $totalEvaluations) * 100, 1) : 0;
        @endphp
        <div class="flex h-2 rounded-full overflow-hidden gap-px">
            <div class="h-2 bg-emerald-400 transition-all duration-700" style="width: {{ $safeRate }}%" title="Safe: {{ $safeRate }}%"></div>
            <div class="h-2 bg-amber-400 transition-all duration-700" style="width: {{ $flagRate }}%" title="Flagged: {{ $flagRate }}%"></div>
            <div class="h-2 bg-red-500 transition-all duration-700" style="width: {{ $blockRate }}%" title="Blocked: {{ $blockRate }}%"></div>
        </div>
        <div class="flex items-center gap-5 mt-2.5 text-[11px] text-gray-500">
            <span class="flex items-center gap-1.5 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                Safe ({{ $safeRate }}%)
            </span>
            <span class="flex items-center gap-1.5 font-medium">
                <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                Flagged ({{ $flagRate }}%)
            </span>
            <span class="flex items-center gap-1.5 font-medium">
                <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
                Blocked ({{ $blockRate }}%)
            </span>
        </div>
    </div>
    @endif

    {{-- ══ High-Risk Evaluations Table ══════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Table Header --}}
        <div class="px-6 py-4 bg-gradient-to-r from-slate-800 to-slate-700 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-white">High-Risk Evaluations</h3>
                <p class="text-xs text-slate-400 mt-0.5">Showing last {{ count($highRiskEvaluations) }} events with risk score ≥ 0.35</p>
            </div>
            <a href="{{ route('admin.ai.risk-rules.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-300 hover:text-indigo-100 transition-colors">
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
                    @forelse ($highRiskEvaluations as $index => $log)
                        <tr class="table-row-anim hover:bg-indigo-50/30 transition-colors" style="animation-delay: {{ $index * 30 }}ms">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-700">{{ $log['date']->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $log['date']->format('H:i:s') }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($log['type'] === 'transaction')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        Transaction
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Login
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if($log['target_url'])
                                    <a href="{{ $log['target_url'] }}" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold">
                                        {{ $log['target_label'] }}
                                    </a>
                                @else
                                    <span class="text-gray-400 italic text-xs">{{ $log['target_label'] }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-gray-500 font-mono text-xs">
                                {{ $log['ip_address'] ?? '—' }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($log['risk_score'] >= 0.60)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                        {{ $log['risk_score'] }} BLOCKED
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
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
                            <td colspan="7" class="px-6 py-16 text-center">
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

    {{-- ══ Chart.js Initialization ══════════════════════════════════════════════ --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ─── Shared helpers ────────────────────────────────────────────────────
        const gridColor = 'rgba(0,0,0,0.05)';
        const tickColor = '#9ca3af';
        const baseFont = { family: 'Inter, system-ui, sans-serif', size: 11 };

        const defaultScales = {
            x: {
                grid: { color: gridColor },
                ticks: { color: tickColor, font: baseFont }
            },
            y: {
                grid: { color: gridColor },
                ticks: { color: tickColor, font: baseFont },
                beginAtZero: true
            }
        };

        const defaultPlugins = {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(15,23,42,0.85)',
                titleFont: { ...baseFont, weight: '600' },
                bodyFont: baseFont,
                padding: 10,
                cornerRadius: 8,
                caretSize: 5,
            }
        };

        // ─── 1. Risk Score Trend Line Chart ────────────────────────────────────
        const trendData = @json($riskTrendData);

        new Chart(document.getElementById('riskTrendChart'), {
            type: 'line',
            data: {
                labels: trendData.labels,
                datasets: [
                    {
                        label: 'Transaction',
                        data: trendData.transaction,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.08)',
                        borderWidth: 2,
                        pointRadius: trendData.labels.length > 30 ? 0 : 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#fff',
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Login',
                        data: trendData.login,
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139,92,246,0.08)',
                        borderWidth: 2,
                        pointRadius: trendData.labels.length > 30 ? 0 : 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: '#fff',
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    ...defaultPlugins,
                    tooltip: {
                        ...defaultPlugins.tooltip,
                        callbacks: {
                            label: (ctx) => ` ${ctx.dataset.label}: ${ctx.parsed.y.toFixed(3)}`
                        }
                    }
                },
                scales: {
                    ...defaultScales,
                    y: {
                        ...defaultScales.y,
                        max: 1.0,
                        ticks: {
                            ...defaultScales.y.ticks,
                            callback: (v) => v.toFixed(1)
                        }
                    }
                }
            }
        });

        // ─── 2. Donut Charts ───────────────────────────────────────────────────
        const donutData = @json($donutData);
        const donutColors = ['#10b981', '#f59e0b', '#ef4444'];
        const donutHover  = ['#059669', '#d97706', '#dc2626'];

        const donutOptions = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...defaultPlugins.tooltip,
                    callbacks: {
                        label: (ctx) => {
                            const labels = ['Safe', 'Flagged', 'Blocked'];
                            return ` ${labels[ctx.dataIndex]}: ${ctx.parsed}`;
                        }
                    }
                }
            }
        };

        new Chart(document.getElementById('txDonutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Safe', 'Flagged', 'Blocked'],
                datasets: [{ data: donutData.transaction, backgroundColor: donutColors, hoverBackgroundColor: donutHover, borderWidth: 2, borderColor: '#fff' }]
            },
            options: donutOptions
        });

        new Chart(document.getElementById('loginDonutChart'), {
            type: 'doughnut',
            data: {
                labels: ['Safe', 'Flagged', 'Blocked'],
                datasets: [{ data: donutData.login, backgroundColor: donutColors, hoverBackgroundColor: donutHover, borderWidth: 2, borderColor: '#fff' }]
            },
            options: donutOptions
        });

        // ─── 3. Risk Score Distribution ────────────────────────────────────────
        const distData = @json($distributionData);

        new Chart(document.getElementById('distributionChart'), {
            type: 'bar',
            data: {
                labels: distData.labels,
                datasets: [
                    {
                        label: 'Transaction',
                        data: distData.transaction,
                        backgroundColor: 'rgba(59,130,246,0.75)',
                        hoverBackgroundColor: 'rgba(59,130,246,0.95)',
                        borderRadius: 4,
                        borderSkipped: false,
                        maxBarThickness: 16,
                    },
                    {
                        label: 'Login',
                        data: distData.login,
                        backgroundColor: 'rgba(139,92,246,0.75)',
                        hoverBackgroundColor: 'rgba(139,92,246,0.95)',
                        borderRadius: 4,
                        borderSkipped: false,
                        maxBarThickness: 16,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: defaultPlugins,
                scales: {
                    ...defaultScales,
                    x: { ...defaultScales.x, stacked: false },
                    y: { ...defaultScales.y, ticks: { ...defaultScales.y.ticks, stepSize: 1 } }
                }
            }
        });

        // ─── 4. Hourly Heatmap ─────────────────────────────────────────────────
        const hourly = @json($hourlyData);

        // Highlight hours with high activity
        const maxHourly = Math.max(...hourly.transaction, ...hourly.login, 1);

        new Chart(document.getElementById('hourlyChart'), {
            type: 'bar',
            data: {
                labels: hourly.labels,
                datasets: [
                    {
                        label: 'Transaction',
                        data: hourly.transaction,
                        backgroundColor: hourly.transaction.map(v =>
                            v >= maxHourly * 0.7
                                ? 'rgba(239,68,68,0.85)'
                                : v >= maxHourly * 0.4
                                    ? 'rgba(245,158,11,0.75)'
                                    : 'rgba(14,165,233,0.65)'
                        ),
                        borderRadius: 3,
                        borderSkipped: false,
                        maxBarThickness: 12,
                    },
                    {
                        label: 'Login',
                        data: hourly.login,
                        backgroundColor: hourly.login.map(v =>
                            v >= maxHourly * 0.7
                                ? 'rgba(220,38,38,0.75)'
                                : v >= maxHourly * 0.4
                                    ? 'rgba(217,119,6,0.65)'
                                    : 'rgba(139,92,246,0.55)'
                        ),
                        borderRadius: 3,
                        borderSkipped: false,
                        maxBarThickness: 12,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: defaultPlugins,
                scales: {
                    x: {
                        ...defaultScales.x,
                        ticks: {
                            ...defaultScales.x.ticks,
                            maxRotation: 0,
                            callback: (_, i) => i % 3 === 0 ? hourly.labels[i] : ''
                        }
                    },
                    y: { ...defaultScales.y, ticks: { ...defaultScales.y.ticks, stepSize: 1 } }
                }
            }
        });

    });
    </script>
    @endpush

</x-admin-layout>
