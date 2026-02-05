<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;

class CatchTokenMismatch
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            // Logout user if any
            try { Auth::logout(); } catch (\Throwable $err) {}
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // determine which login to redirect
            $parts = explode('/', trim($request->path(), '/'));
            $prefix = $parts[0] ?? '';

            switch ($prefix) {
                case 'admin': $route = 'admin.login'; break;
                case 'driver': $route = 'driver.login'; break;
                case 'mitra': $route = 'mitra.login'; break;
                case 'customer': $route = 'customer.login'; break;
                default: $route = 'login';
            }

            return redirect()->route($route)->withErrors(['email' => 'Session expired, silakan login kembali']);
        }
    }
}
