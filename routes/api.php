<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\BannerController as PublicBannerController;
use App\Http\Controllers\Api\Admin\MitraController;
use App\Http\Controllers\Api\Admin\DriverController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\PaymentController;
use App\Http\Controllers\Api\Admin\PaymentCallbackController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\FcmTokenController;

Route::get('customer/banners', [PublicBannerController::class, 'index']);
Route::get('categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    // Mobile routes (customer/mitra/driver) - used by Flutter apps
    Route::prefix('mobile')->group(function () {
        // Public customer banners
        Route::get('banners', [\App\Http\Controllers\Api\BannerController::class, 'index']);
        // Auth
        Route::post('register', [\App\Http\Controllers\Api\Mobile\AuthController::class, 'register'])->middleware('throttle:10,1');
        Route::post('login', [\App\Http\Controllers\Api\Mobile\AuthController::class, 'login'])->middleware('throttle:10,1');

        // Protected mobile routes
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('logout', [\App\Http\Controllers\Api\Mobile\AuthController::class, 'logout']);

            // Customer
            Route::get('mitras/nearby', [\App\Http\Controllers\Api\Mobile\CustomerController::class, 'listMitras']);
            Route::get('mitras/{mitra}/products', [\App\Http\Controllers\Api\Mobile\CustomerController::class, 'products']);
            Route::post('orders', [\App\Http\Controllers\Api\Mobile\CustomerController::class, 'createOrder']);
            Route::post('apply-promo', [\App\Http\Controllers\Api\PromoApplyController::class, 'apply']);

            // Mobile payments (customer)
            Route::post('payments/duitku', [\App\Http\Controllers\Api\Mobile\PaymentController::class, 'createDuitkuPayment'])->name('admin.mobile.payments.duitku');
            // Wallet
            Route::get('wallet', [\App\Http\Controllers\Api\Mobile\WalletController::class, 'show']);
        });
    });

    Route::middleware(['auth:sanctum', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        // CRUD Users (customer, mitra, driver)
        Route::apiResource('users', UserController::class);

        // Mitra
        Route::apiResource('mitras', MitraController::class);

        // Driver
        Route::apiResource('drivers', DriverController::class);

        // Product
        Route::apiResource('products', ProductController::class);

        // Orders
        Route::apiResource('orders', OrderController::class);
        Route::post('orders/{order}/assign-driver', [OrderController::class, 'assignDriver']);

        // Payments (initiate)
        Route::post('payments/duitku', [PaymentController::class, 'createDuitkuPayment']);

        // Mobile payments (alternate route so it is discoverable without nested group)
        Route::post('mobile/payments/duitku', [\App\Http\Controllers\Api\Mobile\PaymentController::class, 'createDuitkuPayment'])->name('admin.mobile.payments.duitku');

        // Promos
        Route::get('promos', [\App\Http\Controllers\Api\Admin\PromoController::class, 'index']);
        Route::post('promos', [\App\Http\Controllers\Api\Admin\PromoController::class, 'store']);
        Route::put('promos/{promo}', [\App\Http\Controllers\Api\Admin\PromoController::class, 'update']);
        Route::delete('promos/{promo}', [\App\Http\Controllers\Api\Admin\PromoController::class, 'destroy']);

        // Wallets
        Route::get('wallets', [\App\Http\Controllers\Api\Admin\WalletController::class, 'index']);

        // Wa Broadcasts
        Route::get('wa-broadcasts', [\App\Http\Controllers\Api\Admin\WaBroadcastController::class, 'index']);
        Route::post('wa-broadcasts', [\App\Http\Controllers\Api\Admin\WaBroadcastController::class, 'store']);

        // System logs
        Route::get('system-logs', [\App\Http\Controllers\Api\Admin\SystemLogController::class, 'index']);

        // Banners (admin CRUD)
        Route::post('banners', [\App\Http\Controllers\Admin\BannerController::class, 'store']);
        Route::put('banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'update']);
        Route::delete('banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy']);

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index']);

        // Payments (admin read)
        Route::get('payments', [\App\Http\Controllers\Api\Admin\PaymentController::class, 'index']);
        Route::get('payments/{payment}', [\App\Http\Controllers\Api\Admin\PaymentController::class, 'show']);

        // Wallets
        Route::get('wallets', [\App\Http\Controllers\Api\Admin\WalletController::class, 'index']);
        Route::get('wallets/{id}', [\App\Http\Controllers\Api\Admin\WalletController::class, 'show']);
        Route::get('wallets/{id}/histories', [\App\Http\Controllers\Api\Admin\WalletController::class, 'histories']);

        // Driver locations
        Route::get('driver-locations', [\App\Http\Controllers\Api\Admin\DriverLocationController::class, 'index']);

        // Order timeline
        Route::get('order/{order}/timeline', [\App\Http\Controllers\Api\Admin\OrderTimelineController::class, 'index']);

        // Profit
        Route::get('profit', [\App\Http\Controllers\Api\Admin\ProfitController::class, 'index']);

        // Settings
        Route::get('settings', [SettingController::class, 'index']);
        Route::post('settings', [SettingController::class, 'update']);

        // FCM Token
        Route::post('fcm/token', [FcmTokenController::class, 'store']);
    });

    // Payment callback (public) - webhook dari Duitku
    Route::post('payment/duitku/callback', [PaymentCallbackController::class, 'handle']);
});
