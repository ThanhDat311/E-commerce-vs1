<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
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

    // ==================== Order Routes ====================
    Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'history'])->name('api.orders.history');
        Route::get('{orderId}', [OrderController::class, 'detail'])->name('api.orders.detail');
        Route::get('{orderId}/summary', [OrderController::class, 'summary'])->name('api.orders.summary');
        Route::get('{orderId}/track', [OrderController::class, 'track'])->name('api.orders.track');
        Route::post('{orderId}/cancel', [OrderController::class, 'cancel'])->name('api.orders.cancel');
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
