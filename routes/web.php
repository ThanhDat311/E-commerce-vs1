<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

require __DIR__ . '/auth.php';

// ====================================================
// PUBLIC ROUTES (Khách vãng lai có thể truy cập)
// ====================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// [FIX QUAN TRỌNG] Đổi name thành 'shop.index' để khớp với Sidebar Filter
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{product}', [ShopController::class, 'show'])->name('product.detail');

// Route nhận kết quả từ VNPay
Route::get('/payment/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payment.vnpay.callback');

// ====================================================
// CART ROUTES (Giỏ hàng) - Dòng 36 bắt đầu ở đây
// ====================================================
Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/add/{id}', [CartController::class, 'addToCart'])->name('add');
    Route::get('/remove/{id}', [CartController::class, 'removeFromCart'])->name('remove');
    Route::patch('/update/{id}', [CartController::class, 'updateCart'])->name('update');
    Route::get('/clear', [CartController::class, 'clearCart'])->name('clear');
    Route::get('/checkout', function () {
        return redirect()->route('checkout.index');
    })->name('checkout');
    Route::post('/place-order', [CheckoutController::class, 'process'])->name('placeOrder');
}); // <--- [QUAN TRỌNG] Đã thêm đóng ngoặc kết thúc nhóm Cart tại đây

// ====================================================
// AUTHENTICATED ROUTES (Phải đăng nhập mới vào được)
// ====================================================
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // User Orders History
    Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

    // Checkout Flow
    Route::group(['prefix' => 'checkout', 'as' => 'checkout.'], function () {
        Route::get('/', [CheckoutController::class, 'show'])->name('index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('process');
        Route::get('/success', [CheckoutController::class, 'success'])->name('success');
    });
});

// ====================================================
// ADMIN ROUTES (Quyền Admin - role_id = 1)
// ====================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ... Các route Resource khác (products, categories, users)
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // ====================================================
    // [START] THÊM ĐOẠN NÀY VÀO ĐÂY
    // ====================================================
    Route::controller(\App\Http\Controllers\Admin\ReportController::class)
        ->prefix('reports')
        ->name('reports.')
        ->group(function () {
            Route::get('/revenue', 'revenue')->name('revenue');           // -> route('admin.reports.revenue')
            Route::get('/top-products', 'topProducts')->name('top_products');
            Route::get('/low-stock', 'lowStock')->name('low_stock');
        });

    // Custom Order Routes cho Admin
    Route::controller(AdminOrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
    });

    // Price Suggestions Management
    Route::controller(\App\Http\Controllers\Admin\PriceSuggestionController::class)
        ->prefix('price-suggestions')
        ->name('price-suggestions.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/{suggestion}/approve', 'approve')->name('approve');
            Route::post('/{suggestion}/reject', 'reject')->name('reject');
        });

    // Audit Logs Management
    Route::controller(\App\Http\Controllers\AuditLogController::class)
        ->prefix('audit-logs')
        ->name('audit-logs.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{auditLog}', 'show')->name('show');
            Route::get('/model/history', 'modelHistory')->name('model-history');
            Route::get('/statistics', 'statistics')->name('statistics');
            Route::get('/export', 'export')->name('export');
        });

    // Risk Rules Management
    Route::controller(\App\Http\Controllers\Admin\RiskRuleController::class)
        ->prefix('risk-rules')
        ->name('risk-rules.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{riskRule}/edit', 'edit')->name('edit');
            Route::put('/{riskRule}', 'update')->name('update');
            Route::patch('/{riskRule}/toggle', 'toggle')->name('toggle');
            Route::post('/reset', 'reset')->name('reset');
            Route::get('/statistics', 'statistics')->name('statistics');
            Route::get('/export', 'export')->name('export');
            Route::post('/import', 'import')->name('import');
        });
});

// ====================================================
// STAFF ROUTES (Quyền Staff - role_id = 2)
// ====================================================
Route::prefix('staff')->name('staff.')->middleware(['auth', 'role:staff'])->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Limited product management
    Route::resource('products', AdminProductController::class)->except(['destroy']);
    Route::resource('categories', AdminCategoryController::class)->except(['destroy']);

    // Order management
    Route::controller(AdminOrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
    });
});

// ====================================================
// VENDOR ROUTES (Quyền Vendor - role_id = 4)
// ====================================================
Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'role:vendor'])->group(function () {

    // Dashboard
    Route::get('/', [\App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');

    // Only their own products
    Route::resource('products', \App\Http\Controllers\Vendor\ProductController::class);

    // Orders containing their products
    Route::controller(\App\Http\Controllers\Vendor\OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}/status', 'updateStatus')->name('update-status');
    });
});


// Address Management Routes
Route::group(['prefix' => 'my-addresses', 'as' => 'addresses.'], function () {
    Route::get('/', [App\Http\Controllers\Customer\AddressController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\Customer\AddressController::class, 'store'])->name('store');
    Route::put('/{address}', [App\Http\Controllers\Customer\AddressController::class, 'update'])->name('update');
    Route::delete('/{address}', [App\Http\Controllers\Customer\AddressController::class, 'destroy'])->name('destroy');
    Route::post('/{address}/default', [App\Http\Controllers\Customer\AddressController::class, 'setDefault'])->name('default');
});

// ====================================================
// API ROUTES (JSON responses for admin panels)
// ====================================================
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {

    // Products API
    Route::apiResource('products', ProductController::class);

    // Orders API
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'apiIndex')->name('index');
        Route::get('/{order}', 'apiShow')->name('show');
        Route::put('/{order}/status', 'updateStatus')->name('update-status');
    });
});

// User Orders History
Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
Route::get('/my-orders/{order}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

// [NEW] Order Cancellation Route
Route::post('/my-orders/{order}/cancel', \App\Http\Controllers\Customer\OrderCancellationController::class)
    ->name('orders.cancel');

// Test route for CSRF token
Route::get('/test-csrf', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
        'session_id' => session()->getId(),
        'app_url' => config('app.url'),
        'session_domain' => config('session.domain'),
        'session_driver' => config('session.driver'),
        'session_cookie' => config('session.cookie'),
        'session_secure' => config('session.secure'),
        'session_same_site' => config('session.same_site'),
        'session_lifetime' => config('session.lifetime'),
    ]);
})->name('test-csrf');

// Debug Session Route (for troubleshooting 419 errors)
Route::get('/debug-session', function () {
    if (!config('app.debug')) {
        abort(404);
    }

    return response()->json([
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'csrf_token' => csrf_token(),
        'csrf_token_from_meta' => request()->header('X-CSRF-TOKEN'),
        'session_config' => [
            'driver' => config('session.driver'),
            'lifetime' => config('session.lifetime'),
            'cookie' => config('session.cookie'),
            'domain' => config('session.domain'),
            'secure' => config('session.secure'),
            'same_site' => config('session.same_site'),
        ],
    ]);
})->name('debug-session');
