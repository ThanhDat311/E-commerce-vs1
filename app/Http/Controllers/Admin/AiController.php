<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureStore;
use App\Models\AuthLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
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

        $highRiskTransactionLogs = (clone $query);
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
                    'target_label' => $log->order_id ? '#ORD-'.str_pad($log->order_id, 5, '0', STR_PAD_LEFT) : 'Pre-checkout',
                    'target_url' => $log->order_id ? route('admin.orders.show', $log->order_id) : null,
                    'ip_address' => $log->ip_address,
                    'risk_score' => $log->risk_score,
                    'reasons' => $log->reasons,
                    'ai_insight' => $log->ai_insight,
                ];
            });

        $highRiskLoginLogs = (clone $authLogQuery);
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
                if ($log->risk_level === 'high') {
                    $rules[] = 'High Risk Profile';
                }

                return [
                    'id' => $log->id,
                    'type' => 'login',
                    'date' => $log->created_at,
                    'target_id' => $log->user_id,
                    'target_label' => $log->user_id ? ($log->user->name ?? 'User #'.$log->user_id) : 'Pre-login',
                    'target_url' => $log->user_id ? route('admin.users.show', $log->user_id) : null,
                    'ip_address' => $log->ip_address,
                    'risk_score' => $log->risk_score,
                    'reasons' => empty($rules) ? ['Suspicious Login Pattern'] : $rules,
                    'ai_insight' => $log->device_fingerprint ? 'Device: '.substr($log->device_fingerprint, 0, 15).'...' : null,
                ];
            });

        $highRiskEvaluations = $highRiskTransactionLogs->concat($highRiskLoginLogs)
            ->sortByDesc('date')
            ->take(20)
            ->values();

        // ── Chart Data ──────────────────────────────────────────────────────────

        // 1. Risk Trend by Day (line chart)
        $trendDays = (int) $period;
        $trendLabels = [];
        $trendTransaction = [];
        $trendLogin = [];

        for ($i = $trendDays - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $trendLabels[] = now()->subDays($i)->format($trendDays <= 7 ? 'D' : 'M d');

            $trendTransaction[] = round(
                AiFeatureStore::query()->whereDate('created_at', $date)->avg('risk_score') ?? 0,
                3
            );

            $trendLogin[] = round(
                AuthLog::query()->whereDate('created_at', $date)->avg('risk_score') ?? 0,
                3
            );
        }

        $riskTrendData = [
            'labels' => $trendLabels,
            'transaction' => $trendTransaction,
            'login' => $trendLogin,
        ];

        // 2. Decision Distribution (donut charts)
        $txSafe = max(0, $totalEvaluations - $blockedTransactions - $flaggedTransactions);
        $loginSafe = max(0, $loginTotal - $loginBlocked - $loginFlagged);

        $donutData = [
            'transaction' => [$txSafe, $flaggedTransactions, $blockedTransactions],
            'login' => [$loginSafe, $loginFlagged, $loginBlocked],
        ];

        // 3. Risk Score Distribution by bucket (bar chart)
        $buckets = ['0.0–0.2', '0.2–0.4', '0.4–0.6', '0.6–0.8', '0.8–1.0'];
        $txBuckets = [];
        $loginBuckets = [];
        $bucketRanges = [[0, 0.2], [0.2, 0.4], [0.4, 0.6], [0.6, 0.8], [0.8, 1.01]];

        foreach ($bucketRanges as [$min, $max]) {
            $txBuckets[] = AiFeatureStore::query()
                ->where('created_at', '>=', $dateFrom)
                ->where('risk_score', '>=', $min)
                ->where('risk_score', '<', $max)
                ->count();

            $loginBuckets[] = AuthLog::query()
                ->where('created_at', '>=', $dateFrom)
                ->where('risk_score', '>=', $min)
                ->where('risk_score', '<', $max)
                ->count();
        }

        $distributionData = [
            'labels' => $buckets,
            'transaction' => $txBuckets,
            'login' => $loginBuckets,
        ];

        // 4. Hourly Heatmap – today's events per hour
        $hourlyLabels = [];
        $hourlyTransaction = [];
        $hourlyLogin = [];
        $today = now()->format('Y-m-d');

        $driver = \Illuminate\Support\Facades\DB::connection()->getDriverName();

        for ($h = 0; $h < 24; $h++) {
            $hourlyLabels[] = sprintf('%02d:00', $h);

            $txQuery = AiFeatureStore::query()->whereDate('created_at', $today);
            $loginQuery = AuthLog::query()->whereDate('created_at', $today);

            if ($driver === 'sqlite') {
                $txQuery->whereRaw("strftime('%H', created_at) = ?", [sprintf('%02d', $h)]);
                $loginQuery->whereRaw("strftime('%H', created_at) = ?", [sprintf('%02d', $h)]);
            } else {
                $txQuery->whereRaw('HOUR(created_at) = ?', [$h]);
                $loginQuery->whereRaw('HOUR(created_at) = ?', [$h]);
            }

            $hourlyTransaction[] = $txQuery->count();
            $hourlyLogin[] = $loginQuery->count();
        }

        $hourlyData = [
            'labels' => $hourlyLabels,
            'transaction' => $hourlyTransaction,
            'login' => $hourlyLogin,
        ];

        // ───────────────────────────────────────────────────────────────────────

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
            'period',
            'riskTrendData',
            'donutData',
            'distributionData',
            'hourlyData'
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
