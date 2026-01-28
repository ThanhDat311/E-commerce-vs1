@extends('admin.layout.admin')

@section('title', 'Audit Logs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-slate-900 rounded-lg">
                    <i class="fas fa-shield-alt text-slate-200 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Audit Logs</h1>
                    <p class="text-gray-600 text-sm mt-1">Compliance & security audit trail</p>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.audit-logs.export', request()->query()) }}" class="flex items-center gap-2 px-4 py-2.5 bg-slate-700 hover:bg-slate-800 text-white rounded-lg font-medium transition-colors">
            <i class="fas fa-download text-sm"></i>
            <span>Export CSV</span>
        </a>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Filter Logs</h2>
        <form method="GET" class="space-y-4">
            <!-- Row 1: Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">User</label>
                    <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Action</label>
                    <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
            </div>

            <!-- Row 2: Additional Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Resource Type</label>
                    <select name="model_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Resources</option>
                        @foreach ($modelTypes as $className => $displayName)
                        <option value="{{ $className }}" {{ request('model_type') === $className ? 'selected' : '' }}>
                            {{ $displayName }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Resource ID</label>
                    <input type="number" name="model_id" value="{{ request('model_id') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 123">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-filter mr-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.audit-logs.index') }}" class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-900 rounded-lg text-sm font-medium transition-colors text-center">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <!-- Table Header -->
        <div class="bg-slate-50 border-b border-gray-200 px-6 py-3">
            <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide">
                @php
                $totalLogs = $auditLogs->total();
                $showing = $auditLogs->firstItem() ?? 0;
                $to = $auditLogs->lastItem() ?? 0;
                @endphp
                Showing {{ $showing }} to {{ $to }} of {{ $totalLogs }} entries
            </div>
        </div>

        <!-- Table -->
        @if($auditLogs->count() > 0)
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Timestamp</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Resource</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Details</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($auditLogs as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <!-- Timestamp -->
                    <td class="px-6 py-3 text-gray-900">
                        <div class="font-mono text-xs text-gray-700">{{ $log->created_at->format('M d, Y') }}</div>
                        <div class="font-mono text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>

                    <!-- User -->
                    <td class="px-6 py-3 text-gray-900">
                        @if ($log->user)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium">
                            <i class="fas fa-user text-xs"></i>
                            {{ $log->user->name }}
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-50 text-gray-500 rounded text-xs">
                            <i class="fas fa-ghost text-xs"></i>
                            Unknown User
                        </span>
                        @endif
                    </td>

                    <!-- Action -->
                    <td class="px-6 py-3">
                        @php
                        $actionColors = [
                        'created' => 'bg-green-50 text-green-700 border border-green-200',
                        'updated' => 'bg-blue-50 text-blue-700 border border-blue-200',
                        'deleted' => 'bg-red-50 text-red-700 border border-red-200',
                        ];
                        $actionClass = $actionColors[$log->action] ?? 'bg-gray-50 text-gray-700 border border-gray-200';
                        $actionIcons = [
                        'created' => 'fa-plus-circle',
                        'updated' => 'fa-edit',
                        'deleted' => 'fa-trash',
                        ];
                        $actionIcon = $actionIcons[$log->action] ?? 'fa-info-circle';
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-xs font-semibold {{ $actionClass }}">
                            <i class="fas {{ $actionIcon }} text-xs"></i>
                            {{ ucfirst($log->action) }}
                        </span>
                    </td>

                    <!-- Resource -->
                    <td class="px-6 py-3 text-gray-900">
                        <div class="text-xs font-medium text-gray-900">{{ class_basename($log->model_type) }}</div>
                        <div class="font-mono text-xs text-gray-500">ID: {{ $log->model_id }}</div>
                    </td>

                    <!-- IP Address -->
                    <td class="px-6 py-3 font-mono text-xs text-gray-600">
                        @if($log->ip_address)
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 rounded text-gray-700">
                            <i class="fas fa-network-wired text-xs text-gray-500"></i>
                            {{ $log->ip_address }}
                        </span>
                        @else
                        <span class="text-gray-400">—</span>
                        @endif
                    </td>

                    <!-- Details -->
                    <td class="px-6 py-3 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.audit-logs.show', $log) }}" class="inline-flex items-center justify-center w-8 h-8 rounded hover:bg-blue-100 text-blue-600 transition-colors" title="View details">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.audit-logs.model-history', ['model_type' => $log->model_type, 'model_id' => $log->model_id]) }}" class="inline-flex items-center justify-center w-8 h-8 rounded hover:bg-gray-100 text-gray-600 transition-colors" title="View history">
                                <i class="fas fa-history text-sm"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="bg-slate-50 border-t border-gray-200 px-6 py-4">
            {{ $auditLogs->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="text-gray-300 text-5xl mb-3">
                <i class="fas fa-inbox"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-1">No Audit Logs Found</h3>
            <p class="text-gray-600 mb-4">No activity matches your current filters</p>
            <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                <i class="fas fa-sync-alt"></i> Clear Filters
            </a>
        </div>
        @endif
    </div>
</div>

@endsection