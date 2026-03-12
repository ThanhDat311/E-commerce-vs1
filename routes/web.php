<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DealController as AdminDealController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Staff\DealController as StaffDealController;
use App\Http\Controllers\Vendor\DealController as VendorDealController;
use App\Http\Controllers\Vendor\FinanceController as VendorFinanceController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// ====================================================
// PUBLIC ROUTES (Khách vãng lai có thể truy cập)
// ====================================================

// Language switcher
Route::get('locale/{lang}', [LocaleController::class, 'setLocale'])->name('locale.switch');

// Google Auth
Route::controller(\App\Http\Controllers\Auth\GoogleController::class)->group(function () {
    Route::get('auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('auth/google/callback', 'handleGoogleCallback')->name('auth.google.callback');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

// [FIX QUAN TRỌNG] Đổi name thành 'shop.index' để khớp với Sidebar Filter
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('shop.show');

// Public Support Pages
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/help', [HelpController::class, 'index'])->name('help.index');

// Route nhận kết quả từ VNPay
Route::get('/payment/vnpay/callback', [PaymentController::class, 'vnpayCallback'])->name('payment.vnpay.callback');
Route::post('/payment/vnpay/ipn', [PaymentController::class, 'vnpayIpn'])->name('payment.vnpay.ipn');

// Search route
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Localization and Currency routes
Route::get('/lang/{lang}', function ($lang) {
    session(['locale' => $lang]);

    return redirect()->back();
})->name('lang.switch');

Route::get('/currency/{currency}', function ($currency) {
    session(['currency' => $currency]);

    return redirect()->back();
})->name('currency.switch');

// User Contact us
Route::get('/contact-us', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact-us', [ContactController::class, 'store'])->name('contact.store');

Route::get('/help-support', [HelpController::class, 'index'])->name('help.index');

// Wishlist routes (placeholder)
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{productId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// ====================================================
// CART ROUTES (Giỏ hàng) - Dòng 36 bắt đầu ở đây
// ====================================================
Route::middleware(['auth'])->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::get('/add/{id}', [CartController::class, 'addToCart'])->name('add');
    Route::get('/remove/{id}', [CartController::class, 'removeFromCart'])->name('remove');
    Route::patch('/update/{id}', [CartController::class, 'updateCart'])->name('update');
    Route::get('/clear', [CartController::class, 'clearCart'])->name('clear');
    Route::get('/checkout', function () {
        return redirect()->route('checkout.index');
    })->name('checkout');
    Route::post('/place-order', [CheckoutController::class, 'process'])->name('placeOrder');
});

// ====================================================
// GUEST CHECKOUT ROUTES
// ====================================================
Route::group(['prefix' => 'checkout', 'as' => 'checkout.'], function () {
    Route::get('/', [CheckoutController::class, 'show'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
});

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

    // Product Reviews
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// ====================================================
// ADMIN ROUTES (Quyền Admin - role_id = 1)
// ====================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // ... Các route Resource khác (products, categories, users)
    Route::resource('products', AdminProductController::class);
    Route::delete('products/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::resource('categories', AdminCategoryController::class)->except(['create']);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // User Management - Additional Routes
    Route::patch('users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle_status');
    Route::patch('users/{user}/update-role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.update_role');
    Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset_password');
    Route::post('users/{user}/force-logout', [\App\Http\Controllers\Admin\UserController::class, 'forceLogout'])->name('users.force_logout');

    // Vendor Management
    Route::controller(\App\Http\Controllers\Admin\VendorController::class)
        ->prefix('vendors')
        ->name('vendors.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{vendor}', 'show')->name('show');
            Route::post('/{vendor}/toggle-status', 'toggleStatus')->name('toggle-status');
            Route::post('/{vendor}/commission', 'updateCommission')->name('commission.update');
        });

    // System Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Customer Support
    Route::controller(\App\Http\Controllers\Admin\SupportController::class)
        ->prefix('support')->name('support.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{ticket}', 'show')->name('show');
            Route::post('/{ticket}/reply', 'storeMessage')->name('reply');
            Route::patch('/{ticket}', 'update')->name('update');
        });

    // ====================================================
    // [START] THÊM ĐOẠN NÀY VÀO ĐÂY
    // ====================================================
    Route::controller(\App\Http\Controllers\Admin\ReportController::class)
        ->prefix('reports')
        ->name('reports.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/revenue', 'revenue')->name('revenue');
            Route::get('/top-products', 'topProducts')->name('top_products');
            Route::get('/low-stock', 'lowStock')->name('low_stock');
            Route::get('/export-csv', 'exportCsv')->name('export_csv');
            Route::get('/export-pdf', 'exportPdf')->name('export_pdf');
        });

    // Orders Management
    Route::resource('orders', AdminOrderController::class);
    Route::post('orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{order}/override-status', [AdminOrderController::class, 'overrideStatus'])->name('orders.override_status');
    Route::get('orders-export', [AdminOrderController::class, 'export'])->name('orders.export');

    // Disputes Management
    Route::resource('disputes', \App\Http\Controllers\Admin\DisputeController::class)->only(['index', 'show']);
    Route::post('disputes/{dispute}/review', [\App\Http\Controllers\Admin\DisputeController::class, 'review'])->name('disputes.review');
    Route::post('disputes/{dispute}/resolve', [\App\Http\Controllers\Admin\DisputeController::class, 'resolve'])->name('disputes.resolve');
    Route::post('disputes/{dispute}/reject', [\App\Http\Controllers\Admin\DisputeController::class, 'reject'])->name('disputes.reject');

    // Finance Management
    Route::controller(\App\Http\Controllers\Admin\FinanceController::class)
        ->prefix('finance')
        ->name('finance.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/commission', 'updateCommissionRate')->name('commission.update');
            Route::get('/export', 'exportReport')->name('export');
        });

    // Audit Logs Management
    Route::controller(\App\Http\Controllers\AuditLogController::class)
        ->prefix('audit-logs')
        ->name('audit-logs.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/model/history', 'modelHistory')->name('model-history');
            Route::get('/statistics', 'statistics')->name('statistics');
            Route::get('/export', 'export')->name('export');
            Route::get('/{auditLog}', 'show')->name('show');
        });

    // AI Management Group
    Route::prefix('ai')
        ->name('ai.')
        ->group(function () {
            // Dashboard
            Route::controller(\App\Http\Controllers\Admin\AiController::class)
                ->prefix('dashboard')
                ->name('dashboard.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                });

            // Settings
            Route::controller(\App\Http\Controllers\Admin\AiSettingController::class)
                ->prefix('settings')
                ->name('settings.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'update')->name('update');
                    Route::post('/test-connection', 'testConnection')->name('test');
                });

            // Price Suggestions
            Route::controller(\App\Http\Controllers\Admin\PriceSuggestionController::class)
                ->prefix('price-suggestions')
                ->name('price-suggestions.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/{suggestion}/approve', 'approve')->name('approve');
                    Route::post('/{suggestion}/reject', 'reject')->name('reject');
                });

            // Risk Rules
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

            // Login Risk
            Route::controller(\App\Http\Controllers\Admin\LoginRiskController::class)
                ->prefix('login-risk')
                ->name('login-risk.')
                ->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::get('/{loginRisk}', 'show')->name('show');
                });
        });

    // Analytics Management
    Route::get('/analytics', [\App\Http\Controllers\RevenueAnalyticsController::class, 'index'])->name('analytics.index');

    // Low Stock Alerts
    Route::controller(\App\Http\Controllers\LowStockAlertsController::class)
        ->prefix('low-stock-alerts')
        ->name('low-stock-alerts.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
        });

    // Deals Management
    Route::resource('deals', AdminDealController::class);
    Route::post('deals/{deal}/approve', [AdminDealController::class, 'approve'])->name('deals.approve');
    Route::patch('deals/{deal}/toggle-status', [AdminDealController::class, 'toggleStatus'])->name('deals.toggle_status');
});

