@extends('admin.layout.admin')

@section('title', 'Revenue Report')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4">
                <div class="p-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg">
    <i class="fas fa-chart-line text-white text-3xl"></i>
</div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Revenue Report</h1>
                    <p class="text-gray-600 text-sm mt-1">Monitor your sales performance and revenue trends</p>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="window.print()" class="flex items-center gap-2 px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-medium transition-colors border border-blue-200">
                <i class="fas fa-print"></i> Print Report
            </button>
            <a href="{{ route('admin.reports.revenue') }}" class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg font-medium transition-colors border border-amber-200">
                <i class="fas fa-redo"></i> Reset Filter
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="{{ route('admin.reports.revenue') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">From Date</p>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">To Date</p>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all">
            </div>
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition-all shadow-md shadow-green-100">
                    <i class="fas fa-filter text-sm"></i> Apply Analytics
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-8 border border-green-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-700 uppercase tracking-wide">Total Revenue (Selected Period)</p>
                    <p class="text-4xl font-black text-green-900 mt-2">${{ number_format($summary['total_revenue'], 2) }}</p>
                </div>
                <div class="p-4 bg-green-200 rounded-2xl">
                    <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-8 border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-700 uppercase tracking-wide">Total Orders</p>
                    <p class="text-4xl font-black text-blue-900 mt-2">{{ number_format($summary['total_orders']) }}</p>
                </div>
                <div class="p-4 bg-blue-200 rounded-2xl">
                    <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-list-ul text-gray-400"></i> Daily Breakdown
        </h2>
        
        @if(count($revenues) > 0)
        <div class="space-y-3">
            @foreach($revenues as $item)
            <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all">
                <div class="px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-4 flex-1">
                        <div class="p-3 bg-gray-100 rounded-lg group-hover:bg-blue-50 transition-colors">
                            <i class="far fa-calendar-alt text-gray-500 group-hover:text-blue-500"></i>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($item->date)->format('d F, Y') }}</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">{{ \Carbon\Carbon::parse($item->date)->format('l') }}</p>
                        </div>
                    </div>

                    <div class="flex-1 text-center">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-50 text-blue-700 rounded-full text-sm font-bold border border-blue-100">
                            <i class="fas fa-box-open text-xs"></i>
                            {{ $item->total_orders }} Orders
                        </span>
                    </div>

                    <div class="flex-1 flex justify-end">
                        <div class="text-right">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Daily Revenue</p>
                            <p class="text-xl font-black text-green-600">
                                + ${{ number_format($item->total_revenue, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-xl border border-gray-200 py-20 text-center">
            <div class="text-gray-200 text-7xl mb-4">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No Data Available</h3>
            <p class="text-gray-500 max-w-sm mx-auto">We couldn't find any revenue data for the selected date range. Try adjusting your filters.</p>
        </div>
        @endif
    </div>
</div>

<style>
    /* Đồng bộ các mã màu từ trang Risk Rules */
    .bg-green-50 { background-color: #f0fdf4; }
    .bg-green-100 { background-color: #dcfce7; }
    .bg-green-200 { background-color: #bbf7d0; }
    .text-green-600 { color: #16a34a; }
    .text-green-700 { color: #15803d; }
    
    .bg-blue-50 { background-color: #eff6ff; }
    .bg-blue-100 { background-color: #dbeafe; }
    .bg-blue-200 { background-color: #bfdbfe; }
    .text-blue-700 { color: #1d4ed8; }

    @media print {
        .flex.gap-3, form { display: none; }
    }
</style>
@endsection