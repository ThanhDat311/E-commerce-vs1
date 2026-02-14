<x-admin-layout :pageTitle="'Activity Log Details'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Activity Logs' => route('admin.audit-logs.index'), 'Log Details' => '']">
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.audit-logs.index') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Activity Log Details</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Log Information -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Log Information</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Log ID</p>
                            <p class="text-sm font-semibold text-gray-900">#LOG-{{ 10000 + $auditLog->id }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Action</p>
                            @if($auditLog->action === 'created')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Created</span>
                            @elseif($auditLog->action === 'updated')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Updated</span>
                            @elseif($auditLog->action === 'deleted')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Deleted</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst($auditLog->action) }}</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Model Type</p>
                            <p class="text-sm font-semibold text-gray-900">{{ class_basename($auditLog->model_type) }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Model ID</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $auditLog->model_id }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Date & Time</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $auditLog->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">IP Address</p>
                            <p class="text-sm font-mono text-gray-900">{{ $auditLog->ip_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changes -->
            @if($auditLog->old_values || $auditLog->new_values)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Changes</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Old Values -->
                            @if($auditLog->old_values)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Old Values</h3>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ $auditLog->old_values }}</pre>
                                    </div>
                                </div>
                            @endif

                            <!-- New Values -->
                            @if($auditLog->new_values)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-700 mb-3">New Values</h3>
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                        <pre class="text-xs text-gray-800 whitespace-pre-wrap">{{ $auditLog->new_values }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Agent -->
            @if($auditLog->user_agent)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">User Agent</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 font-mono break-all">{{ $auditLog->user_agent }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- User Info -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Performed By</h2>
                </div>
                <div class="p-6">
                    @if($auditLog->user)
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden">
                                <img class="h-full w-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($auditLog->user->name) }}&color=475569&background=e2e8f0&size=48" alt="{{ $auditLog->user->name }}">
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $auditLog->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $auditLog->user->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.show', $auditLog->user) }}" class="block w-full text-center px-4 py-2 bg-blue-50 text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-100 transition-colors">
                            View User Profile
                        </a>
                    @else
                        <div class="text-center py-4">
                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500">System Action</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Related Model -->
            @if($model)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Related {{ class_basename($auditLog->model_type) }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2 mb-4">
                            <p class="text-xs font-medium text-gray-500">ID</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $model->id }}</p>
                        </div>
                        @if(method_exists($model, 'name') || isset($model->name))
                            <div class="space-y-2 mb-4">
                                <p class="text-xs font-medium text-gray-500">Name</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $model->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
