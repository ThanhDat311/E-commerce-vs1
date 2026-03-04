<x-vendor-layout>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Finance & Payouts</h1>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Total Earned</p>
            <p class="text-2xl font-bold text-gray-900">${{ number_format($totalEarned, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Gross order amount</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Commission Deducted</p>
            <p class="text-2xl font-bold text-red-600">${{ number_format($totalCommission, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Platform fee</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Net Payout</p>
            <p class="text-2xl font-bold text-green-600">${{ number_format($netPayout, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">After commission</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Pending Payout</p>
            <p class="text-2xl font-bold text-amber-600">${{ number_format($pendingPayout, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Awaiting settlement</p>
        </div>
    </div>

    {{-- Commission History --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-700">Commission History</h2>
        </div>

        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 font-semibold">Order</th>
                    <th class="px-5 py-3 font-semibold">Order Total</th>
                    <th class="px-5 py-3 font-semibold">Rate</th>
                    <th class="px-5 py-3 font-semibold">Commission</th>
                    <th class="px-5 py-3 font-semibold">Net Payout</th>
                    <th class="px-5 py-3 font-semibold">Status</th>
                    <th class="px-5 py-3 font-semibold">Paid At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($commissions as $commission)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 font-medium text-gray-900">
                            #{{ $commission->order?->id ?? '—' }}
                        </td>
                        <td class="px-5 py-4 text-gray-700">
                            ${{ number_format($commission->order_total, 2) }}
                        </td>
                        <td class="px-5 py-4 text-gray-500">
                            {{ number_format($commission->commission_rate, 2) }}%
                        </td>
                        <td class="px-5 py-4 text-red-600 font-medium">
                            -${{ number_format($commission->commission_amount, 2) }}
                        </td>
                        <td class="px-5 py-4 text-green-600 font-semibold">
                            ${{ number_format($commission->net_payout, 2) }}
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $badge = match($commission->status) {
                                    'paid'    => 'success',
                                    'pending' => 'pending',
                                    default   => 'default',
                                };
                            @endphp
                            <x-ui.badge :variant="$badge">{{ ucfirst($commission->status) }}</x-ui.badge>
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs">
                            {{ $commission->paid_at?->format('d M Y') ?? '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-gray-500">No commission records yet.</p>
                            <p class="text-xs text-gray-400 mt-1">Commission records appear after your orders are processed.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($commissions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $commissions->links() }}
            </div>
        @endif
    </div>
</x-vendor-layout>
