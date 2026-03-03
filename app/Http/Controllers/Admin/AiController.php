<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiController extends Controller
{
    public function index()
    {
        // Calculate vital metrics for the dashboard
        $totalEvaluations = \App\Models\AiFeatureStore::count();
        $blockedTransactions = \App\Models\AiFeatureStore::where('risk_score', '>=', 80)->count();
        $flaggedTransactions = \App\Models\AiFeatureStore::whereBetween('risk_score', [50, 79])->count();

        $blockRate = $totalEvaluations > 0 ? round(($blockedTransactions / $totalEvaluations) * 100, 1) : 0;
        $flagRate = $totalEvaluations > 0 ? round(($flaggedTransactions / $totalEvaluations) * 100, 1) : 0;

        // Fetch recent high-risk transactions
        $highRiskLogs = \App\Models\AiFeatureStore::with('order')
            ->where('risk_score', '>=', 50)
            ->orderByDesc('created_at')
            ->take(15)
            ->get();

        return view('pages.admin.ai-dashboard.index', compact(
            'totalEvaluations',
            'blockedTransactions',
            'flaggedTransactions',
            'blockRate',
            'flagRate',
            'highRiskLogs'
        ));
    }
}
