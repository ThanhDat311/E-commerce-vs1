<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 4]); // Admin, Staff, Vendor
    }

    /**
     * Determine whether the user can view the product.
     */
    public function view(User $user, Product $product): bool
    {
        return match ($user->role_id) {
            1 => true, // Admin can view all
            2 => true, // Staff can view all
            4 => $product->vendor_id === $user->id, // Vendor can only view own products
            default => false,
        };
    }

    /**
     * Determine whether the user can create products.
     */
    public function create(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 4]); // Admin, Staff, Vendor
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        return match ($user->role_id) {
            1 => true, // Admin can update all
            2 => true, // Staff can update all
            4 => $product->vendor_id === $user->id, // Vendor can only update own products
            default => false,
        };
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        return match ($user->role_id) {
            1 => true, // Admin can delete all
            2 => false, // Staff cannot delete
            4 => $product->vendor_id === $user->id, // Vendor can only delete own products
            default => false,
        };
    }

    /**
     * Determine whether the user can restore the product.
     */
    public function restore(User $user, Product $product): bool
    {
        return $user->role_id === 1; // Only Admin
    }

    /**
     * Determine whether the user can permanently delete the product.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return $user->role_id === 1; // Only Admin
    }
}