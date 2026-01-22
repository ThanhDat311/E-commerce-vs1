<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VendorScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Chỉ áp dụng scope cho Vendor users
        if (auth()->check() && auth()->user()->role_id === 4) { // Vendor role
            $builder->where('vendor_id', auth()->id());
        }
    }
}