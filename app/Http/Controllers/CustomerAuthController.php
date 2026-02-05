<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if (Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])) {
            $user = $request->user();
            if ($user->role !== 'customer') {
                Auth::logout();
                return back()->withErrors(['email'=>'User bukan customer']);
            }
            $request->session()->regenerate();
            app(\App\Services\SystemLogService::class)->log($user->id, 'login', 'Customer logged in from web', $request->ip());
            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors(['email'=>'Login gagal']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customer.login');
    }
}
