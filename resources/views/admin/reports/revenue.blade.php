@extends('layouts.admin')
@section('title', 'Revenue Report')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Revenue Report</h1>
    
    {{-- Form lọc ngày --}}
    <div class="card mb-4 mt-3">
        <div class="card-body">
            <form action="{{ route('admin.reports.revenue') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">From Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">To Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('admin.reports.revenue') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Thẻ tổng quan --}}
    <div class="row">
        <div class="col-xl-6 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div>Total Revenue (Selected Period)</div>
                    <h2 class="fw-bold">${{ number_format($summary['total_revenue'], 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div>Total Orders</div>
                    <h2 class="fw-bold">{{ number_format($summary['total_orders']) }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Bảng chi tiết --}}
    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-table me-1"></i> Daily Breakdown</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Orders Count</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($revenues as $item)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                        <td>{{ $item->total_orders }}</td>
                        <td class="fw-bold text-success">${{ number_format($item->total_revenue, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">No data found for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection