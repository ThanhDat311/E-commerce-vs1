<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Policies\ProductPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Product::class => ProductPolicy::class,
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate for role-based access
        Gate::define('admin', function (User $user) {
            return $user->role_id === 1;
        });

        Gate::define('vendor', function (User $user) {
            return $user->role_id === 4;
        });

        Gate::define('staff', function (User $user) {
            return $user->role_id === 2;
        });

        Gate::define('customer', function (User $user) {
            return $user->role_id === 3;
        });
    }
}
