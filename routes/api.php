<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RecommendationController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\Vendor;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API v1 Routes
Route::prefix('v1')->group(function () {

    // ==================== Authentication Routes ====================
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('api.auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');

        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
            Route::get('me', [AuthController::class, 'me'])->name('api.auth.me');
            Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('api.auth.refresh-token');
        });
    });

    // ==================== Products ====================
    Route::get('products', [ProductController::class, 'index'])->name('api.products.index');
    Route::get('products/{slug}', [ProductController::class, 'show'])->name('api.products.show');

    // ==================== Recommendations ====================
    Route::get('recommendations/{product}', [RecommendationController::class, 'show'])->name('api.recommendations.show');

    // ==================== Search Routes ====================
    Route::prefix('search')->group(function () {
        Route::get('/', [SearchController::class, 'search'])->name('api.search.products');
        Route::get('suggestions', [SearchController::class, 'suggestions'])->name('api.search.suggestions');

        // Admin-only: requires Sanctum auth + admin role
        Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
            Route::post('reindex', [SearchController::class, 'reindex'])->name('api.search.reindex');
        });
    });

    // ==================== Profile Routes ====================
    Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('api.profile.show');
        Route::put('/', [ProfileController::class, 'update'])->name('api.profile.update');
        Route::post('password', [ProfileController::class, 'updatePassword'])->name('api.profile.update-password');

        // Address management
        Route::post('addresses', [ProfileController::class, 'addAddress'])->name('api.profile.add-address');
        Route::put('addresses/{addressId}', [ProfileController::class, 'updateAddress'])->name('api.profile.update-address');
        Route::delete('addresses/{addressId}', [ProfileController::class, 'deleteAddress'])->name('api.profile.delete-address');
    });

    // ==================== Cart Routes ====================
    Route::middleware('auth:sanctum')->prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'list'])->name('api.cart.list');
        Route::post('add', [CartController::class, 'add'])->name('api.cart.add');
        Route::put('update/{productId}', [CartController::class, 'update'])->name('api.cart.update');
        Route::delete('remove/{productId}', [CartController::class, 'remove'])->name('api.cart.remove');
        Route::delete('clear', [CartController::class, 'clear'])->name('api.cart.clear');
        Route::post('coupon', [CartController::class, 'applyCoupon'])->name('api.cart.apply-coupon');
    });

    // ==================== Coupon Routes ====================
    Route::prefix('coupons')->group(function () {
        // Public endpoint - validate coupon
        Route::post('validate', [CouponController::class, 'validateCoupon'])->name('api.coupons.validate');

        // Protected endpoints
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('history', [CouponController::class, 'history'])->name('api.coupons.history');
            Route::post('check-usage', [CouponController::class, 'checkUsage'])->name('api.coupons.check-usage');
        });
    });

    // ==================== Order Routes ====================
    Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'history'])->name('api.orders.history');
        Route::get('{orderId}', [OrderController::class, 'detail'])->name('api.orders.detail');
        Route::get('{orderId}/summary', [OrderController::class, 'summary'])->name('api.orders.summary');
        Route::get('{orderId}/track', [OrderController::class, 'track'])->name('api.orders.track');
        Route::post('{orderId}/cancel', [OrderController::class, 'cancel'])->name('api.orders.cancel');
    });

    // ==================== Vendor Routes ====================
    Route::middleware(['auth:web', 'role.check:vendor'])
        ->prefix('vendor')
        ->name('api.vendor.')
        ->group(function () {
            // Dashboard
            Route::get('dashboard', [Vendor\DashboardController::class, 'index'])->name('dashboard');

            // Products (full CRUD)
            Route::apiResource('products', Vendor\ProductController::class);

            // Orders
            Route::get('orders', [Vendor\OrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{orderId}', [Vendor\OrderController::class, 'show'])->name('orders.show');
            Route::patch('orders/{orderId}/status', [Vendor\OrderController::class, 'updateStatus'])->name('orders.update-status');

            // Finance
            Route::get('finance', [Vendor\FinanceController::class, 'index'])->name('finance.index');
        });
});

// Health check endpoint (no authentication required)
Route::get('health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'version' => 'v1',
    ]);
})->name('api.health');
