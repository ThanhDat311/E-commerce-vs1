@extends('layouts.admin')
@section('title', 'Revenue Report')

@section('content')
<x-admin.header 
    title="Revenue Report" 
    subtitle="Monitor your sales performance and revenue trends"
    icon="chart-line"
    background="green"
/>

<div class="max-w-7xl mx-auto px-6 py-8">
    {{-- Form lọc ngày --}}
    <x-admin.card variant="white" border="top" borderColor="gray" class="mb-6">
        <form action="{{ route('admin.reports.revenue') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">From Date</label>
                <x-admin.input 
                    type="date" 
                    name="start_date" 
                    :value="$startDate"
                />
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">To Date</label>
                <x-admin.input 
                    type="date" 
                    name="end_date" 
                    :value="$endDate"
                />
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.reports.revenue') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </x-admin.card>

    {{-- Metrics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <x-admin.stat-card 
            title="Total Revenue (Selected Period)"
            stat="${{ number_format($summary['total_revenue'], 2) }}"
            icon="dollar-sign"
            iconBg="green"
        />
        <x-admin.stat-card 
            title="Total Orders"
            stat="{{ number_format($summary['total_orders']) }}"
            icon="shopping-cart"
            iconBg="blue"
        />
    </div>

    {{-- Daily Breakdown Table --}}
    <x-admin.card variant="white" border="left" borderColor="gray">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-gray-900 to-gray-800 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Orders Count</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @forelse($revenues as $item)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">
                        {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <x-admin.badge variant="info">{{ $item->total_orders }} orders</x-admin.badge>
                    </td>
                    <td class="px-6 py-4 text-sm font-semibold text-green-600">
                        ${{ number_format($item->total_revenue, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-gray-600">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-3xl text-gray-400"></i>
                            <p class="font-medium">No data found for this period.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.card>
</div>
@endsection