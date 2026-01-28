<?php

use App\Http\Middleware\CheckPermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\Localization;
use App\Http\Middleware\Currency;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function ($schedule) {
        $schedule->command('pricing:generate-suggestions')->dailyAt('02:00');
    })
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'permission' => CheckPermission::class,
            'admin' => AdminMiddleware::class,
            'role' => CheckRole::class,
            'localization' => Localization::class,
            'currency' => Currency::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
