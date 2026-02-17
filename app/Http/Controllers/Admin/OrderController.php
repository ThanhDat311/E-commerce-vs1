<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory; // Đảm bảo bạn đã tạo Model này
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * 1. DANH SÁCH ĐƠN HÀNG (Kèm bộ lọc nâng cao)
     */
    public function index(Request $request)
    {
        // Khởi tạo query với User để hiển thị tên khách
        $query = Order::with('user');

        // --- BỘ LỌC TÌM KIẾM ---

        // 1. Tìm theo từ khóa (Mã đơn, Tên, Email)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%$keyword%")
                    ->orWhere('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }

        // 2. Lọc theo Trạng thái đơn hàng (có giá trị: pending, processing, shipped, completed, cancelled)
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // 3. Lọc theo Trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // 4. Lọc theo phương thức thanh toán
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // 5. Lọc theo Ngày tháng (From)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        // 6. Lọc theo Ngày tháng (To)
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sắp xếp đơn mới nhất lên đầu và phân trang
        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('pages.admin.orders.index', compact('orders'));
    }

    /**
     * 2. XEM CHI TIẾT ĐƠN HÀNG
     */
    public function show($id)
    {
        // Eager load: Lấy kèm sản phẩm, user, và lịch sử đơn hàng
        $order = Order::with(['orderItems.product', 'user', 'histories.user'])->findOrFail($id);

        return view('pages.admin.orders.show', compact('order'));
    }

    /**
     * 3. CẬP NHẬT ĐƠN HÀNG (Status, Payment Status, Tracking, Admin Notes)
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Mảng lưu lại các thay đổi để ghi log
        $actionDescription = [];
        $hasChanges = false;

        // --- XỬ LÝ LOGIC CẬP NHẬT ---

        // 1. Cập nhật Trạng thái đơn hàng (order_status)
        if ($request->has('status') && $request->status != $order->order_status) {
            $oldStatus = $order->order_status;
            $newStatus = $request->status;
            $actionDescription[] = "Status changed from '{$oldStatus}' to '{$newStatus}'";
            $order->order_status = $newStatus;
            $hasChanges = true;
        }

        // 2. Cập nhật Trạng thái thanh toán (payment_status)
        if ($request->has('payment_status') && $request->payment_status != $order->payment_status) {
            $oldPaymentStatus = $order->payment_status;
            $newPaymentStatus = $request->payment_status;
            $actionDescription[] = "Payment status changed from '{$oldPaymentStatus}' to '{$newPaymentStatus}'";
            $order->payment_status = $newPaymentStatus;
            $hasChanges = true;
        }

        // 3. Cập nhật Vận chuyển (Carrier & Tracking)
        if ($request->filled('tracking_number')) {
            // Chỉ ghi log nếu tracking thay đổi
            if ($order->tracking_number != $request->tracking_number) {
                $actionDescription[] = "Updated tracking info: {$request->tracking_number}";
                $order->tracking_number = $request->tracking_number;
                $order->shipping_carrier = $request->shipping_carrier ?? $order->shipping_carrier;
                $hasChanges = true;
            }
        }

        // 4. Ghi chú nội bộ (Admin Note)
        if ($request->filled('admin_note')) {
            $order->admin_note = $request->admin_note;
            $hasChanges = true;
        }

        // Lưu vào Database nếu có thay đổi
        if ($hasChanges) {
            $order->save();

            // --- GHI LỊCH SỬ (LOG) ---
            if (! empty($actionDescription)) {
                // Kiểm tra xem Model OrderHistory có tồn tại không để tránh lỗi
                if (class_exists(OrderHistory::class)) {
                    OrderHistory::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::id(), // Người thực hiện (Admin đang đăng nhập)
                        'action' => 'Update',
                        'description' => implode('. ', $actionDescription),
                    ]);
                }
            }

            return back()->with('success', 'Order updated successfully.');
        }

        return back()->with('info', 'No changes made to the order.');
    }

    /**
     * 4. CÓ THỂ THÊM: XÓA ĐƠN HÀNG (Nếu cần thiết)
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        // Chỉ cho phép xóa các đơn hàng bị hủy hoặc chưa xử lý
        if (! in_array($order->order_status, ['cancelled', 'pending'])) {
            return back()->with('error', 'Cannot delete orders that are already processing or completed.');
        }

        // Xóa các OrderItem liên quan trước (vì có foreign key)
        $order->orderItems()->delete();

        // Xóa OrderHistory liên quan
        if (class_exists(OrderHistory::class)) {
            OrderHistory::where('order_id', $order->id)->delete();
        }

        // Xóa Order
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        if (! in_array($order->order_status, ['pending', 'processing'])) {
            return back()->with('error', 'Cannot cancel order in current status.');
        }

        $order->update(['order_status' => 'cancelled']);

        OrderHistory::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'Cancel',
            'description' => 'Order cancelled by admin',
        ]);

        return back()->with('success', 'Order cancelled successfully.');
    }

    /**
     * Override order status (admin power)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,processing,completed,cancelled,shipped',
            'notes' => 'nullable|string'
        ]);

        // Record the status change in history with correct field names
        OrderHistory::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'action' => 'Status changed from ' . $order->order_status . ' to ' . $validated['order_status'],
            'description' => $validated['notes'] ?? 'Status updated by admin'
        ]);

        $order->update(['order_status' => $validated['order_status']]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated successfully.');
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with('user');

        // Apply same filters as index
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('id', 'like', "%$keyword%")
                    ->orWhere('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $filename = 'orders_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Customer', 'Email', 'Total', 'Status', 'Payment Status', 'Date']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->first_name . ' ' . $order->last_name,
                    $order->email,
                    $order->total,
                    $order->order_status,
                    $order->payment_status,
                    $order->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
