<?php

namespace App\Providers;

use App\Http\ViewComposers\HeaderComposer;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);

        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\UserRepositoryInterface::class, \App\Repositories\Eloquent\UserRepository::class);
        $this->app->bind(\App\Repositories\Interfaces\CategoryRepositoryInterface::class, \App\Repositories\Eloquent\CategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Auth::viaRemember(function ($user) {
            return $user->load('role.permissions');
        });

        URL::forceScheme('https');

        // Register View Composer for header
        View::composer('layouts.header', HeaderComposer::class);

        // Register View Composer for Store Navbar
        View::composer('components.store.navbar', \App\Http\ViewComposers\CategoryComposer::class);

        // Register layouts as anonymous components
        \Illuminate\Support\Facades\Blade::anonymousComponentPath(resource_path('views/layouts'), 'layouts');
    }
}
