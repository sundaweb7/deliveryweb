<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginHistory;

class RequireRecentLogin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user) return $next($request);

        $role = $user->role ?? null;
        // target roles to enforce
        $targetRoles = ['customer','mitra','driver','user'];
        if (! in_array($role, $targetRoles)) {
            return $next($request);
        }

        $loginAt = $request->session()->get('login_at');
        $now = now()->timestamp;

        // If session has no login_at, set it now (fresh session) and allow
        if (! $loginAt) {
            $request->session()->put('login_at', $now);
            return $next($request);
        }

        // If login_at within 7 days (1 minggu), refresh and allow
        // 7 days = 7 * 24 * 60 * 60 = 604800 seconds
        if (($now - $loginAt) <= 604800) {
            $request->session()->put('login_at', $now);
            return $next($request);
        }

        // Else login_at expired: check recent login history in last 7 days
        $recentLoginExists = LoginHistory::where('user_id', $user->id)
            ->where('role', $role)
            ->where('created_at', '>=', now()->subDays(7))
            ->exists();

        if ($recentLoginExists) {
            // refresh session login time and allow
            $request->session()->put('login_at', $now);
            return $next($request);
        }

        // No recent login: require re-login (log them out)
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to role-specific login
        switch ($role) {
            case 'mitra': $route = 'mitra.login'; break;
            case 'driver': $route = 'driver.login'; break;
            case 'customer':
            case 'user': default: $route = 'customer.login'; break;
        }

        if ($request->expectsJson()) {
            return response()->json(['message'=>'Session expired, please login again'], 401);
        }

        return redirect()->route($route)->with('error','Sesi Anda telah kedaluwarsa (lebih dari 1 minggu), silakan masuk ulang.');
    }
}