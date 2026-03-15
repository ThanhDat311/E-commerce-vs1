<x-admin-layout :pageTitle="'Transaction Risk Logs'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Transaction Risk Logs' => route('admin.ai.transaction-risk.index')]">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transaction Risk Logs</h1>
            <p class="text-sm text-gray-500 mt-1">AI fraud evaluations for checkout/payment attempts.</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Metrics Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Total Evaluations</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
            <p class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1.5">Blocked Orders</p>
            <h3 class="text-3xl font-bold text-red-700">{{ number_format($stats['blocked']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-yellow-100 p-5">
            <p class="text-xs font-semibold text-yellow-500 uppercase tracking-wider mb-1.5">Flagged Orders</p>
            <h3 class="text-3xl font-bold text-yellow-700">{{ number_format($stats['flagged']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-5">
            <p class="text-xs font-semibold text-purple-400 uppercase tracking-wider mb-1.5">Avg Risk Score</p>
            <h3 class="text-3xl font-bold text-purple-700">{{ $stats['avg_score'] }}</h3>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Risk Score</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Decision</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reasons</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($logs as $log)
                        @php
                            $isFrozen = in_array($log->order_id, $frozenOrderIds);
                            $borderColor = match($log->label) {
                                'block' => 'border-l-red-500',
                                'flag' => 'border-l-amber-500',
                                default => 'border-l-green-500',
                            };
                            $riskPct = min(100, max(0, $log->risk_score));
                            $scoreColor = match(true) {
                                $riskPct >= 80 => 'bg-red-100 text-red-800',
                                $riskPct >= 50 => 'bg-amber-100 text-amber-800',
                                default => 'bg-green-100 text-green-800',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-5 py-4 border-l-4 {{ $borderColor }}">
                                <div class="text-sm text-gray-700 font-medium">{{ $log->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-5 py-4">
                                @if($log->order)
                                    <a href="{{ route('admin.orders.show', $log->order_id) }}" class="text-blue-600 hover:underline font-medium text-sm">#{{ $log->order_id }}</a>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ ucfirst($log->order->order_status) }}</div>
                                @else
                                    <span class="text-sm text-gray-400">#{{ $log->order_id }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-700 font-semibold">
                                ${{ number_format($log->total_amount, 2) }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs text-gray-600 bg-gray-100 px-1.5 py-0.5 rounded">{{ $log->ip_address ?? 'N/A' }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $scoreColor }}">
                                    {{ $riskPct }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($log->label === 'block')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-red-700 bg-red-50 border border-red-200 px-2 py-1 rounded-full">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        Block
                                    </span>
                                @elseif($log->label === 'flag')
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-1 rounded-full">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Flag
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2 py-1 rounded-full">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Allow
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                @if(is_array($log->reasons) && count($log->reasons))
                                    <ul class="text-xs text-gray-600 space-y-0.5 list-disc list-inside">
                                        @foreach(array_slice($log->reasons, 0, 3) as $reason)
                                            <li>{{ $reason }}</li>
                                        @endforeach
                                        @if(count($log->reasons) > 3)
                                            <li class="text-gray-400">+{{ count($log->reasons) - 3 }} more</li>
                                        @endif
                                    </ul>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($log->order)
                                    <div class="flex items-center justify-end gap-2">
                                        @if($isFrozen)
                                            <form action="{{ route('admin.ai.transaction-risk.release', $log->order_id) }}" method="POST" class="inline" onsubmit="return confirm('Release this order?');">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                                    Release
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.ai.transaction-risk.freeze', $log->order_id) }}" method="POST" class="inline" onsubmit="return confirm('Freeze this order for review?');">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /><path stroke-linecap="round" stroke-linejoin="round" d="M11 11V9a1 1 0 112 0v2m0 0V7a3 3 0 016 0v4" /></svg>
                                                    Freeze
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.orders.show', $log->order_id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3-9v2m0 14v2m9-9h-2M5 12H3"/></svg>
                                            View Order
                                        </a>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Order not found</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="p-4 bg-gray-100 rounded-full">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">No transaction risk logs yet.</p>
                                        <p class="text-xs text-gray-400 mt-1">AI evaluations will appear here as orders are processed.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

</x-admin-layout>
