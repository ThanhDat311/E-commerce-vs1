<x-admin-layout :pageTitle="'Edit Risk Rule'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'Risk Rules' => route('admin.ai.risk-rules.index'), 'Edit' => '#']">

    <div class="max-w-2xl">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Risk Rule</h1>
            <p class="text-sm text-gray-500 mt-1">Adjust the weight and settings for this fraud detection rule.</p>
        </div>

        {{-- Rule Info Card --}}
        @php
            $levelColor = match($riskRule->risk_level) {
                'critical' => ['bg' => 'bg-red-50',    'border' => 'border-red-200',   'badge_bg' => 'bg-red-100',    'badge_text' => 'text-red-700'],
                'high'     => ['bg' => 'bg-orange-50', 'border' => 'border-orange-200','badge_bg' => 'bg-orange-100', 'badge_text' => 'text-orange-700'],
                'medium'   => ['bg' => 'bg-amber-50',  'border' => 'border-amber-200', 'badge_bg' => 'bg-amber-100',  'badge_text' => 'text-amber-700'],
                'low'      => ['bg' => 'bg-green-50',  'border' => 'border-green-200', 'badge_bg' => 'bg-green-100',  'badge_text' => 'text-green-700'],
                default    => ['bg' => 'bg-gray-50',   'border' => 'border-gray-200',  'badge_bg' => 'bg-gray-100',   'badge_text' => 'text-gray-600'],
            };
        @endphp

        <div class="p-4 {{ $levelColor['bg'] }} {{ $levelColor['border'] }} border rounded-xl mb-6 flex items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <code class="font-mono text-sm bg-white/70 px-2 py-0.5 rounded border text-gray-800">{{ $riskRule->rule_key }}</code>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $levelColor['badge_bg'] }} {{ $levelColor['badge_text'] }}">
                        {{ $riskRule->getRiskLevelLabel() }}
                    </span>
                    @if($riskRule->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600">Current weight: <strong>{{ $riskRule->weight }}</strong> points</p>
            </div>
        </div>

        {{-- Edit Form --}}
        <form method="POST" action="{{ route('admin.ai.risk-rules.update', $riskRule) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Weight --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <label for="weight" class="block text-sm font-semibold text-gray-800 mb-1">
                    Risk Weight
                    <span class="text-gray-400 font-normal ml-1">(0 – 100)</span>
                </label>
                <p class="text-xs text-gray-500 mb-3">The number of points this rule adds to the total risk score when triggered.</p>

                <div class="flex items-center gap-4">
                    <input type="range" id="weight_slider" min="0" max="100" step="1"
                           value="{{ old('weight', $riskRule->weight) }}"
                           oninput="document.getElementById('weight').value = this.value"
                           class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                    <input type="number" id="weight" name="weight"
                           min="0" max="100"
                           value="{{ old('weight', $riskRule->weight) }}"
                           oninput="document.getElementById('weight_slider').value = this.value"
                           class="w-20 rounded-lg border border-gray-300 bg-white text-center text-lg font-bold text-gray-900 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 py-2 px-3
                                  @error('weight') border-red-400 @enderror">
                </div>

                @error('weight')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror

                {{-- Weight guide --}}
                <div class="mt-3 grid grid-cols-4 text-xs text-center text-gray-400">
                    <div><span class="block font-semibold text-green-600">0–10</span>Low impact</div>
                    <div><span class="block font-semibold text-amber-500">11–25</span>Medium</div>
                    <div><span class="block font-semibold text-orange-500">26–50</span>High</div>
                    <div><span class="block font-semibold text-red-600">51–100</span>Critical</div>
                </div>
            </div>

            {{-- Description --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <label for="description" class="block text-sm font-semibold text-gray-800 mb-1">
                    Description
                </label>
                <p class="text-xs text-gray-500 mb-3">Explain when this rule triggers and what it detects.</p>
                <textarea id="description" name="description" rows="4"
                          placeholder="e.g. Triggers when the order value exceeds $5,000 threshold..."
                          class="w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-900 shadow-sm placeholder-gray-400
                                 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 resize-none
                                 @error('description') border-red-400 @enderror">{{ old('description', $riskRule->description) }}</textarea>
                @error('description')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active Status --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-800">Rule Active</p>
                    <p class="text-xs text-gray-500 mt-0.5">Inactive rules are skipped during risk evaluation.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer" x-data>
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           class="sr-only peer"
                           {{ old('is_active', $riskRule->is_active) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.ai.risk-rules.index', ['type' => $riskRule->ai_type]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>

    </div>

</x-admin-layout>
