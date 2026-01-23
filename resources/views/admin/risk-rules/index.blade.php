@extends('layouts.admin')

@section('title', 'Risk Rules Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Risk Rules Management</h1>
                <p class="text-gray-600 mt-1">Configure and manage fraud detection risk rules</p>
            </div>
            <div class="flex gap-2">
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-upload mr-2"></i>Import
                </button>
                <a href="{{ route('admin.risk-rules.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
                <button type="button" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition" onclick="resetRules()">
                    <i class="fas fa-redo mr-2"></i>Reset to Default
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Total Rules</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_rules'] }}</p>
                </div>
                <i class="fas fa-list text-4xl text-blue-500 opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Active Rules</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['active_rules'] }}</p>
                </div>
                <i class="fas fa-check-circle text-4xl text-green-500 opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Avg Weight</p>
                    <p class="text-3xl font-bold text-blue-600">{{ round($stats['average_weight'], 1) }}</p>
                </div>
                <i class="fas fa-balance-scale text-4xl text-blue-500 opacity-20"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600">Inactive</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['inactive_rules'] }}</p>
                </div>
                <i class="fas fa-ban text-4xl text-red-500 opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Rules Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Rule Key</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Weight</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rules as $rule)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-mono text-gray-900">
                        {{ $rule->rule_key }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $rule->weight }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-700">{{ $rule->weight }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ Str::limit($rule->description, 50) }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $rule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $rule->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.risk-rules.edit', $rule) }}" class="text-blue-500 hover:text-blue-700 transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.risk-rules.toggle', $rule) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-yellow-500 hover:text-yellow-700 transition" title="Toggle Status">
                                    <i class="fas fa-toggle-{{ $rule->is_active ? 'on' : 'off' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-3 block text-gray-300"></i>
                        No risk rules found. Run the seeder to populate initial rules.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Risk Rules</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.risk-rules.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select JSON File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".json" required>
                        <small class="text-muted">Upload a JSON file exported from this system or manually created</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function resetRules() {
        if (confirm('Are you sure you want to reset all risk rules to default values?\nThis cannot be undone.')) {
            fetch('{{ route("admin.risk-rules.reset") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            }).then(response => window.location.reload());
        }
    }
</script>
@endsection