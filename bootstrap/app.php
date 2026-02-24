<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\Currency;
use App\Http\Middleware\Localization;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__ . '/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            \Illuminate\Support\Facades\RateLimiter::for('api', function (\Illuminate\Http\Request $request) {
                return \Illuminate\Cache\RateLimiting\Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
            });

            \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
                return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip());
            });

            \Illuminate\Support\Facades\RateLimiter::for('checkout', function (\Illuminate\Http\Request $request) {
                return \Illuminate\Cache\RateLimiting\Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
            });
        },
    )
    ->withSchedule(function ($schedule) {
        $schedule->command('pricing:generate-suggestions')->dailyAt('02:00');
    })
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'permission' => CheckPermission::class,
            'admin' => AdminMiddleware::class,
            'role' => CheckRole::class,
            'role.check' => RoleMiddleware::class,
            'localization' => Localization::class,
            'currency' => Currency::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
