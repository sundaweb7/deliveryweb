<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        // If the request is for the admin area, redirect to admin login route
        if ($request->is('admin') || $request->is('admin/*')) {
            if (Route::has('admin.login')) {
                return route('admin.login');
            }
            // fallback to a safe path
            return url('/');
        }

        // If the request is for the mitra area, redirect to mitra login
        if ($request->is('mitra') || $request->is('mitra/*')) {
            if (Route::has('mitra.login')) {
                return route('mitra.login');
            }
            return url('/');
        }

        // Otherwise, if a standard 'login' route exists, use it.
        if (Route::has('login')) {
            return route('login');
        }

        // Final fallback
        return url('/');
    }
}
