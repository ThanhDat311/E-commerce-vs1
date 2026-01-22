<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 4]); // Admin, Staff, Vendor
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        return match ($user->role_id) {
            1 => true, // Admin can view all orders
            2 => true, // Staff can view all orders
            3 => $order->user_id === $user->id, // Customer can view own orders
            4 => $this->vendorCanViewOrder($user, $order), // Vendor can view orders containing their products
            default => false,
        };
    }

    /**
     * Determine whether the user can create orders.
     */
    public function create(User $user): bool
    {
        return $user->role_id === 3; // Only Customer
    }

    /**
     * Determine whether the user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        return match ($user->role_id) {
            1 => true, // Admin can update all
            2 => true, // Staff can update status
            3 => $order->user_id === $user->id && in_array($order->order_status, ['pending', 'confirmed']), // Customer can update own pending orders
            default => false,
        };
    }

    /**
     * Determine whether the user can delete the order.
     */
    public function delete(User $user, Order $order): bool
    {
        return match ($user->role_id) {
            1 => true, // Admin can delete
            3 => $order->user_id === $user->id && $order->order_status === 'pending', // Customer can cancel own pending orders
            default => false,
        };
    }

    /**
     * Determine whether the user can update order status.
     */
    public function updateStatus(User $user, Order $order): bool
    {
        return in_array($user->role_id, [1, 2]); // Admin and Staff only
    }

    /**
     * Check if vendor can view the order (contains their products).
     */
    private function vendorCanViewOrder(User $user, Order $order): bool
    {
        return $order->orderItems()
            ->whereHas('product', function ($query) use ($user) {
                $query->where('vendor_id', $user->id);
            })
            ->exists();
    }
}