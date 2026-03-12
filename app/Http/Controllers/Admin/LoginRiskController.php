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

        return view('pages.admin.ai-login-risk.index', compact('logs', 'stats'));
    }

    /**
     * Display the specified login risk log.
     */
    public function show(AuthLog $loginRisk): View
    {
        // Load the associated user if available
        $loginRisk->load('user');

        return view('pages.admin.ai-login-risk.show', compact('loginRisk'));
    }
}
