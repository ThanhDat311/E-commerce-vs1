<x-admin-layout :pageTitle="'Model History'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Activity Logs' => route('admin.audit-logs.index'), 'Model History' => '']">
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.audit-logs.index') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ class_basename($modelType) }} History</h1>
        </div>
        @if($model)
            <p class="text-sm text-gray-600 ml-14">Viewing history for {{ class_basename($modelType) }} #{{ $modelId }}</p>
        @endif
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50/80 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">User</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Action</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Changes</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Date & Time</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($auditLogs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($log->user)
                                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                                            <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($log->user->name) }}&color=475569&background=e2e8f0&size=32" alt="{{ $log->user->name }}">
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $log->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                        </div>
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <span class="text-gray-500 text-sm">System</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->action === 'created')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                        </svg>
                                        Created
                                    </span>
                                @elseif($log->action === 'updated')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Updated
                                    </span>
                                @elseif($log->action === 'deleted')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Deleted
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($log->old_values || $log->new_values)
                                    <div class="max-w-md">
                                        @if($log->old_values)
                                            <p class="text-xs text-gray-500 mb-1">Old: <span class="text-red-600 font-mono">{{ Str::limit($log->old_values, 50) }}</span></p>
                                        @endif
                                        @if($log->new_values)
                                            <p class="text-xs text-gray-500">New: <span class="text-green-600 font-mono">{{ Str::limit($log->new_values, 50) }}</span></p>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">No changes recorded</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $log->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->created_at->format('h:i A') }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.audit-logs.show', $log) }}" class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="View Details">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No history found for this {{ class_basename($modelType) }}.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($auditLogs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $auditLogs->firstItem() }}</span> to <span class="font-medium">{{ $auditLogs->lastItem() }}</span> of <span class="font-bold text-gray-700">{{ $auditLogs->total() }}</span> logs
                </p>
                <div>
                    {{ $auditLogs->links() }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
