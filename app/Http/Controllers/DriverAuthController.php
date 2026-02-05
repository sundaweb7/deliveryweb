<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('driver.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if (Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])) {
            $user = $request->user();
            if ($user->role !== 'driver') {
                Auth::logout();
                return back()->withErrors(['email'=>'User bukan driver']);
            }
            $request->session()->regenerate();
            app(\App\Services\SystemLogService::class)->log($user->id, 'login', 'Driver logged in from web', $request->ip());
            return redirect()->intended(route('driver.dashboard'));
        }

        return back()->withErrors(['email'=>'Login gagal']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('driver.login');
    }
}
