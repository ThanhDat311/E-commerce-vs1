<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceSuggestion;
use App\Services\PricingService;

class PriceSuggestionController extends Controller
{
    protected $pricingService;

    public function __construct(PricingService $pricingService)
    {
        $this->pricingService = $pricingService;
    }

    /**
     * Display a listing of pending price suggestions.
     */
    public function index()
    {
        $this->authorize('viewAny', PriceSuggestion::class);

        $suggestions = PriceSuggestion::with('product')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $kpi = [
            'pending' => PriceSuggestion::where('status', 'pending')->count(),
            'approved_today' => PriceSuggestion::where('status', 'approved')->whereDate('updated_at', today())->count(),
            'rejected_today' => PriceSuggestion::where('status', 'rejected')->whereDate('updated_at', today())->count(),
            'total_approved' => PriceSuggestion::where('status', 'approved')->count(),
        ];

        return view('pages.admin.price-suggestions.index', compact('suggestions', 'kpi'));
    }

    /**
     * Approve a price suggestion.
     */
    public function approve(PriceSuggestion $suggestion)
    {
        $this->authorize('update', $suggestion);

        try {
            $this->pricingService->approveSuggestion($suggestion);

            return redirect()->back()->with('success', 'Price suggestion approved successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to approve suggestion: '.$e->getMessage());
        }
    }

    /**
     * Reject a price suggestion.
     */
    public function reject(PriceSuggestion $suggestion)
    {
        $this->authorize('update', $suggestion);

        try {
            $this->pricingService->rejectSuggestion($suggestion);

            return redirect()->back()->with('success', 'Price suggestion rejected.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reject suggestion: '.$e->getMessage());
        }
    }
}
