<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionSetting;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Vendor listing page with search + status filter
     */
    public function index(Request $request)
    {
        $query = User::where('role_id', 4)
            ->withCount('products')
            ->addSelect([
                'total_sales' => OrderItem::selectRaw('COALESCE(SUM(order_items.total), 0)')
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.payment_status', 'paid')
                    ->whereColumn('products.vendor_id', 'users.id'),
            ]);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'suspended') {
                $query->where('is_active', false);
            }
        }

        $vendors = $query->latest()->paginate(15)->withQueryString();

        return view('pages.admin.vendors.index', compact('vendors'));
    }

    /**
     * Vendor detail page with stats, chart, profile, top products
     */
    public function show(User $vendor)
    {
        abort_if($vendor->role_id !== 4, 404);

        // ── Lifetime stats ──────────────────────────────────
        $lifetimeRevenue = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('products.vendor_id', $vendor->id)
            ->sum('order_items.total');

        // Revenue trend (this month vs last month)
        $thisMonthRevenue = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('products.vendor_id', $vendor->id)
            ->where('orders.created_at', '>=', Carbon::now()->startOfMonth())
            ->sum('order_items.total');

        $lastMonthRevenue = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('products.vendor_id', $vendor->id)
            ->whereBetween('orders.created_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth(),
            ])
            ->sum('order_items.total');

        $revenueTrend = $lastMonthRevenue > 0
            ? round(($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100, 1)
            : 0;

        // Monthly orders
        $monthlySalesCount = Order::whereHas('orderItems.product', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        $lastMonthSalesCount = Order::whereHas('orderItems.product', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [
                Carbon::now()->subMonth()->startOfMonth(),
                Carbon::now()->subMonth()->endOfMonth(),
            ])
            ->count();

        $salesTrend = $lastMonthSalesCount > 0
            ? round(($monthlySalesCount - $lastMonthSalesCount) / $lastMonthSalesCount * 100, 1)
            : 0;

        // Average order value
        $avgOrderValue = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('products.vendor_id', $vendor->id)
            ->avg('orders.total') ?? 0;

        // ── 30-day Sales Chart ──────────────────────────────
        $chartRaw = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('products.vendor_id', $vendor->id)
            ->where('orders.created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $chartValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $key = $day->format('Y-m-d');
            $chartLabels[] = $day->format('M d');
            $chartValues[] = (float) ($chartRaw[$key] ?? 0);
        }

        // ── Top Selling Products ────────────────────────────
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(order_items.total) as total_revenue'))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // ── Commission Rate ─────────────────────────────────
        $commissionRate = CommissionSetting::getRateForVendor($vendor->id);

        // ── Product count ───────────────────────────────────
        $vendor->loadCount('products');

        // ── Average rating ──────────────────────────────────
        $avgRating = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendor->id)
            ->avg('reviews.rating') ?? 0;

        return view('pages.admin.vendors.show', compact(
            'vendor',
            'lifetimeRevenue',
            'revenueTrend',
            'thisMonthRevenue',
            'monthlySalesCount',
            'salesTrend',
            'avgOrderValue',
            'chartLabels',
            'chartValues',
            'topProducts',
            'commissionRate',
            'avgRating',
        ));
    }

    /**
     * Toggle vendor active/suspended status
     */
    public function toggleStatus(User $vendor)
    {
        abort_if($vendor->role_id !== 4, 404);

        $vendor->update(['is_active' => ! $vendor->is_active]);

        $status = $vendor->is_active ? 'activated' : 'suspended';

        return back()->with('success', "Vendor \"{$vendor->name}\" has been {$status}.");
    }

    /**
     * Update vendor-specific commission rate
     */
    public function updateCommission(Request $request, User $vendor)
    {
        abort_if($vendor->role_id !== 4, 404);

        $request->validate([
            'rate' => ['required', 'numeric', 'min:0', 'max:50'],
        ]);

        CommissionSetting::updateOrCreate(
            ['vendor_id' => $vendor->id],
            ['rate' => $request->input('rate'), 'is_active' => true]
        );

        return back()->with('success', "Commission rate for \"{$vendor->name}\" updated to {$request->input('rate')}%.");
    }
}
