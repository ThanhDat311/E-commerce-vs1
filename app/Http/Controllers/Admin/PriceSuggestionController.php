<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PriceSuggestion;
use App\Services\PricingService;
use Illuminate\Http\Request;

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

        return view('admin.price-suggestions.index', compact('suggestions'));
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
            return redirect()->back()->with('error', 'Failed to approve suggestion: ' . $e->getMessage());
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
            return redirect()->back()->with('error', 'Failed to reject suggestion: ' . $e->getMessage());
        }
    }
}
