<x-admin-layout :pageTitle="'Login Risk Details'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Login Risk Logs' => route('admin.ai.login-risk.index'), 'Details' => '#']">

    {{-- Page Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Login Attempt Details</h1>
            <p class="text-sm text-gray-500 mt-1">Detailed AI analysis and fingerprint data for this event.</p>
        </div>
        <a href="{{ route('admin.ai.login-risk.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-white bg-gray-800 hover:bg-gray-700 px-4 py-2 rounded-lg transition-colors shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Logs
        </a>
    </div>

    @php
        $score      = (float) $loginRisk->risk_score;
        $scorePct   = number_format($score * 100, 2);
        $isBlocked  = $loginRisk->auth_decision === 'block_access';
        $isChallenged = in_array($loginRisk->auth_decision, ['challenge_otp', 'challenge_biometric']);
        $isAllowed  = $loginRisk->auth_decision === 'passive_auth_allow';

        $decisionLabel = match($loginRisk->auth_decision) {
            'passive_auth_allow'   => 'ALLOW',
            'block_access'         => 'BLOCK',
            'challenge_otp'        => 'CHALLENGE OTP',
            'challenge_biometric'  => 'CHALLENGE BIOMETRIC',
            default                => strtoupper(str_replace('_', ' ', $loginRisk->auth_decision)),
        };
        $decisionClass = $isBlocked  ? 'bg-red-100 text-red-800 ring-1 ring-red-200'
                       : ($isChallenged ? 'bg-yellow-100 text-yellow-800 ring-1 ring-yellow-200'
                       : 'bg-green-100 text-green-800 ring-1 ring-green-200');

        $levelColors = [
            'low'      => ['gauge' => '#22c55e', 'bg' => 'from-green-50 to-emerald-50',  'text' => 'text-green-600',  'badge' => 'bg-green-100 text-green-700'],
            'medium'   => ['gauge' => '#f59e0b', 'bg' => 'from-yellow-50 to-amber-50',   'text' => 'text-yellow-600', 'badge' => 'bg-yellow-100 text-yellow-700'],
            'high'     => ['gauge' => '#ef4444', 'bg' => 'from-red-50 to-rose-50',        'text' => 'text-red-600',    'badge' => 'bg-red-100 text-red-700'],
            'critical' => ['gauge' => '#7c3aed', 'bg' => 'from-purple-50 to-violet-50',  'text' => 'text-purple-600', 'badge' => 'bg-purple-100 text-purple-700'],
        ];
        $lc = $levelColors[$loginRisk->risk_level] ?? $levelColors['low'];

        // SVG gauge
        $radius    = 56;
        $circumference = 2 * pi() * $radius;
        $dashOffset = $circumference - ($circumference * min($score, 1));

        // Reasons
        $reasons = $loginRisk->reasons ?? [];
        if (is_string($reasons)) { $reasons = json_decode($reasons, true) ?? []; }
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ============= LEFT COLUMN ============= --}}
        <div class="lg:col-span-1 space-y-5">

            {{-- Risk Score Gauge Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Card Header --}}
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r {{ $lc['bg'] }}">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">AI Decision</h2>
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold {{ $decisionClass }}">
                        {{ $decisionLabel }}
                    </span>
                </div>

                {{-- Gauge --}}
                <div class="p-6 flex flex-col items-center">
                    <div class="relative inline-flex items-center justify-center mb-4">
                        <svg class="h-36 w-36 transform -rotate-90" viewBox="0 0 128 128">
                            <circle cx="64" cy="64" r="{{ $radius }}" stroke="#f3f4f6" stroke-width="10" fill="transparent"/>
                            <circle cx="64" cy="64" r="{{ $radius }}"
                                    stroke="{{ $lc['gauge'] }}" stroke-width="10" fill="transparent"
                                    stroke-linecap="round"
                                    stroke-dasharray="{{ $circumference }}"
                                    stroke-dashoffset="{{ $dashOffset }}"
                                    style="transition: stroke-dashoffset 1s ease"/>
                        </svg>
                        <div class="absolute flex flex-col items-center">
                            <span class="text-3xl font-extrabold text-gray-900">{{ $scorePct }}%</span>
                            <span class="text-xs font-semibold uppercase tracking-widest {{ $lc['text'] }} mt-0.5">{{ $loginRisk->risk_level }} Risk</span>
                        </div>
                    </div>

                    {{-- Risk level bar --}}
                    <div class="w-full rounded-full h-2 bg-gray-100 mb-4 overflow-hidden">
                        <div class="h-2 rounded-full transition-all duration-1000"
                             style="width: {{ min($score * 100, 100) }}%; background: {{ $lc['gauge'] }};"></div>
                    </div>

                    {{-- Status badge --}}
                    <div class="flex items-center gap-2">
                        @if($loginRisk->is_successful)
                            <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                <span class="h-2 w-2 rounded-full bg-green-500 inline-block animate-pulse"></span>
                                Login Successful
                            </span>
                        @else
                            <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <span class="h-2 w-2 rounded-full bg-red-500 inline-block"></span>
                                Login Failed
                            </span>
                        @endif
                    </div>

                    <p class="text-xs text-gray-400 mt-4 text-center">
                        Logged on <span class="font-medium text-gray-600">{{ $loginRisk->created_at->format('M d, Y') }}</span>
                        at <span class="font-medium text-gray-600">{{ $loginRisk->created_at->format('H:i:s T') }}</span>
                    </p>
                </div>
            </div>

            {{-- User Context Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">User Context</h2>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-sm flex-shrink-0">
                            {{ strtoupper(substr($loginRisk->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-base font-semibold text-gray-900">{{ $loginRisk->user->name ?? 'Unknown User' }}</div>
                            <div class="text-sm text-gray-500">{{ $loginRisk->user->email ?? 'No email available' }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">User ID</p>
                            <p class="text-sm font-semibold text-gray-800">#{{ $loginRisk->user_id }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Role</p>
                            <p class="text-sm font-semibold text-gray-800 capitalize">{{ $loginRisk->user->role ?? 'user' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============= RIGHT COLUMN ============= --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- AI Risk Reasons Card --}}
            @if(!empty($reasons))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">AI Risk Reasoning</h2>
                    <span class="ml-auto text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">{{ count($reasons) }} signals</span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($reasons as $reason)
                    <div class="flex items-start gap-3 p-3 rounded-xl border {{ $isBlocked ? 'border-red-100 bg-red-50' : ($isChallenged ? 'border-yellow-100 bg-yellow-50' : 'border-green-100 bg-green-50') }}">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 {{ $isBlocked ? 'text-red-500' : ($isChallenged ? 'text-yellow-500' : 'text-green-500') }}" fill="currentColor" viewBox="0 0 20 20">
                                @if($isBlocked)
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                @elseif($isChallenged)
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                @endif
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700">{{ $reason }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Environment Details Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                    </svg>
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Environment Details</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    {{-- IP Address --}}
                    <div class="px-5 py-4 flex items-center gap-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">IP Address</p>
                            <p class="text-sm font-mono font-semibold text-gray-800 mt-0.5">{{ $loginRisk->ip_address }}</p>
                        </div>
                        <a href="https://ipinfo.io/{{ $loginRisk->ip_address }}" target="_blank" rel="noopener"
                           class="flex-shrink-0 text-xs text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors font-medium">
                            Lookup ↗
                        </a>
                    </div>

                    {{-- Location --}}
                    <div class="px-5 py-4 flex items-center gap-4">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Location</p>
                            <p class="text-sm font-medium text-gray-800 mt-0.5">
                                @if($loginRisk->geo_location && is_array($loginRisk->geo_location))
                                    {{ implode(', ', array_filter([$loginRisk->geo_location['cityName'] ?? $loginRisk->geo_location['city'] ?? null, $loginRisk->geo_location['regionName'] ?? $loginRisk->geo_location['region'] ?? null, $loginRisk->geo_location['countryName'] ?? $loginRisk->geo_location['country'] ?? null])) ?: 'Unknown location' }}
                                @else
                                    <span class="text-gray-400 italic">Not available</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Device Fingerprint --}}
                    <div class="px-5 py-4 flex items-center gap-4">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Device Fingerprint</p>
                            <p class="text-xs font-mono text-gray-600 mt-0.5 truncate">{{ $loginRisk->device_fingerprint ?? 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- User Agent --}}
                    <div class="px-5 py-4 flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">User Agent</p>
                            <p class="text-xs text-gray-600 mt-0.5 break-all leading-relaxed">{{ $loginRisk->user_agent ?? 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- Session ID --}}
                    <div class="px-5 py-4 flex items-center gap-4">
                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 overflow-hidden">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Session ID</p>
                            <p class="text-xs font-mono text-gray-500 mt-0.5 truncate">{{ $loginRisk->session_id ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Quick Actions</h2>
                </div>
                <div class="p-5 flex flex-wrap gap-3">
                    {{-- Block IP --}}
                    <form action="{{ route('admin.ai.login-risk.toggle-ip') }}" method="POST"
                          onsubmit="return confirm('Toggle block for IP {{ $loginRisk->ip_address }}?');">
                        @csrf
                        <input type="hidden" name="ip_address" value="{{ $loginRisk->ip_address }}">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                                       {{ in_array($loginRisk->ip_address, $blockedIps ?? []) ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-red-50 hover:bg-red-100 text-red-700 border border-red-200' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            {{ in_array($loginRisk->ip_address, $blockedIps ?? []) ? 'Unblock IP' : 'Block IP' }}
                        </button>
                    </form>

                    {{-- Whitelist User --}}
                    @if($loginRisk->user_id)
                    <form action="{{ route('admin.ai.login-risk.toggle-user') }}" method="POST"
                          onsubmit="return confirm('Toggle whitelist for this user?');">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $loginRisk->user_id }}">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors
                                       {{ in_array($loginRisk->user_id, $whitelistedUsers ?? []) ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-green-50 hover:bg-green-100 text-green-700 border border-green-200' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            {{ in_array($loginRisk->user_id, $whitelistedUsers ?? []) ? 'Remove from Whitelist' : 'Whitelist User' }}
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.ai.login-risk.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        View All Logs
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
