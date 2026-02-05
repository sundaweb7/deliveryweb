<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);

        // Ensure wallets exist for users when retrieved (idempotent)
        \App\Models\User::retrieved(function($user){
            if (!$user->wallet) {
                try { $user->wallet()->create(['balance'=>0]); } catch (\Exception $e) {}
            }
        });

        // Ensure wallet on user creation
        \App\Models\User::created(function($user){
            try { $user->wallet()->create(['balance'=>0]); } catch (\Exception $e) {}
        });

        // Record login history on Login event
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, \App\Listeners\RecordLoginHistory::class . '@handle');
    }
}
