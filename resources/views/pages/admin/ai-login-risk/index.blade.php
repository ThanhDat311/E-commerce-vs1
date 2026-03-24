<x-admin-layout :pageTitle="'Login Risk Logs'" :breadcrumbs="['Admin' => route('admin.dashboard'), 'AI Management' => '#', 'Login Risk Logs' => route('admin.ai.login-risk.index')]">

    {{-- Page Header & Stats --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Login Risk Logs</h1>
            <p class="text-sm text-gray-500 mt-1">AI evaluations for user login attempts.</p>
        </div>
    </div>

    {{-- Metrics Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Total Logins</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ number_format($stats['total']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5">
            <p class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1.5">Blocked Logins</p>
            <h3 class="text-3xl font-bold text-red-700">{{ number_format($stats['blocked']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-yellow-100 p-5">
            <p class="text-xs font-semibold text-yellow-500 uppercase tracking-wider mb-1.5">Flagged/Challenged</p>
            <h3 class="text-3xl font-bold text-yellow-700">{{ number_format($stats['flagged']) }}</h3>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-5">
            <p class="text-xs font-semibold text-purple-400 uppercase tracking-wider mb-1.5">Avg Risk Score</p>
            <h3 class="text-3xl font-bold text-purple-700">{{ number_format($stats['avg_score'] * 100, 2) }}%</h3>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex flex-wrap gap-4 items-end">
        <form action="{{ route('admin.ai.login-risk.index') }}" method="GET" class="flex flex-wrap gap-4 items-end w-full">
            <div>
                <label for="risk_level" class="block text-sm font-medium text-gray-700 mb-1">Risk Level</label>
                <select name="risk_level" id="risk_level" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="all" {{ request('risk_level') === 'all' ? 'selected' : '' }}>All Levels</option>
                    <option value="low" {{ request('risk_level') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('risk_level') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('risk_level') === 'high' ? 'selected' : '' }}>High</option>
                    <option value="critical" {{ request('risk_level') === 'critical' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>
            
            <div>
                <label for="decision" class="block text-sm font-medium text-gray-700 mb-1">AI Decision</label>
                <select name="decision" id="decision" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="all" {{ request('decision') === 'all' ? 'selected' : '' }}>All Decisions</option>
                    <option value="passive_auth_allow" {{ request('decision') === 'passive_auth_allow' ? 'selected' : '' }}>Allow</option>
                    <option value="challenge_otp" {{ request('decision') === 'challenge_otp' ? 'selected' : '' }}>Challenge OTP</option>
                    <option value="challenge_biometric" {{ request('decision') === 'challenge_biometric' ? 'selected' : '' }}>Challenge Biometric</option>
                    <option value="block_access" {{ request('decision') === 'block_access' ? 'selected' : '' }}>Block</option>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.ai.login-risk.index') }}" class="ml-2 text-sm text-gray-600 hover:text-gray-900">Clear</a>
            </div>
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP / Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Risk Score</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Decision</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l-4 {{ $log->auth_decision === 'block_access' ? 'border-red-500' : ($log->auth_decision === 'passive_auth_allow' ? 'border-green-500' : 'border-yellow-500') }}">
                                <div>{{ $log->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                                        {{ substr($log->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'Unknown User' }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $log->user_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="font-mono text-xs">{{ $log->ip_address }}</div>
                                @if(isset($log->geo_location['country']))
                                    <div class="text-xs mt-0.5 flex items-center gap-1">
                                        <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ is_array($log->geo_location) ? ($log->geo_location['city'] ?? '') . ', ' . ($log->geo_location['country'] ?? '') : 'Unknown' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $scoreColor = 'green';
                                    if ($log->risk_score >= 0.8) $scoreColor = 'purple';
                                    elseif ($log->risk_score >= 0.6) $scoreColor = 'red';
                                    elseif ($log->risk_score >= 0.3) $scoreColor = 'yellow';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $scoreColor }}-100 text-{{ $scoreColor }}-800">
                                    {{ number_format($log->risk_score * 100, 2) }}% / {{ ucfirst($log->risk_level) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->auth_decision === 'passive_auth_allow')
                                    <span class="text-green-600 font-semibold"><i class="fas fa-check-circle mr-1"></i> Allow</span>
                                @elseif($log->auth_decision === 'block_access')
                                    <span class="text-red-600 font-semibold"><i class="fas fa-ban mr-1"></i> Block</span>
                                @else
                                    <span class="text-yellow-600 font-semibold"><i class="fas fa-exclamation-triangle mr-1"></i> {{ ucfirst(str_replace('_', ' ', $log->auth_decision)) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($log->is_successful)
                                    <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded border border-green-200">Success</span>
                                @else
                                    <span class="px-2 py-1 bg-red-50 text-red-700 text-xs rounded border border-red-200">Failed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Toggle IP Block --}}
                                    <form action="{{ route('admin.ai.login-risk.toggle-ip') }}" method="POST" class="inline" onsubmit="return confirm('Toggle block for this IP?');">
                                        @csrf
                                        <input type="hidden" name="ip_address" value="{{ $log->ip_address }}">
                                        @if(in_array($log->ip_address, $blockedIps))
                                            <button type="submit" class="text-white bg-red-600 hover:bg-red-700 px-2 py-1.5 rounded text-xs transition-colors shadow-sm font-semibold" title="Unblock IP">
                                                Unblock IP
                                            </button>
                                        @else
                                            <button type="submit" class="text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 px-2 py-1.5 rounded text-xs transition-colors font-semibold" title="Block IP">
                                                Block IP
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Toggle User Whitelist --}}
                                    @if($log->user_id)
                                        <form action="{{ route('admin.ai.login-risk.toggle-user') }}" method="POST" class="inline" onsubmit="return confirm('Toggle whitelist for this user?');">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $log->user_id }}">
                                            @if(in_array($log->user_id, $whitelistedUsers))
                                                <button type="submit" class="text-white bg-green-600 hover:bg-green-700 px-2 py-1.5 rounded text-xs transition-colors shadow-sm font-semibold" title="Remove User from Whitelist">
                                                    Un-Whitelist
                                                </button>
                                            @else
                                                <button type="submit" class="text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 px-2 py-1.5 rounded text-xs transition-colors font-semibold" title="Whitelist User">
                                                    Whitelist User
                                                </button>
                                            @endif
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.ai.login-risk.show', $log->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded transition-colors ml-1 font-semibold text-xs">
                                        Details
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                No login risk logs found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium">{{ $logs->firstItem() }}</span> to <span class="font-medium">{{ $logs->lastItem() }}</span> of <span class="font-bold text-gray-700">{{ $logs->total() }}</span> results
                </p>
                <div>
                    {{ $logs->links('vendor.pagination.admin') }}
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
