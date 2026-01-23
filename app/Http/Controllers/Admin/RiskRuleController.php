<?php

namespace App\Http\Controllers\Admin;

use App\Models\RiskRule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RiskRuleController extends Controller
{
    /**
     * Display a listing of risk rules.
     */
    public function index(): View
    {
        $rules = RiskRule::all()->sortBy('rule_key');

        // Statistics
        $stats = [
            'total_rules' => RiskRule::count(),
            'active_rules' => RiskRule::where('is_active', true)->count(),
            'inactive_rules' => RiskRule::where('is_active', false)->count(),
            'average_weight' => RiskRule::where('is_active', true)->avg('weight'),
        ];

        return view('admin.risk-rules.index', compact('rules', 'stats'));
    }

    /**
     * Show the form for editing a risk rule.
     */
    public function edit(RiskRule $riskRule): View
    {
        return view('admin.risk-rules.edit', compact('riskRule'));
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

        return redirect()->route('admin.risk-rules.index')
            ->with('success', "Risk rule '{$riskRule->rule_key}' updated successfully!");
    }

    /**
     * Toggle the active status of a risk rule.
     */
    public function toggle(RiskRule $riskRule): RedirectResponse
    {
        $riskRule->update(['is_active' => !$riskRule->is_active]);
        $status = $riskRule->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Risk rule '{$riskRule->rule_key}' {$status}!");
    }

    /**
     * Reset all rules to default values.
     */
    public function reset(): RedirectResponse
    {
        $defaultRules = [
            'guest_checkout' => 20,
            'new_user_24h' => 15,
            'high_value_5000' => 25,
            'high_value_1000' => 10,
            'suspicious_time' => 30,
            'large_quantity' => 20,
            'round_amount' => 10,
        ];

        foreach ($defaultRules as $key => $weight) {
            RiskRule::where('rule_key', $key)->update(['weight' => $weight]);
        }

        RiskRule::clearCache();

        return redirect()->route('admin.risk-rules.index')
            ->with('success', 'All risk rules reset to default values!');
    }

    /**
     * Get risk rules statistics (JSON API).
     */
    public function statistics()
    {
        $rules = RiskRule::where('is_active', true)->get();

        return response()->json([
            'total_rules' => RiskRule::count(),
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
    public function export()
    {
        $rules = RiskRule::all()
            ->map(function ($rule) {
                return [
                    'rule_key' => $rule->rule_key,
                    'weight' => $rule->weight,
                    'description' => $rule->description,
                    'is_active' => $rule->is_active,
                ];
            })
            ->toArray();

        return response()->json($rules)
            ->header('Content-Disposition', 'attachment; filename="risk-rules-' . date('Y-m-d-H-i-s') . '.json"');
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

        if (!is_array($data)) {
            return back()->with('error', 'Invalid JSON format');
        }

        $imported = 0;
        foreach ($data as $item) {
            if (isset($item['rule_key'], $item['weight'])) {
                RiskRule::updateOrCreate(
                    ['rule_key' => $item['rule_key']],
                    [
                        'weight' => $item['weight'],
                        'description' => $item['description'] ?? '',
                        'is_active' => $item['is_active'] ?? true,
                    ]
                );
                $imported++;
            }
        }

        RiskRule::clearCache();

        return redirect()->route('admin.risk-rules.index')
            ->with('success', "Imported {$imported} risk rules successfully!");
    }
}
