<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dispute;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    /**
     * Display a listing of disputes
     */
    public function index(Request $request)
    {
        $query = Dispute::with(['order', 'user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $disputes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('pages.admin.disputes.index', compact('disputes'));
    }

    /**
     * Display the specified dispute
     */
    public function show(Dispute $dispute)
    {
        $dispute->load(['order.orderItems.product', 'user', 'refund']);

        return view('pages.admin.disputes.show', compact('dispute'));
    }

    /**
     * Mark dispute as under review
     */
    public function review(Dispute $dispute)
    {
        $dispute->update([
            'status' => 'under_review',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Dispute marked as under review.');
    }

    /**
     * Resolve dispute
     */
    public function resolve(Request $request, Dispute $dispute)
    {
        $request->validate([
            'resolution' => 'required|in:approve_refund,reject,partial_refund',
            'admin_response' => 'required|string|max:1000',
            'refund_amount' => 'required_if:resolution,approve_refund,partial_refund|numeric|min:0',
        ]);

        $dispute->update([
            'status' => 'resolved',
            'admin_response' => $request->admin_response,
            'resolved_at' => now(),
        ]);

        // If approving refund, create refund record
        if (in_array($request->resolution, ['approve_refund', 'partial_refund'])) {
            Refund::create([
                'order_id' => $dispute->order_id,
                'dispute_id' => $dispute->id,
                'amount' => $request->refund_amount,
                'reason' => $dispute->reason,
                'status' => 'approved',
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);
        }

        return back()->with('success', 'Dispute resolved successfully.');
    }

    /**
     * Reject dispute
     */
    public function reject(Request $request, Dispute $dispute)
    {
        $request->validate([
            'admin_response' => 'required|string|max:1000',
        ]);

        $dispute->update([
            'status' => 'rejected',
            'admin_response' => $request->admin_response,
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Dispute rejected.');
    }
}
