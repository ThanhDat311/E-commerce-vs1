<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureStore;
use App\Models\AuthLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->query('period', '30');
        $dateFrom = now()->subDays((int) $period)->startOfDay();

        $query = AiFeatureStore::query()->where('created_at', '>=', $dateFrom);

        $totalEvaluations = (clone $query)->count();
        $blockedTransactions = (clone $query)->where('risk_score', '>=', 0.60)->count();
        $flaggedTransactions = (clone $query)->whereBetween('risk_score', [0.35, 0.59])->count();
        $avgRiskScore = round((clone $query)->avg('risk_score') ?? 0, 3);

        $blockRate = $totalEvaluations > 0 ? round(($blockedTransactions / $totalEvaluations) * 100, 1) : 0;
        $flagRate = $totalEvaluations > 0 ? round(($flaggedTransactions / $totalEvaluations) * 100, 1) : 0;

        // Login Risk Metrics
        $authLogQuery = AuthLog::query()->where('created_at', '>=', $dateFrom);
        $loginTotal = (clone $authLogQuery)->count();
        $loginBlocked = (clone $authLogQuery)->where('auth_decision', 'block_access')->count();
        $loginFlagged = (clone $authLogQuery)->whereIn('auth_decision', ['challenge_otp', 'challenge_biometric'])->count();
        $loginAvgScore = round((clone $authLogQuery)->avg('risk_score') ?? 0, 3);
        $loginBlockRate = $loginTotal > 0 ? round(($loginBlocked / $loginTotal) * 100, 1) : 0;
        $loginFlagRate = $loginTotal > 0 ? round(($loginFlagged / $loginTotal) * 100, 1) : 0;

        $highRiskTransactionLogs = clone $query;
        $highRiskTransactionLogs = $highRiskTransactionLogs->with('order')
            ->where('risk_score', '>=', 0.35)
            ->orderByDesc('created_at')
            ->take(20)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'type' => 'transaction',
                    'date' => $log->created_at,
                    'target_id' => $log->order_id,
                    'target_label' => $log->order_id ? '#ORD-' . str_pad($log->order_id, 5, '0', STR_PAD_LEFT) : 'Pre-checkout',
                    'target_url' => $log->order_id ? route('admin.orders.show', $log->order_id) : null,
                    'ip_address' => $log->ip_address,
                    'risk_score' => $log->risk_score,
                    'reasons' => $log->reasons,
                    'ai_insight' => $log->ai_insight,
                ];
            });

        $highRiskLoginLogs = clone $authLogQuery;
        $highRiskLoginLogs = $highRiskLoginLogs->with('user')
            ->where('risk_score', '>=', 0.35)
            ->orderByDesc('created_at')
            ->take(20)
            ->get()
            ->map(function ($log) {
                $rules = [];
                if ($log->auth_decision === 'block_access') {
                    $rules[] = 'Access Blocked by Policy';
                } elseif (in_array($log->auth_decision, ['challenge_otp', 'challenge_biometric'])) {
                    $rules[] = 'Challenge Required';
                }
                if ($log->risk_level === 'high') $rules[] = 'High Risk Profile';

                return [
                    'id' => $log->id,
                    'type' => 'login',
                    'date' => $log->created_at,
                    'target_id' => $log->user_id,
                    'target_label' => $log->user_id ? ($log->user->name ?? 'User #' . $log->user_id) : 'Pre-login',
                    'target_url' => $log->user_id ? route('admin.users.show', $log->user_id) : null,
                    'ip_address' => $log->ip_address,
                    'risk_score' => $log->risk_score,
                    'reasons' => empty($rules) ? ['Suspicious Login Pattern'] : $rules,
                    'ai_insight' => $log->device_fingerprint ? 'Device: ' . substr($log->device_fingerprint, 0, 15) . '...' : null,
                ];
            });

        $highRiskEvaluations = $highRiskTransactionLogs->concat($highRiskLoginLogs)
            ->sortByDesc('date')
            ->take(20)
            ->values();

        $aiServiceOnline = $this->checkServiceHealth();

        return view('pages.admin.ai-dashboard.index', compact(
            'totalEvaluations',
            'blockedTransactions',
            'flaggedTransactions',
            'blockRate',
            'flagRate',
            'avgRiskScore',
            'loginTotal',
            'loginBlocked',
            'loginFlagged',
            'loginAvgScore',
            'loginBlockRate',
            'loginFlagRate',
            'highRiskEvaluations',
            'aiServiceOnline',
            'period'
        ));
    }

    private function checkServiceHealth(): bool
    {
        try {
            $url = config('services.ai_microservice.url', 'http://localhost:8001');
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(2)->get("{$url}/health");

            return $response->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
