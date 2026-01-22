<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class VendorOrderScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $query, Model $model): void
    {
        if (Auth::check() && Auth::user()->role_id === 4) { // Vendor role
            $query->whereHas('orderItems.product', function ($productQuery) {
                $productQuery->where('vendor_id', Auth::id());
            });
        }
    }
}