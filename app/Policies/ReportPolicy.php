<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    /**
     * Determine whether the user can view sales reports.
     */
    public function viewSalesReports(User $user): bool
    {
        return in_array($user->role_id, [1, 2]); // Admin and Staff only
    }

    /**
     * Determine whether the user can view product reports.
     */
    public function viewProductReports(User $user): bool
    {
        return in_array($user->role_id, [1, 2, 4]); // Admin, Staff, Vendor
    }

    /**
     * Determine whether the user can view user behavior reports.
     */
    public function viewUserBehaviorReports(User $user): bool
    {
        return $user->role_id === 1; // Admin only
    }

    /**
     * Determine whether the user can view vendor reports.
     */
    public function viewVendorReports(User $user): bool
    {
        return $user->role_id === 1; // Admin only
    }

    /**
     * Determine whether the user can view system logs.
     */
    public function viewSystemLogs(User $user): bool
    {
        return $user->role_id === 1; // Admin only
    }

    /**
     * Determine whether the user can export reports.
     */
    public function exportReports(User $user): bool
    {
        return in_array($user->role_id, [1, 2]); // Admin and Staff only
    }
}