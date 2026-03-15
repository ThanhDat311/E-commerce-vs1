<x-admin-layout :pageTitle="'AI Settings'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Settings' => route('admin.ai.settings.index')]">

    <div class="max-w-4xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">AI Engine Settings</h1>
                <p class="text-sm text-gray-500 mt-1">Configure connection and behavior of the Electro AI Engine (FastAPI microservice).</p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-800">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Info Banner --}}
        <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3">
            <div class="p-2 bg-blue-100 rounded-lg flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-sm text-blue-800">
                <strong class="block mb-1">Fail-Open Mechanism Active</strong>
                If the AI Engine is unreachable or times out, logins and checkouts proceed normally to avoid disrupting business operations. A <span class="font-mono text-xs bg-white/60 px-1 py-0.5 rounded border border-blue-200">Log::error</span> will be recorded for monitoring.
            </div>
        </div>

        <div x-data="{
            testing: false,
            testResult: null,
            async testConnection() {
                this.testing = true;
                this.testResult = null;
                try {
                    const resp = await fetch('{{ route('admin.ai.settings.test') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        }
                    });
                    this.testResult = await resp.json();
                } catch (e) {
                    this.testResult = { status: 'error', message: e.message };
                } finally {
                    this.testing = false;
                }
            }
        }" class="space-y-6">

            {{-- Connection Settings --}}
            <form method="POST" action="{{ route('admin.ai.settings.update') }}" class="space-y-6">
                @csrf

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">Connection Settings</h3>
                            <p class="text-sm text-gray-500 mt-0.5">Endpoints and authentication for the Python AI microservice.</p>
                        </div>
                        {{-- Test Connection --}}
                        <button type="button" @click="testConnection()"
                                :disabled="testing"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border transition-colors
                                       bg-white border-gray-200 hover:bg-gray-50 text-gray-700 disabled:opacity-60">
                            <span x-show="!testing">
                                <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </span>
                            <span x-show="testing">
                                <svg class="w-4 h-4 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                            </span>
                            <span x-text="testing ? 'Testing…' : 'Test Connection'"></span>
                        </button>
                    </div>

                    {{-- Test Result --}}
                    <div x-show="testResult" x-transition class="mx-6 mt-4">
                        <div :class="{
                                 'bg-green-50 border-green-200 text-green-800': testResult?.status === 'online',
                                 'bg-red-50 border-red-200 text-red-800': testResult?.status === 'offline',
                                 'bg-amber-50 border-amber-200 text-amber-800': testResult?.status === 'error',
                             }"
                             class="flex items-center gap-2 p-3 rounded-lg border text-sm font-medium">
                            <span x-show="testResult?.status === 'online'">✅</span>
                            <span x-show="testResult?.status === 'offline'">🔴</span>
                            <span x-show="testResult?.status === 'error'">⚠️</span>
                            <span x-text="testResult?.message"></span>
                        </div>
                    </div>

                    <div class="p-6 space-y-5">
                        {{-- Base URL --}}
                        <div>
                            <label for="ai_base_url" class="block text-sm font-medium text-gray-700 mb-1">API Base URL</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                </div>
                                <input type="url" name="ai_base_url" id="ai_base_url"
                                       class="pl-9 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="http://127.0.0.1:8000"
                                       value="{{ old('ai_base_url', $settings['ai_base_url']) }}">
                            </div>
                            @error('ai_base_url')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">The root URL where the FastAPI service is running (from <code class="font-mono">AI_MICROSERVICE_URL</code> in .env).</p>
                        </div>

                        {{-- API Key --}}
                        <div>
                            <label for="ai_api_key" class="block text-sm font-medium text-gray-700 mb-1">Secret Token <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                </div>
                                <input type="password" name="ai_api_key" id="ai_api_key"
                                       class="pl-9 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       placeholder="Leave blank if no auth is required"
                                       value="{{ old('ai_api_key', $settings['ai_api_key']) }}">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Provide if the AI service requires a Bearer token.</p>
                        </div>
                    </div>
                </div>

                {{-- Global Parameters --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-base font-semibold text-gray-900">Global Parameters</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Tune behavior for AI analysis requests.</p>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- Timeout --}}
                        <div>
                            <label for="ai_timeout" class="block text-sm font-medium text-gray-700 mb-1">Request Timeout (seconds)</label>
                            <input type="number" name="ai_timeout" id="ai_timeout" step="0.5" min="1" max="30"
                                   class="block w-32 rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   value="{{ old('ai_timeout', $settings['ai_timeout']) }}">
                            @error('ai_timeout')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Max time to wait before failing open. Recommended: 3 seconds.</p>
                        </div>

                        {{-- Strict Mode --}}
                        <div class="border-t border-gray-100 pt-5 flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Strict Mode</h4>
                                <p class="text-xs text-gray-500 mt-1">Block logins/transactions if the AI engine is down (fail-closed). Not recommended for production.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer" x-data>
                                <input type="hidden" name="strict_mode" value="0">
                                <input type="checkbox" name="strict_mode" value="1" class="sr-only peer" {{ $settings['strict_mode'] == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 border border-gray-300 peer-focus:ring-2 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                            </label>
                        </div>

                        {{-- Auto-Apply Price Suggestions --}}
                        <div class="border-t border-gray-100 pt-5 flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Auto-Apply Price Suggestions</h4>
                                <p class="text-xs text-gray-500 mt-1">Automatically approve pricing changes recommended by the AI (within safe margins).</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer" x-data>
                                <input type="hidden" name="auto_apply_price_suggestions" value="0">
                                <input type="checkbox" name="auto_apply_price_suggestions" value="1" class="sr-only peer" {{ $settings['auto_apply_price_suggestions'] == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 border border-gray-300 peer-focus:ring-2 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-3">
                    <button type="reset" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Reset
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

</x-admin-layout>
