<x-admin-layout :pageTitle="'Electro AI - Risk Dashboard'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Dashboard' => route('admin.ai-dashboard.index')]">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Electro AI - Risk Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Live feed from Electro AI Engine</p>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Evaluations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Risk Evaluations</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($totalEvaluations) }}</h3>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Blocked Transactions -->
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-500 mb-1">Blocked (Score ≥ 80)</p>
                    <div class="flex items-baseline space-x-2">
                        <h3 class="text-3xl font-bold text-red-700">{{ number_format($blockedTransactions) }}</h3>
                        <span class="text-sm font-semibold text-red-600 bg-red-100 px-2 py-0.5 rounded-full">{{ $blockRate }}%</span>
                    </div>
                </div>
                <div class="p-3 bg-red-50 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Flagged Transactions -->
        <div class="bg-white rounded-xl shadow-sm border border-yellow-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-600 mb-1">Flagged (Score 50-79)</p>
                    <div class="flex items-baseline space-x-2">
                        <h3 class="text-3xl font-bold text-yellow-700">{{ number_format($flaggedTransactions) }}</h3>
                        <span class="text-sm font-semibold text-yellow-700 bg-yellow-100 px-2 py-0.5 rounded-full">{{ $flagRate }}%</span>
                    </div>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent High-Risk Logs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent High-Risk Transactions (Score ≥ 50)</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID / Target</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risk Score</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Analysis Reasons</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs text-indigo-600 font-medium uppercase tracking-wider">
                            <span class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                <span>Gen AI Insight</span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($highRiskLogs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                @if($log->order_id)
                                    <a href="{{ route('admin.orders.show', $log->order_id) }}" class="text-indigo-600 hover:text-indigo-900">#ORD-{{ str_pad($log->order_id, 5, '0', STR_PAD_LEFT) }}</a>
                                @else
                                    <span class="text-gray-400">Pre-checkout</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->ip_address ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->risk_score >= 80)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $log->risk_score }} (BLOCKED)
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $log->risk_score }} (FLAGGED)
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if(is_array($log->reasons))
                                    <ul class="list-disc list-inside">
                                        @foreach(array_slice($log->reasons, 0, 2) as $reason)
                                            <li>{{ Str::limit($reason, 50) }}</li>
                                        @endforeach
                                        @if(count($log->reasons) > 2)
                                            <li class="text-xs text-indigo-600 mt-1 cursor-help" title="{{ implode(', ', $log->reasons) }}">...and {{ count($log->reasons) - 2 }} more</li>
                                        @endif
                                    </ul>
                                @else
                                    <span class="text-gray-400">No specific reasons logged</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if($log->ai_insight)
                                    <div class="group relative cursor-help">
                                        <p class="text-indigo-700 font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                            {{ Str::limit($log->ai_insight, 40) }}
                                        </p>
                                        <div class="hidden group-hover:block absolute z-10 w-64 p-3 mt-1 text-xs leading-tight text-white bg-indigo-900 rounded-lg shadow-lg -left-1/2">
                                            {{ $log->ai_insight }}
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-xs">Waiting for AI...</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm">Great news! No high-risk transactions detected recently.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
