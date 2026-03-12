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

        $totalEvaluations = AiFeatureStore::count();
        $blockedTransactions = AiFeatureStore::where('risk_score', '>=', 0.60)->count();
        $flaggedTransactions = AiFeatureStore::whereBetween('risk_score', [0.35, 0.59])->count();
        $avgRiskScore = round(AiFeatureStore::avg('risk_score') ?? 0, 3);

        $blockRate = $totalEvaluations > 0 ? round(($blockedTransactions / $totalEvaluations) * 100, 1) : 0;
        $flagRate = $totalEvaluations > 0 ? round(($flaggedTransactions / $totalEvaluations) * 100, 1) : 0;

        // Login Risk Metrics
        $authLogQuery = AuthLog::query()->where('created_at', '>=', $dateFrom);
        $loginTotal = AuthLog::count();
        $loginBlocked = AuthLog::where('auth_decision', 'block_access')->count();
        $loginFlagged = AuthLog::whereIn('auth_decision', ['challenge_otp', 'challenge_biometric'])->count();
        $loginAvgScore = round(AuthLog::avg('risk_score') ?? 0, 3);
        $loginBlockRate = $loginTotal > 0 ? round(($loginBlocked / $loginTotal) * 100, 1) : 0;
        $loginFlagRate = $loginTotal > 0 ? round(($loginFlagged / $loginTotal) * 100, 1) : 0;

        $highRiskLogs = $query->with('order')
            ->where('risk_score', '>=', 0.35)
            ->orderByDesc('created_at')
            ->take(20)
            ->get();

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
            'highRiskLogs',
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
