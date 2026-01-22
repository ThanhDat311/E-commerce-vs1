<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * 1. BÁO CÁO DOANH THU (Có lọc theo ngày)
     */
    public function revenue(Request $request)
    {
        // Mặc định lấy 30 ngày gần nhất nếu không chọn ngày
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Query tổng hợp doanh thu theo ngày
        $revenues = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('COUNT(id) as total_orders')
            )
            ->where('order_status', '!=', 'cancelled') // Không tính đơn hủy
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Tính tổng cộng của giai đoạn này
        $summary = [
            'total_revenue' => $revenues->sum('total_revenue'),
            'total_orders' => $revenues->sum('total_orders')
        ];

        return view('admin.reports.revenue', compact('revenues', 'startDate', 'endDate', 'summary'));
    }

    /**
     * 2. TOP SẢN PHẨM BÁN CHẠY
     */
    public function topProducts(Request $request)
    {
        // Query join order_items với products để tính tổng số lượng bán
        $topProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(price * quantity) as total_revenue')
            )
            ->with('product') // Eager load thông tin sản phẩm
            ->groupBy('product_id')
            ->orderByDesc('total_sold') // Sắp xếp bán nhiều nhất lên đầu
            ->limit(20) // Lấy top 20
            ->get();

        return view('admin.reports.top_products', compact('topProducts'));
    }

    /**
     * 3. CẢNH BÁO TỒN KHO THẤP
     */
    public function lowStock(Request $request)
    {
        // Ngưỡng cảnh báo (mặc định dưới 10 cái là báo động)
        $threshold = 10;

        $lowStockProducts = Product::where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        return view('admin.reports.low_stock', compact('lowStockProducts'));
    }
}