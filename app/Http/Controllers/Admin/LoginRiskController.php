<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginRiskController extends Controller
{
    /**
     * Display a listing of login risk logs.
     */
    public function index(Request $request): View
    {
        $query = AuthLog::with('user')->orderBy('created_at', 'desc');

        // Optional filtering by risk level or decision
        if ($request->has('risk_level') && $request->risk_level !== 'all') {
            $query->where('risk_level', $request->risk_level);
        }

        if ($request->has('decision') && $request->decision !== 'all') {
            $query->where('auth_decision', $request->decision);
        }

        $logs = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => AuthLog::count(),
            'blocked' => AuthLog::where('auth_decision', 'block_access')->count(),
            'flagged' => AuthLog::whereIn('auth_decision', ['challenge_otp', 'challenge_biometric'])->count(),
            'avg_score' => round(AuthLog::avg('risk_score') ?? 0, 2),
        ];

        $blockedIps = \App\Models\RiskList::where('type', 'ip')->where('action', 'block')->pluck('value')->toArray();
        $whitelistedUsers = \App\Models\RiskList::where('type', 'user_id')->where('action', 'whitelist')->pluck('value')->toArray();

        return view('pages.admin.ai-login-risk.index', compact('logs', 'stats', 'blockedIps', 'whitelistedUsers'));
    }

    /**
     * Display the specified login risk log.
     */
    public function show(AuthLog $loginRisk): View
    {
        $loginRisk->load('user');

        $blockedIps = \App\Models\RiskList::where('type', 'ip')->where('action', 'block')->pluck('value')->toArray();
        $whitelistedUsers = \App\Models\RiskList::where('type', 'user_id')->where('action', 'whitelist')->pluck('value')->toArray();

        return view('pages.admin.ai-login-risk.show', compact('loginRisk', 'blockedIps', 'whitelistedUsers'));
    }

    public function toggleIpBlock(Request $request)
    {
        $request->validate(['ip_address' => 'required|ip']);
        $ip = $request->ip_address;

        $existing = \App\Models\RiskList::where('type', 'ip')->where('value', $ip)->first();
        if ($existing) {
            $existing->delete();
            $msg = "Unblocked IP: {$ip}";
        } else {
            \App\Models\RiskList::create([
                'type' => 'ip',
                'value' => $ip,
                'action' => 'block',
                'reason' => 'Manually blocked from Login Risk Logs',
            ]);
            $msg = "Blocked IP: {$ip}";
        }

        return back()->with('success', $msg);
    }

    public function toggleUserWhitelist(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);
        $userId = $request->user_id;

        $existing = \App\Models\RiskList::where('type', 'user_id')->where('value', $userId)->first();
        if ($existing) {
            $existing->delete();
            $msg = "Removed User ID {$userId} from whitelist.";
        } else {
            \App\Models\RiskList::create([
                'type' => 'user_id',
                'value' => $userId,
                'action' => 'whitelist',
                'reason' => 'Manually whitelisted from Login Risk Logs',
            ]);
            $msg = "Whitelisted User ID {$userId}.";
        }

        return back()->with('success', $msg);
    }
}
