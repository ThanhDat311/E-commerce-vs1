<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DealController extends Controller
{
    public function index(): View
    {
        $deals = Deal::with('creator')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('pages.staff.deals.index', compact('deals'));
    }

    public function edit(Deal $deal): View
    {
        return view('pages.staff.deals.edit', compact('deal'));
    }

    /**
     * Staff can only update description.
     */
    public function update(Request $request, Deal $deal): RedirectResponse
    {
        $validated = $request->validate([
            'description' => ['nullable', 'string'],
        ]);

        $deal->update($validated);

        return redirect()->route('staff.deals.index')->with('success', 'Deal updated.');
    }

    public function toggleStatus(Deal $deal): RedirectResponse
    {
        // Staff cannot approve/toggle pending vendor deals
        if ($deal->vendor_id && $deal->status === 'pending') {
            return back()->with('error', 'Only Admin can approve vendor deals.');
        }

        $newStatus = $deal->status === 'active' ? 'draft' : 'active';
        $deal->update(['status' => $newStatus]);

        return back()->with('success', "Deal status changed to {$newStatus}.");
    }
}
