<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskRule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiskRuleController extends Controller
{
    /**
     * Display a listing of risk rules filtered by AI type.
     */
    public function index(Request $request): View
    {
        $activeType = $request->query('type', RiskRule::TYPE_TRANSACTION);

        // Guard against invalid type values
        if (! in_array($activeType, [RiskRule::TYPE_TRANSACTION, RiskRule::TYPE_LOGIN])) {
            $activeType = RiskRule::TYPE_TRANSACTION;
        }

        $rules = RiskRule::forType($activeType)->get()->sortBy('rule_key')->values();

        $stats = [
            'total_rules' => RiskRule::forType($activeType)->count(),
            'active_rules' => RiskRule::forType($activeType)->where('is_active', true)->count(),
            'inactive_rules' => RiskRule::forType($activeType)->where('is_active', false)->count(),
            'average_weight' => RiskRule::forType($activeType)->where('is_active', true)->avg('weight'),
        ];

        $aiTypes = [
            RiskRule::TYPE_TRANSACTION => 'Transaction Fraud AI',
            RiskRule::TYPE_LOGIN => 'Login Risk AI',
        ];

        return view('pages.admin.risk-rules.index', compact('rules', 'stats', 'activeType', 'aiTypes'));
    }

    /**
     * Show the form for editing a risk rule.
     */
    public function edit(RiskRule $riskRule): View
    {
        return view('pages.admin.risk-rules.edit', compact('riskRule'));
    }

    /**
     * Update the specified risk rule in storage.
     */
    public function update(Request $request, RiskRule $riskRule): RedirectResponse
    {
        $validated = $request->validate([
            'weight' => 'required|integer|min:0|max:100',
            'description' => 'required|string|min:10|max:500',
            'is_active' => 'boolean',
        ]);

        $riskRule->update($validated);

        return redirect()->route('admin.ai.risk-rules.index', ['type' => $riskRule->ai_type])
            ->with('success', "Risk rule '{$riskRule->rule_key}' updated successfully!");
    }

    /**
     * Toggle the active status of a risk rule.
     */
    public function toggle(RiskRule $riskRule): RedirectResponse
    {
        $riskRule->update(['is_active' => ! $riskRule->is_active]);
        $status = $riskRule->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Risk rule '{$riskRule->rule_key}' {$status}!");
    }

    /**
     * Reset all rules of the current type to default values.
     */
    public function reset(Request $request): RedirectResponse
    {
        $type = $request->query('type', RiskRule::TYPE_TRANSACTION);

        $defaultRules = match ($type) {
            RiskRule::TYPE_LOGIN => [
                'failed_attempts_3' => 25,
                'failed_attempts_5' => 45,
                'new_ip_address' => 20,
                'suspicious_login_hour' => 15,
                'new_device_fingerprint' => 20,
                'vpn_proxy_detected' => 35,
                'impossible_travel' => 40,
                'credential_stuffing_pattern' => 50,
            ],
            default => [
                'guest_checkout' => 20,
                'new_user_24h' => 15,
                'high_value_5000' => 25,
                'high_value_1000' => 10,
                'suspicious_time' => 30,
                'large_quantity' => 20,
                'round_amount' => 10,
            ],
        };

        foreach ($defaultRules as $key => $weight) {
            RiskRule::where('rule_key', $key)->where('ai_type', $type)->update(['weight' => $weight]);
        }

        RiskRule::clearCache($type);

        return redirect()->route('admin.ai.risk-rules.index', ['type' => $type])
            ->with('success', 'All rules reset to default values!');
    }

    /**
     * Get risk rules statistics (JSON API).
     */
    public function statistics(Request $request)
    {
        $type = $request->query('type', RiskRule::TYPE_TRANSACTION);
        $rules = RiskRule::forType($type)->where('is_active', true)->get();

        return response()->json([
            'ai_type' => $type,
            'total_rules' => RiskRule::forType($type)->count(),
            'active_rules' => $rules->count(),
            'average_weight' => $rules->avg('weight'),
            'max_weight' => $rules->max('weight'),
            'min_weight' => $rules->min('weight'),
            'total_weight' => $rules->sum('weight'),
            'rules' => $rules->map(function ($rule) {
                return [
                    'key' => $rule->rule_key,
                    'weight' => $rule->weight,
                    'description' => $rule->description,
                    'active' => $rule->is_active,
                ];
            }),
        ]);
    }

    /**
     * Export rules as JSON.
     */
    public function export(Request $request)
    {
        $type = $request->query('type', RiskRule::TYPE_TRANSACTION);

        $rules = RiskRule::forType($type)->get()
            ->map(function ($rule) {
                return [
                    'rule_key' => $rule->rule_key,
                    'ai_type' => $rule->ai_type,
                    'weight' => $rule->weight,
                    'risk_level' => $rule->risk_level,
                    'description' => $rule->description,
                    'is_active' => $rule->is_active,
                ];
            })
            ->toArray();

        return response()->json($rules)
            ->header('Content-Disposition', 'attachment; filename="risk-rules-'.$type.'-'.date('Y-m-d-H-i-s').'.json"');
    }

    /**
     * Import rules from JSON.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:json',
        ]);

        $file = $request->file('file');
        $data = json_decode(file_get_contents($file->path()), true);

        if (! is_array($data)) {
            return back()->with('error', 'Invalid JSON format');
        }

        $imported = 0;
        foreach ($data as $item) {
            if (isset($item['rule_key'], $item['weight'])) {
                RiskRule::updateOrCreate(
                    ['rule_key' => $item['rule_key']],
                    [
                        'ai_type' => $item['ai_type'] ?? RiskRule::TYPE_TRANSACTION,
                        'weight' => $item['weight'],
                        'risk_level' => $item['risk_level'] ?? 'medium',
                        'description' => $item['description'] ?? '',
                        'is_active' => $item['is_active'] ?? true,
                    ]
                );
                $imported++;
            }
        }

        RiskRule::clearCache();

        return redirect()->route('admin.ai.risk-rules.index')
            ->with('success', "Imported {$imported} risk rules successfully!");
    }

    /**
     * Simulate a rule change against recent feature store logs.
     */
    public function simulate(Request $request, \App\Services\AIDecisionEngine $aiEngine)
    {
        $validated = $request->validate([
            'rule_key' => 'required|string',
            'weight' => 'required|integer|min:0|max:100',
        ]);

        $ruleKey = $validated['rule_key'];
        $newWeight = $validated['weight'];

        $recentLogs = \App\Models\AiFeatureStore::whereNotNull('order_id')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        $originalBlocked = 0;
        $simulatedBlocked = 0;
        $originalFlagged = 0;
        $simulatedFlagged = 0;

        foreach ($recentLogs as $log) {
            if ($log->label === 'block') {
                $originalBlocked++;
            } elseif ($log->label === 'flag') {
                $originalFlagged++;
            }

            $orderData = [
                'id' => $log->order_id,
                'total' => $log->total_amount,
                'quantity' => 1,
            ];

            $userData = ['id' => null];

            $contextData = [
                'hour' => clone $log->created_at ? $log->created_at->hour : now()->hour,
                'ip' => $log->ip_address,
            ];

            $simResult = $aiEngine->assessFraudRisk($orderData, $userData, $contextData, [
                $ruleKey => $newWeight,
            ]);

            if ($simResult['decision'] === 'BLOCK') {
                $simulatedBlocked++;
            } elseif ($simResult['decision'] === 'FLAG') {
                $simulatedFlagged++;
            }
        }

        return response()->json([
            'analyzed_count' => $recentLogs->count(),
            'original_blocked' => $originalBlocked,
            'simulated_blocked' => $simulatedBlocked,
            'original_flagged' => $originalFlagged,
            'simulated_flagged' => $simulatedFlagged,
            'diff_blocked' => $simulatedBlocked - $originalBlocked,
            'diff_flagged' => $simulatedFlagged - $originalFlagged,
        ]);
    }
}
