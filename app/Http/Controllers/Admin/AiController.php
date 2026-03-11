<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureStore;
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
        $blockedTransactions = AiFeatureStore::where('risk_score', '>=', 80)->count();
        $flaggedTransactions = AiFeatureStore::whereBetween('risk_score', [50, 79])->count();
        $avgRiskScore = round(AiFeatureStore::avg('risk_score') ?? 0, 1);

        $blockRate = $totalEvaluations > 0 ? round(($blockedTransactions / $totalEvaluations) * 100, 1) : 0;
        $flagRate = $totalEvaluations > 0 ? round(($flaggedTransactions / $totalEvaluations) * 100, 1) : 0;

        $highRiskLogs = $query->with('order')
            ->where('risk_score', '>=', 50)
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
            'highRiskLogs',
            'aiServiceOnline',
            'period'
        ));
    }

    private function checkServiceHealth(): bool
    {
        try {
            $url = config('services.ai_microservice.url', 'http://localhost:8000');
            $response = Http::timeout(2)->get("{$url}/health");

            return $response->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
