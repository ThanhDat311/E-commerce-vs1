@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Price Suggestions</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Current Price</th>
                            <th>Suggested Price</th>
                            <th>Change</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suggestions as $suggestion)
                        <tr>
                            <td>{{ $suggestion->id }}</td>
                            <td>
                                <strong>{{ $suggestion->product->name }}</strong><br>
                                <small class="text-muted">SKU: {{ $suggestion->product->sku }}</small>
                            </td>
                            <td>${{ number_format($suggestion->old_price, 2) }}</td>
                            <td>${{ number_format($suggestion->new_price, 2) }}</td>
                            <td>
                                @php
                                $change = $suggestion->new_price - $suggestion->old_price;
                                $percent = $suggestion->old_price > 0 ? ($change / $suggestion->old_price) * 100 : 0;
                                @endphp
                                <span class="{{ $change >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $change >= 0 ? '+' : '' }}${{ number_format($change, 2) }}
                                    ({{ $change >= 0 ? '+' : '' }}{{ number_format($percent, 1) }}%)
                                </span>
                            </td>
                            <td>{{ $suggestion->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.price-suggestions.approve', $suggestion) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this price change?')">Approve</button>
                                </form>
                                <form action="{{ route('admin.price-suggestions.reject', $suggestion) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this suggestion?')">Reject</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No pending price suggestions</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($suggestions->hasPages())
            <div class="d-flex justify-content-center">
                {{ $suggestions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection