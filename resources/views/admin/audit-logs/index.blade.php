@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Audit Logs</h1>
                <p class="text-gray-600 mt-1">System-wide activity log for tracking changes</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.audit-logs.export', request()->query()) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-download mr-2"></i>Export CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Filter Logs</h2>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Model Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Model Type</label>
                <select name="model_type" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Models</option>
                    @foreach ($modelTypes as $className => $displayName)
                    <option value="{{ $className }}" {{ request('model_type') === $className ? 'selected' : '' }}>
                        {{ $displayName }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                <select name="action" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Actions</option>
                    <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>

            <!-- User Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                <select name="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Start Date Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- End Date Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Model ID Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Model ID</label>
                <input type="number" name="model_id" value="{{ request('model_id') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter model ID">
            </div>

            <!-- Filter Buttons -->
            <div class="flex gap-2 items-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition flex-1">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.audit-logs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Model</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auditLogs as $log)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-900">#{{ $log->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        @if ($log->user)
                        <a href="{{ route('admin.users.show', $log->user) }}" class="text-blue-500 hover:underline">
                            {{ $log->user->name }}
                        </a>
                        @else
                        <span class="text-gray-500">Unknown User</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $log->action === 'created' ? 'bg-green-100 text-green-800' : ($log->action === 'updated' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="text-xs text-gray-600">
                            {{ class_basename($log->model_type) }}
                        </div>
                        <div class="text-xs text-gray-500">
                            ID: {{ $log->model_id }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $log->ip_address ?? 'N/A' }}</code>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <div>{{ $log->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $log->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.audit-logs.show', $log) }}" class="text-blue-500 hover:text-blue-700 mr-3" title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.audit-logs.model-history', ['model_type' => $log->model_type, 'model_id' => $log->model_id]) }}" class="text-green-500 hover:text-green-700" title="View Model History">
                            <i class="fas fa-history"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-3 block text-gray-300"></i>
                        No audit logs found. Try adjusting your filters.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $auditLogs->links() }}
    </div>
</div>
@endsection