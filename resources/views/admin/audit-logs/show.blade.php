@extends('layouts.admin')

@section('title', 'Audit Log Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.audit-logs.index') }}" class="text-blue-500 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>Back to Logs
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">Audit Log #{{ $auditLog->id }}</h1>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Basic Info -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold mb-4">Basic Information</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Action</label>
                                <div class="mt-1">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $auditLog->action === 'created' ? 'bg-green-100 text-green-800' : ($auditLog->action === 'updated' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($auditLog->action) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Model Type</label>
                                <p class="mt-1 text-gray-900">{{ class_basename($auditLog->model_type) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Model ID</label>
                                <p class="mt-1 text-gray-900">{{ $auditLog->model_id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date & Time</label>
                                <p class="mt-1 text-gray-900">{{ $auditLog->created_at->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="mb-8 pb-8 border-b border-gray-200">
                        <h2 class="text-lg font-semibold mb-4">User Information</h2>
                        @if ($auditLog->user)
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">User</label>
                                <p class="mt-1 text-gray-900">
                                    <a href="{{ route('admin.users.show', $auditLog->user) }}" class="text-blue-500 hover:underline">
                                        {{ $auditLog->user->name }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-gray-900">{{ $auditLog->user->email }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-gray-500">User information not available</p>
                        @endif
                    </div>

                    <!-- Network Info -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold mb-4">Network Information</h2>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">IP Address</label>
                                <code class="block mt-1 bg-gray-100 px-3 py-2 rounded text-sm text-gray-900">
                                    {{ $auditLog->ip_address ?? 'N/A' }}
                                </code>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">User Agent</label>
                                <code class="block mt-1 bg-gray-100 px-3 py-2 rounded text-sm text-gray-900 break-words">
                                    {{ $auditLog->user_agent ?? 'N/A' }}
                                </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Model Info -->
            @if ($model)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Related Model</h2>
                <p class="text-sm text-gray-600 mb-3">This {{ class_basename($auditLog->model_type) }} still exists in the system.</p>
                <a href="#" class="block w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-center transition">
                    <i class="fas fa-link mr-2"></i>View Model
                </a>
            </div>
            @else
            <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6">
                <h2 class="text-lg font-semibold mb-4 text-yellow-900">Model Not Found</h2>
                <p class="text-sm text-yellow-700">This {{ class_basename($auditLog->model_type) }} has been deleted from the system.</p>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.audit-logs.model-history', ['model_type' => $auditLog->model_type, 'model_id' => $auditLog->model_id]) }}" class="block w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-center text-sm transition">
                        <i class="fas fa-history mr-2"></i>View Model History
                    </a>
                    <a href="{{ route('admin.audit-logs.index', ['model_id' => $auditLog->model_id]) }}" class="block w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-center text-sm transition">
                        <i class="fas fa-filter mr-2"></i>Filter by Model
                    </a>
                    <a href="{{ route('admin.audit-logs.index', ['user_id' => $auditLog->user_id]) }}" class="block w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded text-center text-sm transition">
                        <i class="fas fa-user mr-2"></i>Filter by User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Changes Section -->
    @if ($auditLog->action === 'updated')
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Changes Made</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Old Values -->
                <div>
                    <h3 class="text-md font-semibold mb-3 text-red-700">Before</h3>
                    @if ($auditLog->old_values)
                    <div class="bg-red-50 rounded p-4 border border-red-200">
                        <pre class="text-xs overflow-x-auto text-gray-700"><code>{{ json_encode(json_decode($auditLog->old_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                    @else
                    <p class="text-gray-500">No previous values</p>
                    @endif
                </div>

                <!-- New Values -->
                <div>
                    <h3 class="text-md font-semibold mb-3 text-green-700">After</h3>
                    @if ($auditLog->new_values)
                    <div class="bg-green-50 rounded p-4 border border-green-200">
                        <pre class="text-xs overflow-x-auto text-gray-700"><code>{{ json_encode(json_decode($auditLog->new_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                    </div>
                    @else
                    <p class="text-gray-500">No new values</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @elseif ($auditLog->action === 'created')
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Initial Values</h2>
        </div>
        <div class="p-6">
            @if ($auditLog->new_values)
            <div class="bg-green-50 rounded p-4 border border-green-200">
                <pre class="text-sm overflow-x-auto text-gray-700"><code>{{ json_encode(json_decode($auditLog->new_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
            </div>
            @else
            <p class="text-gray-500">No values recorded</p>
            @endif
        </div>
    </div>
    @elseif ($auditLog->action === 'deleted')
    <div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Deleted Values</h2>
        </div>
        <div class="p-6">
            @if ($auditLog->old_values)
            <div class="bg-red-50 rounded p-4 border border-red-200">
                <pre class="text-sm overflow-x-auto text-gray-700"><code>{{ json_encode(json_decode($auditLog->old_values), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
            </div>
            @else
            <p class="text-gray-500">No values recorded</p>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection