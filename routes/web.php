<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// Generic login route name used by auth middleware redirection
Route::get('login', function(){
    return redirect()->route('admin.login');
})->name('login');

// Debug route (local) - shows columns in drivers table
Route::get('debug/drivers-columns', function(){
    return response()->json(\Schema::getColumnListing('drivers'));
});

// Debug route to ensure driver detail columns exist (runs Schema::table safely)
Route::get('debug/ensure-driver-columns', function(){
    try {
        $added = [];
        // First attempt Schema builder
        \Schema::table('drivers', function(\Illuminate\Database\Schema\Blueprint $table) use (&$added) {
            if (!\Schema::hasColumn('drivers','name')) { $table->string('name')->nullable()->after('user_id'); $added[]='name'; }
            if (!\Schema::hasColumn('drivers','address')) { $table->text('address')->nullable()->after('name'); $added[]='address'; }
            if (!\Schema::hasColumn('drivers','wa_contact')) { $table->string('wa_contact')->nullable()->after('address'); $added[]='wa_contact'; }
        });
        // If still missing, run raw ALTER TABLE statements
        if (!\Schema::hasColumn('drivers','name')) {
            try { \DB::statement("ALTER TABLE `drivers` ADD `name` VARCHAR(255) NULL AFTER `user_id`"); $added[]='name_raw'; } catch (\Throwable $e) { /* ignore */ }
        }
        if (!\Schema::hasColumn('drivers','address')) {
            try { \DB::statement("ALTER TABLE `drivers` ADD `address` TEXT NULL AFTER `name`"); $added[]='address_raw'; } catch (\Throwable $e) { /* ignore */ }
        }
        if (!\Schema::hasColumn('drivers','wa_contact')) {
            try { \DB::statement("ALTER TABLE `drivers` ADD `wa_contact` VARCHAR(50) NULL AFTER `address`"); $added[]='wa_contact_raw'; } catch (\Throwable $e) { /* ignore */ }
        }

        return response()->json(['status'=>'ok','added'=>$added,'columns'=>\Schema::getColumnListing('drivers')]);
    } catch (\Throwable $e) {
        return response()->json(['status'=>'error','message'=>$e->getMessage(),'trace'=>$e->getTraceAsString()],500);
    }
});

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');

});

// Mitra routes (web)
Route::prefix('mitra')->group(function(){
    Route::get('login', [\App\Http\Controllers\MitraAuthController::class, 'showLoginForm'])->name('mitra.login');
    Route::post('login', [\App\Http\Controllers\MitraAuthController::class, 'login'])->name('mitra.login.post');

    Route::middleware(['auth', \App\Http\Middleware\RequireRecentLogin::class, \App\Http\Middleware\RoleMiddleware::class . ':mitra'])->group(function(){
        Route::get('logout', [\App\Http\Controllers\MitraAuthController::class, 'logout'])->name('mitra.logout.get');
        Route::post('logout', [\App\Http\Controllers\MitraAuthController::class, 'logout'])->name('mitra.logout');
        Route::get('/', [\App\Http\Controllers\Mitra\DashboardController::class, 'index'])->name('mitra.dashboard');

        // Products
        Route::get('products', [\App\Http\Controllers\Mitra\ProductController::class, 'index'])->name('mitra.products.index');
        Route::get('products/data', [\App\Http\Controllers\Mitra\ProductController::class, 'data'])->name('mitra.products.data');
        Route::post('products', [\App\Http\Controllers\Mitra\ProductController::class, 'store']);
        Route::get('products/{product}', [\App\Http\Controllers\Mitra\ProductController::class, 'show']);
        Route::put('products/{product}', [\App\Http\Controllers\Mitra\ProductController::class, 'update']);
        Route::delete('products/{product}', [\App\Http\Controllers\Mitra\ProductController::class, 'destroy']);

        // Orders
        Route::get('orders', [\App\Http\Controllers\Mitra\OrderController::class, 'index'])->name('mitra.orders.index');
        Route::get('orders/data', [\App\Http\Controllers\Mitra\OrderController::class, 'data'])->name('mitra.orders.data');
        Route::get('orders/{order}', [\App\Http\Controllers\Mitra\OrderController::class, 'show']);

        // Wallet
        Route::get('wallet', [\App\Http\Controllers\Mitra\WalletController::class, 'index'])->name('mitra.wallet.index');

        // Settings
        Route::get('settings', [\App\Http\Controllers\Mitra\SettingController::class, 'index'])->name('mitra.settings.index');
        Route::post('settings', [\App\Http\Controllers\Mitra\SettingController::class, 'update'])->name('mitra.settings.update');
    });
});

// Driver routes (web) — top-level so login is accessible
Route::prefix('driver')->group(function(){
    Route::get('login', [\App\Http\Controllers\DriverAuthController::class, 'showLoginForm'])->name('driver.login');
    Route::post('login', [\App\Http\Controllers\DriverAuthController::class, 'login'])->name('driver.login.post');

    Route::middleware(['auth', \App\Http\Middleware\RequireRecentLogin::class, \App\Http\Middleware\RoleMiddleware::class . ':driver'])->group(function(){
        Route::get('logout', [\App\Http\Controllers\DriverAuthController::class, 'logout'])->name('driver.logout.get');
        Route::post('logout', [\App\Http\Controllers\DriverAuthController::class, 'logout'])->name('driver.logout');
        Route::get('/', [\App\Http\Controllers\Driver\DashboardController::class, 'index'])->name('driver.dashboard');

        Route::get('orders', [\App\Http\Controllers\Driver\OrderController::class, 'index'])->name('driver.orders.index');
        Route::get('orders/data', [\App\Http\Controllers\Driver\OrderController::class, 'data'])->name('driver.orders.data');
        Route::get('orders/{order}', [\App\Http\Controllers\Driver\OrderController::class, 'show']);
        Route::post('orders/{order}/mark-delivered', [\App\Http\Controllers\Driver\OrderController::class, 'markDelivered']);

        Route::get('wallet', [\App\Http\Controllers\Driver\WalletController::class, 'index'])->name('driver.wallet.index');

        Route::get('settings', [\App\Http\Controllers\Driver\SettingController::class, 'index'])->name('driver.settings.index');
        Route::post('settings', [\App\Http\Controllers\Driver\SettingController::class, 'update'])->name('driver.settings.update');
    });
});

// Customer routes (web) — top-level for customer login + member pages
Route::prefix('customer')->group(function(){
    Route::get('login', [\App\Http\Controllers\CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('login', [\App\Http\Controllers\CustomerAuthController::class, 'login'])->name('customer.login.post');

    Route::middleware(['auth', \App\Http\Middleware\RequireRecentLogin::class, \App\Http\Middleware\RoleMiddleware::class . ':customer'])->group(function(){
        Route::get('logout', [\App\Http\Controllers\CustomerAuthController::class, 'logout'])->name('customer.logout.get');
        Route::post('logout', [\App\Http\Controllers\CustomerAuthController::class, 'logout'])->name('customer.logout');
        Route::get('/', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('customer.dashboard');

        // Customer payment initiation (web endpoint used by mobile tests)
        Route::post('payments/duitku', [\App\Http\Controllers\Api\Mobile\PaymentController::class, 'createDuitkuPayment'])->name('customer.payments.duitku');

        Route::get('wallet', [\App\Http\Controllers\Customer\WalletController::class, 'index'])->name('customer.wallet.index');

        Route::get('settings', [\App\Http\Controllers\Customer\SettingController::class, 'index'])->name('customer.settings.index');
        Route::post('settings', [\App\Http\Controllers\Customer\SettingController::class, 'update'])->name('customer.settings.update');
    });
});

// Re-open admin area routes (kept below for clarity)
Route::post('payment/duitku/callback', [\App\Http\Controllers\Api\Admin\PaymentCallbackController::class, 'handle']);

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');

    Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])->group(function () {
        Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin.logout.get');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');



        // Web CRUD pages
        Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');

        // Payment callback (public) - duplicate for local testing
        Route::post('payment/duitku/callback', [\App\Http\Controllers\Api\Admin\PaymentCallbackController::class, 'handle']);
        Route::get('users/data', [AdminUserController::class, 'data'])->name('admin.users.data');
        Route::post('users', [AdminUserController::class, 'store']);
        Route::get('users/{user}', [AdminUserController::class, 'show']);
        Route::put('users/{user}', [AdminUserController::class, 'update']);
        Route::delete('users/{user}', [AdminUserController::class, 'destroy']);
        Route::get('users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');

        // Mitras / Drivers / Products / Orders / Settings pages
        Route::get('mitras', [\App\Http\Controllers\Admin\MitraController::class, 'index'])->name('admin.mitras.index');
        Route::get('mitras/data', [\App\Http\Controllers\Admin\MitraController::class, 'data'])->name('admin.mitras.data');
        Route::post('mitras', [\App\Http\Controllers\Admin\MitraController::class, 'store']);
        Route::get('mitras/{mitra}', [\App\Http\Controllers\Admin\MitraController::class, 'show']);
        Route::put('mitras/{mitra}', [\App\Http\Controllers\Admin\MitraController::class, 'update']);
        Route::delete('mitras/{mitra}', [\App\Http\Controllers\Admin\MitraController::class, 'destroy']);

        Route::get('drivers', [\App\Http\Controllers\Admin\DriverController::class, 'index'])->name('admin.drivers.index');
        Route::get('drivers/data', [\App\Http\Controllers\Admin\DriverController::class, 'data'])->name('admin.drivers.data');
        Route::post('drivers', [\App\Http\Controllers\Admin\DriverController::class, 'store']);
        Route::get('drivers/{driver}', [\App\Http\Controllers\Admin\DriverController::class, 'show']);
        Route::put('drivers/{driver}', [\App\Http\Controllers\Admin\DriverController::class, 'update']);
        Route::delete('drivers/{driver}', [\App\Http\Controllers\Admin\DriverController::class, 'destroy']);

        Route::get('products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
        Route::get('products/data', [\App\Http\Controllers\Admin\ProductController::class, 'data'])->name('admin.products.data');
        Route::post('products', [\App\Http\Controllers\Admin\ProductController::class, 'store']);
        Route::get('products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show']);
        Route::put('products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update']);
        Route::delete('products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy']);

        // Categories
        Route::get('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('categories/data', [\App\Http\Controllers\Admin\CategoryController::class, 'data'])->name('admin.categories.data');
        Route::post('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store']);
        Route::get('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'show']);
        Route::put('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update']);
        Route::delete('categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy']);

        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('orders/data', [\App\Http\Controllers\Admin\OrderController::class, 'data'])->name('admin.orders.data');
        Route::post('orders/{order}/assign-driver', [\App\Http\Controllers\Admin\OrderController::class, 'assignDriver']);

        // Payments
        Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('admin.payments.index');
        Route::get('payments/data', [\App\Http\Controllers\Admin\PaymentController::class, 'data'])->name('admin.payments.data');
        Route::get('payments/{payment}', [\App\Http\Controllers\Admin\PaymentController::class, 'show']);

        // Wallets
        Route::get('wallets', [\App\Http\Controllers\Admin\WalletController::class, 'index'])->name('admin.wallets.index');
        Route::get('wallets/data', [\App\Http\Controllers\Admin\WalletController::class, 'data'])->name('admin.wallets.data');
        Route::get('wallets/{id}', [\App\Http\Controllers\Admin\WalletController::class, 'show']);
        Route::get('wallets/{id}/histories', [\App\Http\Controllers\Admin\WalletController::class, 'histories']);

        // Promos
        Route::get('promos', [\App\Http\Controllers\Admin\PromoController::class, 'index'])->name('admin.promos.index');
        Route::get('promos/data', [\App\Http\Controllers\Admin\PromoController::class, 'data'])->name('admin.promos.data');
        Route::post('promos', [\App\Http\Controllers\Admin\PromoController::class, 'store']);
        Route::get('promos/{promo}', [\App\Http\Controllers\Admin\PromoController::class, 'show']);
        Route::put('promos/{promo}', [\App\Http\Controllers\Admin\PromoController::class, 'update']);
        Route::delete('promos/{promo}', [\App\Http\Controllers\Admin\PromoController::class, 'destroy']);

        // Banners
        Route::get('banners', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('admin.banners.index');
        Route::get('banners/data', [\App\Http\Controllers\Admin\BannerController::class, 'data'])->name('admin.banners.data');
        Route::post('banners', [\App\Http\Controllers\Admin\BannerController::class, 'store']);
        Route::put('banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'update']);
        Route::delete('banners/{banner}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy']);

        // System logs
        Route::get('system-logs', [\App\Http\Controllers\Admin\SystemLogController::class, 'index'])->name('admin.system_logs.index');
        Route::get('system-logs/data', [\App\Http\Controllers\Admin\SystemLogController::class, 'data'])->name('admin.system_logs.data');

        // Login histories
        Route::get('login-histories', [\App\Http\Controllers\Admin\LoginHistoryController::class, 'index'])->name('admin.login_histories.index');
        Route::get('login-histories/data', [\App\Http\Controllers\Admin\LoginHistoryController::class, 'data'])->name('admin.login_histories.data');

        // WA Broadcasts
        Route::get('wa-broadcasts', [\App\Http\Controllers\Admin\WaBroadcastController::class, 'index'])->name('admin.wa_broadcasts.index');
        Route::get('wa-broadcasts/data', [\App\Http\Controllers\Admin\WaBroadcastController::class, 'data'])->name('admin.wa_broadcasts.data');
        Route::post('wa-broadcasts', [\App\Http\Controllers\Admin\WaBroadcastController::class, 'store']);

        // Driver tracking
        Route::get('driver-locations', [\App\Http\Controllers\Admin\DriverTrackingController::class, 'index'])->name('admin.driver_locations.index');
        Route::get('driver-locations/data', [\App\Http\Controllers\Admin\DriverTrackingController::class, 'data'])->name('admin.driver_locations.data');

        // Order timeline
        Route::get('orders/{order}/timeline', [\App\Http\Controllers\Admin\OrderTimelineController::class, 'indexOrderTimelineBlade'])->name('admin.orders.timeline');

        // Profit
        Route::get('profit', [\App\Http\Controllers\Admin\ProfitController::class, 'index'])->name('admin.profit.index');

        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    });
});