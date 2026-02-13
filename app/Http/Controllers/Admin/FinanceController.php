<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\CommissionSetting;
use App\Models\Order;
use App\Models\VendorPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // ── Stat Cards ──────────────────────────────────────
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $platformCommissions = Commission::sum('commission_amount');
        $pendingPayouts = Commission::where('status', 'pending')
            ->selectRaw('SUM(order_total - commission_amount) as total')
            ->value('total') ?? 0;
        $taxCollected = round($totalRevenue * 0.05, 2); // 5% estimated tax

        // ── Revenue vs Payouts Chart (30 days) ──────────────
        $period = $request->input('period', 30);
        $startDate = Carbon::now()->subDays($period);

        $revenueByDay = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $payoutsByDay = Commission::where('status', 'paid')
            ->where('paid_at', '>=', $startDate)
            ->selectRaw('DATE(paid_at) as date, SUM(order_total - commission_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Fill missing days with 0
        $chartLabels = [];
        $chartRevenue = [];
        $chartPayouts = [];
        for ($i = $period; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('M d');
            $chartRevenue[] = (float) ($revenueByDay[$date] ?? 0);
            $chartPayouts[] = (float) ($payoutsByDay[$date] ?? 0);
        }

        // ── Commission Settings ─────────────────────────────
        $globalRate = CommissionSetting::getRateForVendor();

        // ── Transactions Table ──────────────────────────────
        $transactionsQuery = Commission::with(['order', 'vendor'])
            ->latest();

        if ($search = $request->input('search')) {
            $transactionsQuery->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('order', fn($oq) => $oq->where('id', 'like', "%{$search}%"))
                    ->orWhereHas('vendor', fn($vq) => $vq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $transactionsQuery->where('status', $status);
        }

        $transactions = $transactionsQuery->paginate(15)->withQueryString();

        // ── Revenue trend % (vs previous period) ────────────
        $currentRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $startDate)
            ->sum('total');
        $previousRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [
                Carbon::now()->subDays($period * 2),
                $startDate,
            ])
            ->sum('total');
        $revenueTrend = $previousRevenue > 0
            ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1)
            : 0;

        $commissionTrend = 8.2; // Placeholder

        return view('pages.admin.finance.index', compact(
            'totalRevenue',
            'platformCommissions',
            'pendingPayouts',
            'taxCollected',
            'chartLabels',
            'chartRevenue',
            'chartPayouts',
            'globalRate',
            'transactions',
            'revenueTrend',
            'commissionTrend',
        ));
    }

    public function updateCommissionRate(Request $request)
    {
        $request->validate([
            'rate' => ['required', 'numeric', 'min:0', 'max:50'],
        ]);

        CommissionSetting::updateOrCreate(
            ['vendor_id' => null],
            [
                'rate' => $request->input('rate'),
                'is_active' => true,
            ]
        );

        return redirect()->route('admin.finance.index')
            ->with('success', 'Global commission rate updated to ' . $request->input('rate') . '%');
    }

    public function exportReport(Request $request): StreamedResponse
    {
        $filename = 'finance-report-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Transaction ID',
                'Order ID',
                'Vendor',
                'Order Total',
                'Commission Rate',
                'Platform Fee',
                'Net Payout',
                'Status',
                'Date',
            ]);

            Commission::with(['order', 'vendor'])
                ->latest()
                ->chunk(200, function ($commissions) use ($handle) {
                    foreach ($commissions as $commission) {
                        fputcsv($handle, [
                            'TRX-' . str_pad($commission->id, 5, '0', STR_PAD_LEFT),
                            '#ORD-' . $commission->order_id,
                            $commission->vendor->name ?? 'N/A',
                            '$' . number_format($commission->order_total, 2),
                            $commission->commission_rate . '%',
                            '$' . number_format($commission->commission_amount, 2),
                            '$' . number_format($commission->net_payout, 2),
                            ucfirst($commission->status),
                            $commission->created_at->format('M d, Y'),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
