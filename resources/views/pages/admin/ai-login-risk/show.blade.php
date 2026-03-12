<x-admin-layout :pageTitle="'Login Risk Details'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Login Risk Logs' => route('admin.ai.login-risk.index'), 'Details' => '#']">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Login Attempt Details</h1>
            <p class="text-sm text-gray-500 mt-1">Detailed AI analysis and fingerprint data for this event.</p>
        </div>
        <a href="{{ route('admin.ai.login-risk.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Logs
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Core Info & Decision --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Decision Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900">AI Decision</h2>
                    @if($loginRisk->auth_decision === 'passive_auth_allow')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">ALLOW</span>
                    @elseif($loginRisk->auth_decision === 'block_access')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">BLOCK</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">{{ strtoupper(str_replace('_', ' ', $loginRisk->auth_decision)) }}</span>
                    @endif
                </div>
                <div class="p-5">
                    <div class="mb-5 flex justify-center">
                        <div class="relative inline-flex items-center justify-center">
                            <svg class="h-32 w-32 transform -rotate-90">
                                <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-100" />
                                @php
                                    $scoreColor = 'text-green-500';
                                    if ($loginRisk->risk_score >= 0.6) $scoreColor = 'text-red-500';
                                    elseif ($loginRisk->risk_score >= 0.3) $scoreColor = 'text-yellow-500';
                                    $dashArray = 2 * pi() * 56;
                                    $dashOffset = $dashArray - ($dashArray * min($loginRisk->risk_score, 1));
                                @endphp
                                <circle cx="64" cy="64" r="56" stroke="currentColor" stroke-width="8" fill="transparent"
                                        stroke-dasharray="{{ $dashArray }}" stroke-dashoffset="{{ $dashOffset }}" class="{{ $scoreColor }} transition-all duration-1000" />
                            </svg>
                            <div class="absolute flex flex-col items-center justify-center text-center">
                                <span class="text-3xl font-bold text-gray-900">{{ $loginRisk->risk_score }}</span>
                                <span class="text-xs text-gray-500 uppercase tracking-wide mt-1">{{ $loginRisk->risk_level }} Risk</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center text-sm text-gray-600 border-t border-gray-100 pt-4">
                        <p>Event logged on <span class="font-medium text-gray-900">{{ $loginRisk->created_at->format('M d, Y') }}</span></p>
                        <p>at <span class="font-medium text-gray-900">{{ $loginRisk->created_at->format('H:i:s T') }}</span></p>
                    </div>
                </div>
            </div>

            {{-- User Info Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900">User Context</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                            {{ substr($loginRisk->user->name ?? '?', 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <div class="text-base font-medium text-gray-900">{{ $loginRisk->user->name ?? 'Unknown User' }}</div>
                            <div class="text-sm text-gray-500">{{ $loginRisk->user->email ?? 'No email available' }}</div>
                        </div>
                    </div>
                    
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-medium text-gray-500 uppercase">User ID</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $loginRisk->user_id }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-xs font-medium text-gray-500 uppercase">Attempt Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($loginRisk->is_successful)
                                    <span class="text-green-600 font-medium">Successful</span>
                                @else
                                    <span class="text-red-600 font-medium">Failed</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>

        {{-- Right Column: Technical Details --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- IP and Device Data --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900">Environment Details</h2>
                </div>
                <div class="p-0">
                    <dl class="divide-y divide-gray-100">
                        <div class="px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4 flex items-center">
                            <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 flex items-center justify-between">
                                <span class="font-mono bg-gray-50 px-2 py-1 rounded text-gray-800">{{ $loginRisk->ip_address }}</span>
                                <a href="https://ipinfo.io/{{ $loginRisk->ip_address }}" target="_blank" class="text-blue-600 hover:underline text-xs" rel="noopener">Lookup</a>
                            </dd>
                        </div>
                        <div class="px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if($loginRisk->geo_location)
                                    <div class="flex flex-col gap-1">
                                        <span>{{ is_array($loginRisk->geo_location) ? ($loginRisk->geo_location['city'] ?? '') . ', ' . ($loginRisk->geo_location['region'] ?? '') . ' ' . ($loginRisk->geo_location['country'] ?? '') : json_encode($loginRisk->geo_location) }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Not available</span>
                                @endif
                            </dd>
                        </div>
                        <div class="px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Device Fingerprint</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <p class="break-all font-mono text-xs bg-gray-50 p-2 rounded border border-gray-100">{{ $loginRisk->device_fingerprint ?? 'N/A' }}</p>
                            </dd>
                        </div>
                        <div class="px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <p class="text-gray-700">{{ $loginRisk->user_agent ?? 'N/A' }}</p>
                            </dd>
                        </div>
                        <div class="px-5 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Session ID</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <p class="font-mono text-xs text-gray-500">{{ $loginRisk->session_id ?? 'N/A' }}</p>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Raw JSON dump for debugging --}}
            <div class="bg-gray-900 rounded-xl shadow-sm border border-gray-800 overflow-hidden mt-6">
                <div class="p-4 border-b border-gray-800 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-300 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        Raw Payload Data
                    </h2>
                </div>
                <div class="p-0 overflow-x-auto">
                    <pre class="text-xs text-green-400 p-4 font-mono leading-relaxed">{{ json_encode($loginRisk->toArray(), JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
