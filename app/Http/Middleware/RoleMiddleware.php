<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = $request->user();
        $allowed = array_map('trim', explode(',', $roles));
        if (! $user || ! in_array($user->role, $allowed)) {
            // For API / AJAX requests respond JSON
            if ($request->expectsJson()) {
                return response()->json(['message'=>'Unauthorized. Role '.implode(',', $allowed).' required.'], 403);
            }
            // For web, redirect to appropriate login page if available
            if (in_array('admin', $allowed) && \Illuminate\Support\Facades\Route::has('admin.login')) {
                return redirect()->route('admin.login');
            }
            if (in_array('mitra', $allowed) && \Illuminate\Support\Facades\Route::has('mitra.login')) {
                return redirect()->route('mitra.login');
            }
            // fallback to normal login
            if (\Illuminate\Support\Facades\Route::has('login')) {
                return redirect()->route('login');
            }
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}
