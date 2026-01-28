<!-- htmlhint style-disabled-in-attr: false, script-disabled-in-attr: false -->
@extends('admin.layout.admin')

@section('title', 'AI Risk Control Center')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-4">
                <div class="p-4 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl shadow-lg">
                    <i class="fas fa-shield-virus text-white text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900">AI Risk Control Center</h1>
                    <p class="text-gray-600 text-sm mt-1">Intelligent fraud detection & adaptive risk management</p>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="button" class="flex items-center gap-2 px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-medium transition-colors border border-blue-200">
                <i class="fas fa-upload"></i> Import
            </button>
            <a href="{{ route('admin.risk-rules.export') }}" class="flex items-center gap-2 px-4 py-2.5 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg font-medium transition-colors border border-green-200">
                <i class="fas fa-download"></i> Export
            </a>
            <button type="button" class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg font-medium transition-colors border border-amber-200" onclick="if(confirm('Reset all rules to default values?')) location.href=this.dataset.resetUrl" data-reset-url="{{ route('admin.risk-rules.reset') }}">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Rules -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-700 uppercase tracking-wide">Total Rules</p>
                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $stats['total_rules'] }}</p>
                </div>
                <div class="p-3 bg-blue-200 rounded-lg">
                    <i class="fas fa-list text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Rules -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-700 uppercase tracking-wide">Active Rules</p>
                    <p class="text-3xl font-bold text-green-900 mt-2">{{ $stats['active_rules'] }}</p>
                </div>
                <div class="p-3 bg-green-200 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Weight -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-700 uppercase tracking-wide">Avg Weight</p>
                    <p class="text-3xl font-bold text-purple-900 mt-2">{{ round($stats['average_weight'], 1) }}</p>
                </div>
                <div class="p-3 bg-purple-200 rounded-lg">
                    <i class="fas fa-sliders-h text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Inactive Rules -->
        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-700 uppercase tracking-wide">Disabled Rules</p>
                    <p class="text-3xl font-bold text-red-900 mt-2">{{ $stats['inactive_rules'] }}</p>
                </div>
                <div class="p-3 bg-red-200 rounded-lg">
                    <i class="fas fa-ban text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-600 text-lg"></i>
                <span class="text-green-800 font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Risk Rules Cards -->
    @if($rules->count() > 0)
    <div class="space-y-4">
        @foreach($rules as $rule)
        <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg hover:border-gray-300 transition-all">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <!-- Left Section: Rule Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <!-- Risk Level Icon -->
                            <div class="p-2.5 bg-{{ $rule->getRiskLevelColor() }}-100 rounded-lg">
                                <i class="fas {{ $rule->getRiskLevelIcon() }} text-{{ $rule->getRiskLevelColor() }}-600 text-lg"></i>
                            </div>
                            <!-- Rule Title -->
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ str_replace('_', ' ', ucwords($rule->rule_key, '_')) }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-0.5">Rule ID: #{{ $rule->id }}</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ $rule->description }}</p>

                        <!-- Rule Metadata -->
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Weight Display -->
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Risk Weight</p>
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-blue-600" style="width: {{ $rule->weight }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 min-w-[2.5rem]">{{ $rule->weight }}%</span>
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Status</p>
                                <div class="flex items-center gap-2">
                                    @if($rule->is_active)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-circle text-green-500 text-xs"></i> Active
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                        <i class="fas fa-circle text-gray-500 text-xs"></i> Inactive
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Section: Risk Badge & Actions -->
                    <div class="flex flex-col items-end gap-4 ml-6">
                        <!-- Risk Level Badge -->
                        <div class="px-3 py-1.5 bg-{{ $rule->getRiskLevelColor() }}-100 border border-{{ $rule->getRiskLevelColor() }}-300 rounded-lg text-center">
                            <p class="text-xs font-bold text-{{ $rule->getRiskLevelColor() }}-700 uppercase tracking-wide">
                                {{ $rule->getRiskLevelLabel() }}
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.risk-rules.edit', $rule) }}" class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-medium text-sm transition-colors border border-blue-200">
                                <i class="fas fa-sliders-h text-xs"></i>
                                <span>Configure</span>
                            </a>

                            <form action="{{ route('admin.risk-rules.toggle', $rule) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center gap-2 px-3 py-1.5 {{ $rule->is_active ? 'bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200' : 'bg-green-50 hover:bg-green-100 text-green-700 border border-green-200' }} rounded-lg font-medium text-sm transition-colors">
                                    <i class="fas fa-{{ $rule->is_active ? 'power-off' : 'check' }} text-xs"></i>
                                    <span>{{ $rule->is_active ? 'Disable' : 'Enable' }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Settings Display (if available) -->
                @if($rule->settings)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Settings</p>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($rule->settings as $key => $value)
                        <div class="bg-gray-50 rounded p-2">
                            <p class="text-xs text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
        <div class="text-gray-300 text-6xl mb-4">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No Risk Rules Found</h3>
        <p class="text-gray-600 mb-6">Risk rules have not been initialized. Run the database seeder to populate default rules.</p>
        <button onclick="location.href=this.dataset.dashboardUrl" data-dashboard-url="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </button>
    </div>
    @endif
</div>

<!-- Custom Styles for Dynamic Classes -->
<style>
    .bg-red-50 {
        background-color: #fef2f2;
    }

    .bg-red-100 {
        background-color: #fee2e2;
    }

    .bg-red-200 {
        background-color: #fecaca;
    }

    .bg-red-300 {
        background-color: #fca5a5;
    }

    .bg-red-600 {
        background-color: #dc2626;
    }

    .bg-red-700 {
        background-color: #b91c1c;
    }

    .text-red-600 {
        color: #dc2626;
    }

    .text-red-700 {
        color: #b91c1c;
    }

    .border-red-200 {
        border-color: #fecaca;
    }

    .border-red-300 {
        border-color: #fca5a5;
    }

    .bg-orange-50 {
        background-color: #fff7ed;
    }

    .bg-orange-100 {
        background-color: #ffedd5;
    }

    .bg-orange-200 {
        background-color: #fed7aa;
    }

    .bg-orange-600 {
        background-color: #ea580c;
    }

    .text-orange-600 {
        color: #ea580c;
    }

    .text-orange-700 {
        color: #c2410c;
    }

    .border-orange-200 {
        border-color: #fed7aa;
    }

    .border-orange-300 {
        border-color: #fdba74;
    }

    .bg-amber-50 {
        background-color: #fffbeb;
    }

    .bg-amber-100 {
        background-color: #fef3c7;
    }

    .bg-amber-200 {
        background-color: #fde68a;
    }

    .bg-amber-600 {
        background-color: #d97706;
    }

    .bg-amber-700 {
        background-color: #b45309;
    }

    .text-amber-600 {
        color: #d97706;
    }

    .text-amber-700 {
        color: #b45309;
    }

    .border-amber-200 {
        border-color: #fde68a;
    }

    .border-amber-300 {
        border-color: #fcd34d;
    }

    .bg-green-50 {
        background-color: #f0fdf4;
    }

    .bg-green-100 {
        background-color: #dcfce7;
    }

    .bg-green-200 {
        background-color: #bbf7d0;
    }

    .bg-green-600 {
        background-color: #16a34a;
    }

    .text-green-600 {
        color: #16a34a;
    }

    .text-green-700 {
        color: #15803d;
    }

    .border-green-200 {
        border-color: #bbf7d0;
    }

    .border-green-300 {
        border-color: #86efac;
    }

    .bg-blue-50 {
        background-color: #eff6ff;
    }

    .bg-blue-100 {
        background-color: #dbeafe;
    }

    .bg-blue-200 {
        background-color: #bfdbfe;
    }

    .bg-blue-600 {
        background-color: #2563eb;
    }

    .bg-blue-700 {
        background-color: #1d4ed8;
    }

    .text-blue-600 {
        color: #2563eb;
    }

    .text-blue-700 {
        color: #1d4ed8;
    }

    .border-blue-200 {
        border-color: #bfdbfe;
    }

    .border-blue-300 {
        border-color: #93c5fd;
    }
</style>

@endsection