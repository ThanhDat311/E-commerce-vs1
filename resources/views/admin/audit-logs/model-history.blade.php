@extends('layouts.admin')

@section('title', 'Model History')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.audit-logs.index') }}" class="text-blue-500 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>Back to Logs
        </a>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Model Change History</h1>
        <p class="text-gray-600 mt-1">
            <span class="font-semibold">{{ class_basename($modelType) }}</span> #{{ $modelId }}
        </p>
    </div>

    <!-- Model Info Card -->
    @if ($model)
    <div class="bg-blue-50 rounded-lg border border-blue-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-blue-900 mb-2">Current Model Information</h2>
        <p class="text-blue-700">This {{ class_basename($modelType) }} exists in the system and is actively being tracked.</p>
    </div>
    @else
    <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-yellow-900 mb-2">Model Deleted</h2>
        <p class="text-yellow-700">This {{ class_basename($modelType) }} has been deleted, but its change history is preserved below.</p>
    </div>
    @endif

    <!-- Timeline -->
    <div class="relative">
        <!-- Timeline Track -->
        <div class="hidden lg:block absolute left-1/2 transform -translate-x-1/2 w-1 bg-gray-200 top-0 bottom-0"></div>

        <!-- Timeline Items -->
        <div class="space-y-8">
            @forelse ($auditLogs as $log)
            <div class="relative">
                <!-- Timeline Dot -->
                <div class="hidden lg:flex absolute left-1/2 transform -translate-x-1/2 items-center justify-center">
                    <div class="relative z-10">
                        <div class="w-4 h-4 bg-white border-4 rounded-full {{ $log->action === 'created' ? 'border-green-500' : ($log->action === 'updated' ? 'border-blue-500' : 'border-red-500') }}"></div>
                    </div>
                </div>

                <!-- Content Card -->
                <div class="lg:w-5/12 ml-auto lg:ml-0 {{ $loop->even ? 'lg:ml-auto' : 'lg:mr-auto' }}">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Card Header -->
                        <div class="px-6 py-4 border-b border-gray-200 {{ $log->action === 'created' ? 'bg-green-50' : ($log->action === 'updated' ? 'bg-blue-50' : 'bg-red-50') }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $log->action === 'created' ? 'bg-green-100 text-green-800' : ($log->action === 'updated' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                    <p class="text-sm text-gray-600 mt-2">
                                        {{ $log->created_at->format('M d, Y - h:i A') }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-500">#{{ $log->id }}</span>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6">
                            <!-- User Info -->
                            @if ($log->user)
                            <div class="mb-4 pb-4 border-b border-gray-200">
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">{{ $log->user->name }}</span>
                                    <span class="text-xs text-gray-500">({{ $log->user->email }})</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    IP: <code class="bg-gray-100 px-2 py-1 rounded">{{ $log->ip_address ?? 'N/A' }}</code>
                                </p>
                            </div>
                            @endif

                            <!-- Changes -->
                            @if ($log->action === 'updated')
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Changes:</h4>
                                <div class="space-y-3">
                                    @if ($log->old_values && $log->new_values)
                                    @php
                                    $oldVals = json_decode($log->old_values, true);
                                    $newVals = json_decode($log->new_values, true);
                                    $changedKeys = array_keys(array_diff_assoc($oldVals, $newVals));
                                    @endphp
                                    @foreach ($changedKeys as $key)
                                    <div class="bg-gray-50 rounded p-3">
                                        <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">{{ $key }}</p>
                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <div>
                                                <span class="text-gray-600">Before:</span>
                                                <code class="block bg-red-50 text-red-700 px-2 py-1 rounded mt-1 break-words">{{ $oldVals[$key] ?? 'N/A' }}</code>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">After:</span>
                                                <code class="block bg-green-50 text-green-700 px-2 py-1 rounded mt-1 break-words">{{ $newVals[$key] ?? 'N/A' }}</code>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            @elseif ($log->action === 'created')
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Initial Values:</h4>
                                @if ($log->new_values)
                                <div class="bg-gray-50 rounded p-3">
                                    <pre class="text-xs overflow-x-auto"><code>{{ json_encode(json_decode($log->new_values, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                </div>
                                @endif
                            </div>
                            @elseif ($log->action === 'deleted')
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Deleted Values:</h4>
                                @if ($log->old_values)
                                <div class="bg-gray-50 rounded p-3">
                                    <pre class="text-xs overflow-x-auto"><code>{{ json_encode(json_decode($log->old_values, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                            <a href="{{ route('admin.audit-logs.show', $log) }}" class="text-sm text-blue-500 hover:text-blue-700">
                                View Full Details <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 text-lg">No change history found for this model.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($auditLogs->hasPages())
        <div class="mt-8">
            {{ $auditLogs->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    @media (max-width: 1024px) {
        .lg\:w-5\/12 {
            width: 100%;
        }
    }
</style>
@endsection