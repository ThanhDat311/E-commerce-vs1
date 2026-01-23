@extends('layouts.admin')

@section('title', 'Edit Risk Rule: ' . $riskRule->rule_key)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.risk-rules.index') }}" class="text-blue-500 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>Back to Risk Rules
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Edit Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h1 class="text-2xl font-bold text-white">Edit Risk Rule</h1>
                    <p class="text-blue-100">{{ $riskRule->rule_key }}</p>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.risk-rules.update', $riskRule) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Rule Key (Read Only) -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rule Key</label>
                        <input type="text" value="{{ $riskRule->rule_key }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-600">
                        <small class="text-gray-500">This is a system identifier and cannot be changed</small>
                    </div>

                    <!-- Weight -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Weight (0-100)</label>
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <input type="range" name="weight" min="0" max="100" value="{{ $riskRule->weight }}" id="weightSlider" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                            </div>
                            <input type="number" name="weight" min="0" max="100" value="{{ $riskRule->weight }}" id="weightInput" class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center">
                        </div>
                        <small class="text-gray-500">Higher weight = Higher fraud risk score when rule is triggered</small>
                        @error('weight')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Weight Visualization -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-700 mb-2">Risk Level Visualization</p>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-green-400 via-yellow-400 to-red-500 h-3 rounded-full" id="weightBar" style="width: {{ $riskRule->weight }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>Low (0)</span>
                            <span id="weightDisplay">{{ $riskRule->weight }}%</span>
                            <span>High (100)</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $riskRule->description }}</textarea>
                        <small class="text-gray-500">Explain why this rule contributes to fraud risk</small>
                        @error('description')
                        <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="mb-6">
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" value="1" {{ $riskRule->is_active ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm font-medium text-gray-700">Active</span>
                        </label>
                        <small class="text-gray-500 ml-7">Enable this rule in fraud risk calculations</small>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex gap-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                        <a href="{{ route('admin.risk-rules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div>
            <!-- Rule Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Rule Information</h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Current Weight</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $riskRule->weight }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $riskRule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $riskRule->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="text-sm text-gray-900">{{ $riskRule->updated_at->format('M d, Y H:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Weight Reference -->
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                <h3 class="font-semibold text-blue-900 mb-3">Weight Reference</h3>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="font-medium text-blue-900">0-10</span>
                        <p class="text-blue-700">Minor risk factor</p>
                    </div>
                    <div>
                        <span class="font-medium text-blue-900">11-20</span>
                        <p class="text-blue-700">Low to moderate risk</p>
                    </div>
                    <div>
                        <span class="font-medium text-blue-900">21-40</span>
                        <p class="text-blue-700">Moderate to high risk</p>
                    </div>
                    <div>
                        <span class="font-medium text-blue-900">41-100</span>
                        <p class="text-blue-700">High risk factor</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Sync weight slider and input
    const slider = document.getElementById('weightSlider');
    const input = document.getElementById('weightInput');
    const display = document.getElementById('weightDisplay');
    const bar = document.getElementById('weightBar');

    slider.addEventListener('input', function() {
        input.value = this.value;
        updateDisplay();
    });

    input.addEventListener('input', function() {
        if (this.value >= 0 && this.value <= 100) {
            slider.value = this.value;
            updateDisplay();
        }
    });

    function updateDisplay() {
        const value = slider.value;
        display.textContent = value + '%';
        bar.style.width = value + '%';
    }
</script>
@endsection