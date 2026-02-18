<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected string $routePrefix = 'staff';

    public function index(Request $request)
    {
        $query = Order::with('user');

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

        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('pages.staff.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['orderItems.product', 'user', 'histories.user'])->findOrFail($id);

        return view('pages.staff.orders.show', [
            'order' => $order,
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $actionDescription = [];
        $hasChanges = false;

        if ($request->has('status') && $request->status != $order->order_status) {
            $oldStatus = $order->order_status;
            $newStatus = $request->status;
            $actionDescription[] = "Status changed from '{$oldStatus}' to '{$newStatus}'";
            $order->order_status = $newStatus;
            $hasChanges = true;
        }

        if ($request->has('payment_status') && $request->payment_status != $order->payment_status) {
            $oldPaymentStatus = $order->payment_status;
            $newPaymentStatus = $request->payment_status;
            $actionDescription[] = "Payment status changed from '{$oldPaymentStatus}' to '{$newPaymentStatus}'";
            $order->payment_status = $newPaymentStatus;
            $hasChanges = true;
        }

        if ($request->filled('tracking_number')) {
            if ($order->tracking_number != $request->tracking_number) {
                $actionDescription[] = "Updated tracking info: {$request->tracking_number}";
                $order->tracking_number = $request->tracking_number;
                $order->shipping_carrier = $request->shipping_carrier ?? $order->shipping_carrier;
                $hasChanges = true;
            }
        }

        if ($request->filled('admin_note')) {
            $order->admin_note = $request->admin_note;
            $hasChanges = true;
        }

        if ($hasChanges) {
            $order->save();

            if (! empty($actionDescription)) {
                if (class_exists(OrderHistory::class)) {
                    OrderHistory::create([
                        'order_id' => $order->id,
                        'user_id' => Auth::id(),
                        'action' => 'Update',
                        'description' => implode('. ', $actionDescription),
                    ]);
                }
            }

            return back()->with('success', 'Order updated successfully.');
        }

        return back()->with('info', 'No changes made to the order.');
    }
}
