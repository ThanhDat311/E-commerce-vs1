@extends('admin.layout.admin')

@section('title', 'Audit Logs')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4">
                <div class="p-4 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl shadow-lg">
                    <i class="fas fa-shield-alt text-white text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">Audit Logs</h1>
                    <p class="text-gray-600 text-sm mt-1">Compliance & security audit trail</p>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.audit-logs.export', request()->query()) }}" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-blue-200 text-blue-700 rounded-lg font-medium hover:bg-blue-50 transition-colors shadow-sm">
                <i class="fas fa-download text-sm text-blue-500"></i>
                <span>Export CSV</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-700 uppercase tracking-wide">Total Logs</p>
                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $auditLogs->total() }}</p>
                </div>
                <div class="p-3 bg-blue-200 rounded-lg">
                    <i class="fas fa-history text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-indigo-700 uppercase tracking-wide">Created</p>
                    <p class="text-3xl font-bold text-indigo-900 mt-2">{{ $auditLogs->where('action', 'created')->count() }}</p>
                </div>
                <div class="p-3 bg-indigo-200 rounded-lg">
                    <i class="fas fa-plus-circle text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-xl p-6 border border-cyan-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-cyan-700 uppercase tracking-wide">Updated</p>
                    <p class="text-3xl font-bold text-cyan-900 mt-2">{{ $auditLogs->where('action', 'updated')->count() }}</p>
                </div>
                <div class="p-3 bg-cyan-200 rounded-lg">
                    <i class="fas fa-edit text-cyan-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-sky-50 to-sky-100 rounded-xl p-6 border border-sky-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-sky-700 uppercase tracking-wide">Deleted</p>
                    <p class="text-3xl font-bold text-sky-900 mt-2">{{ $auditLogs->where('action', 'deleted')->count() }}</p>
                </div>
                <div class="p-3 bg-sky-200 rounded-lg">
                    <i class="fas fa-trash text-sky-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h2 class="text-xs font-bold text-blue-500 uppercase tracking-widest mb-6 flex items-center gap-2">
            <i class="fas fa-filter text-blue-400"></i> Filter Logs
        </h2>
        <form method="GET" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2 bg-blue-50/30 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2 bg-blue-50/30 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">User</label>
                    <select name="user_id" class="w-full px-4 py-2 bg-blue-50/30 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Action</label>
                    <select name="action" class="w-full px-4 py-2 bg-blue-50/30 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-4 border-t border-blue-50">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Resource Type</label>
                    <select name="model_type" class="w-full px-4 py-2 bg-blue-50/30 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                        <option value="">All Resources</option>
                        @foreach ($modelTypes as $className => $displayName)
                        <option value="{{ $className }}" {{ request('model_type') === $className ? 'selected' : '' }}>{{ $displayName }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Resource ID</label>
                    <input type="number" name="model_id" value="{{ request('model_id') }}" placeholder="e.g. 123" class="w-full px-4 py-2 bg-blue-50/30 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all outline-none">
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition-all shadow-md shadow-blue-100">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.audit-logs.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-lg transition-all">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-blue-50/50 border-b border-gray-200 flex justify-between items-center">
            <h3 class="font-bold text-gray-900">Audit Trail Detail</h3>
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">
                Showing {{ $auditLogs->firstItem() ?? 0 }}-{{ $auditLogs->lastItem() ?? 0 }} of {{ $auditLogs->total() }} entries
            </span>
        </div>

        @if($auditLogs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Timestamp</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Action</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Resource</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">IP Address</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($auditLogs as $log)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-900 font-mono">{{ $log->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-blue-500 font-mono">{{ $log->created_at->format('H:i:s') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if ($log->user)
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xs font-bold border border-blue-200">
                                    {{ substr($log->user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $log->user->name }}</span>
                            </div>
                            @else
                            <span class="text-xs font-bold text-gray-400 italic">Unknown User</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                            $actionStyles = [
                                'created' => 'bg-indigo-100 text-indigo-700 border-indigo-200 fa-plus-circle',
                                'updated' => 'bg-blue-100 text-blue-700 border-blue-200 fa-edit',
                                'deleted' => 'bg-sky-100 text-sky-700 border-sky-200 fa-trash',
                            ];
                            $currentStyle = $actionStyles[$log->action] ?? 'bg-gray-100 text-gray-700 border-gray-200 fa-info-circle';
                            $parts = explode(' ', $currentStyle);
                            $icon = array_pop($parts);
                            $class = implode(' ', $parts);
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $class }}">
                                <i class="fas {{ $icon }}"></i>
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-900">{{ class_basename($log->model_type) }}</p>
                            <p class="text-xs text-blue-400 font-mono font-bold">#{{ $log->model_id }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($log->ip_address)
                            <span class="px-2 py-1 bg-blue-50 border border-blue-100 rounded text-[11px] font-mono text-blue-600">
                                {{ $log->ip_address }}
                            </span>
                            @else
                            <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.audit-logs.show', $log) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.audit-logs.model-history', ['model_type' => $log->model_type, 'model_id' => $log->model_id]) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="History">
                                    <i class="fas fa-history"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $auditLogs->links() }}
        </div>
        @else
        <div class="py-20 text-center">
            <div class="text-blue-100 text-7xl mb-4">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">System Secure</h3>
            <p class="text-gray-500">No activity logs found matching your filters.</p>
        </div>
        @endif
    </div>
</div>

<style>
    /* Gradient blue support */
    .bg-gradient-to-br { background-image: linear-gradient(to bottom right, var(--tw-gradient-stops)); }
    .from-blue-600 { --tw-gradient-from: #2563eb; --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to, rgba(37, 99, 235, 0)); }
    .to-indigo-700 { --tw-gradient-to: #4338ca; }
</style>
@endsection