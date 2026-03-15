<?php

namespace App\Http\Controllers\Admin\AI;

use App\Http\Controllers\Controller;
use App\Models\AiFeatureStore;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TransactionRiskController extends Controller
{
    /**
     * Display a listing of AI-evaluated transaction fraud logs.
     */
    public function index(): View
    {
        $query = AiFeatureStore::with('order')
            ->whereNotNull('order_id')
            ->orderBy('created_at', 'desc');

        $logs = $query->paginate(20);

        $stats = [
            'total' => AiFeatureStore::whereNotNull('order_id')->count(),
            'blocked' => AiFeatureStore::whereNotNull('order_id')->where('label', 'block')->count(),
            'flagged' => AiFeatureStore::whereNotNull('order_id')->where('label', 'flag')->count(),
            'avg_score' => round(AiFeatureStore::whereNotNull('order_id')->avg('risk_score') ?? 0, 2),
        ];

        $frozenOrderIds = Order::withoutGlobalScopes()
            ->where('order_status', 'frozen')
            ->pluck('id')
            ->toArray();

        return view('pages.admin.ai-transaction-risk.index', compact('logs', 'stats', 'frozenOrderIds'));
    }

    /**
     * Freeze an order (set status to 'frozen' for manual review).
     */
    public function freeze(Order $order): RedirectResponse
    {
        $order->order_status = 'frozen';
        $order->save();

        return back()->with('success', "Order #{$order->id} has been frozen for review.");
    }

    /**
     * Release (unfreeze) an order back to its prior status.
     */
    public function release(Order $order): RedirectResponse
    {
        if ($order->order_status === 'frozen') {
            $order->order_status = 'pending';
            $order->save();
        }

        return back()->with('success', "Order #{$order->id} has been released.");
    }
}