// ====================================================
// STAFF ROUTES (Quyền Staff - role_id = 2)
// ====================================================
Route::prefix('staff')->name('staff.')->middleware(['auth', 'role.check:staff'])->group(function () {

    Route::get('/', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');

    // Limited product management (no destroy)
    Route::resource('products', \App\Http\Controllers\Staff\ProductController::class)->except(['destroy']);
    Route::delete('products/images/{image}', [\App\Http\Controllers\Staff\ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::resource('categories', \App\Http\Controllers\Staff\CategoryController::class)->except(['create', 'destroy']);

    // Order management
    Route::controller(\App\Http\Controllers\Staff\OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
        Route::match(['put', 'patch'], '/{id}/status', 'update')->name('updateStatus');
    });

    // Support Ticket management
    Route::resource('support', \App\Http\Controllers\Staff\SupportController::class)->except(['create', 'edit', 'destroy']);
    Route::post('support/{support}/reply', [\App\Http\Controllers\Staff\SupportController::class, 'storeMessage'])->name('support.reply');

    // Deals (limited – no delete, no approve)
    Route::get('deals', [StaffDealController::class, 'index'])->name('deals.index');
    Route::get('deals/{deal}/edit', [StaffDealController::class, 'edit'])->name('deals.edit');
    Route::put('deals/{deal}', [StaffDealController::class, 'update'])->name('deals.update');
    Route::patch('deals/{deal}/toggle-status', [StaffDealController::class, 'toggleStatus'])->name('deals.toggle_status');
});

// ====================================================
// VENDOR ROUTES (Quyền Vendor - role_id = 4)
// ====================================================
Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'role.check:vendor'])->group(function () {

    // Dashboard
    Route::get('/', [\App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');

    // Only their own products
    Route::resource('products', \App\Http\Controllers\Vendor\ProductController::class);

    // Orders containing their products
    Route::controller(\App\Http\Controllers\Vendor\OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{id}/status', 'updateStatus')->name('updateStatus');
    });

    // Vendor Deals (own deals only, status=pending on create)
    Route::resource('deals', VendorDealController::class);

    // Vendor Finance & Payouts
    Route::get('/finance', [VendorFinanceController::class, 'index'])->name('finance.index');
});

// Address Management Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('my-addresses')->name('addresses.')->group(function () {
        Route::get('/', [App\Http\Controllers\Customer\AddressController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Customer\AddressController::class, 'store'])->name('store');
        Route::put('/{address}', [App\Http\Controllers\Customer\AddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [App\Http\Controllers\Customer\AddressController::class, 'destroy'])->name('destroy');
        Route::post('/{address}/default', [App\Http\Controllers\Customer\AddressController::class, 'setDefault'])->name('default');
    });

    // Payment Methods
    Route::prefix('my-payment-methods')->name('payment-methods.')->group(function () {
        Route::get('/', [App\Http\Controllers\Customer\PaymentMethodController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Customer\PaymentMethodController::class, 'store'])->name('store');
        Route::put('/{paymentMethod}', [App\Http\Controllers\Customer\PaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{paymentMethod}', [App\Http\Controllers\Customer\PaymentMethodController::class, 'destroy'])->name('destroy');
        Route::post('/{paymentMethod}/default', [App\Http\Controllers\Customer\PaymentMethodController::class, 'setDefault'])->name('default');
    });

    // Notification Settings
    Route::get('/my-notifications', [App\Http\Controllers\Customer\NotificationController::class, 'settings'])->name('notifications.settings');
    Route::post('/my-notifications', [App\Http\Controllers\Customer\NotificationController::class, 'updateSettings'])->name('notifications.update');

    // Account Security
    Route::get('/account-security', [App\Http\Controllers\Customer\AccountSecurityController::class, 'index'])->name('security.index');
    Route::post('/account-security/password', [App\Http\Controllers\Customer\AccountSecurityController::class, 'updatePassword'])->name('security.password');

    // Customer Support Tickets
    Route::prefix('my-tickets')->name('tickets.')->group(function () {
        Route::get('/', [App\Http\Controllers\Customer\TicketController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Customer\TicketController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Customer\TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [App\Http\Controllers\Customer\TicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/messages', [App\Http\Controllers\Customer\TicketController::class, 'storeMessage'])->name('messages.store');
    });

    // User Orders Actions
    Route::post('/my-orders/{order}/cancel', \App\Http\Controllers\Customer\OrderCancellationController::class)->name('orders.cancel');
    Route::post('/my-orders/{order}/repay', \App\Http\Controllers\Customer\OrderRepaymentController::class)->name('orders.repay');
});

// Fallback for Boost browser logs GET requests to prevent MethodNotAllowedHttpException
Route::get('/_boost/browser-logs', function () {
    return response()->noContent();
});
