<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function index(Request $request): View
    {
        $vendor = $request->user();

        $commissions = Commission::with('order')
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(20);

        $allCommissions = Commission::where('vendor_id', $vendor->id);

        $totalEarned = (clone $allCommissions)->sum('order_total');
        $totalCommission = (clone $allCommissions)->sum('commission_amount');
        $pendingPayout = (clone $allCommissions)->where('status', 'pending')->sum('commission_amount');
        $paidCommission = (clone $allCommissions)->where('status', 'paid')->sum('commission_amount');

        $netPayout = $totalEarned - $totalCommission;

        return view('pages.vendor.finance.index', compact(
            'commissions',
            'totalEarned',
            'totalCommission',
            'netPayout',
            'pendingPayout',
            'paidCommission'
        ));
    }
}
