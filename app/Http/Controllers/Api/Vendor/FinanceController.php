<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommissionResource;
use App\Models\Commission;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    use ApiResponse;

    /**
     * Return paginated commission records with summary totals for the authenticated vendor.
     */
    public function index(Request $request): JsonResponse
    {
        $vendor = Auth::user();

        $commissions = Commission::with('order')
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate($request->integer('per_page', 20));

        $allCommissions = Commission::where('vendor_id', $vendor->id);

        $totalEarned = (clone $allCommissions)->sum('order_total');
        $totalCommission = (clone $allCommissions)->sum('commission_amount');
        $pendingPayout = (clone $allCommissions)->where('status', 'pending')->sum('commission_amount');
        $paidCommission = (clone $allCommissions)->where('status', 'paid')->sum('commission_amount');

        return $this->successResponse([
            'summary' => [
                'total_earned' => round($totalEarned, 2),
                'total_commission' => round($totalCommission, 2),
                'net_payout' => round($totalEarned - $totalCommission, 2),
                'pending_payout' => round($pendingPayout, 2),
                'paid_commission' => round($paidCommission, 2),
            ],
            'commissions' => CommissionResource::collection($commissions->items()),
            'pagination' => [
                'current_page' => $commissions->currentPage(),
                'last_page' => $commissions->lastPage(),
                'per_page' => $commissions->perPage(),
                'total' => $commissions->total(),
            ],
        ], 'Finance summary retrieved successfully.');
    }
}
