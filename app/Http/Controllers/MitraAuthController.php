<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('mitra.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if (Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])) {
            $user = $request->user();
            if ($user->role !== 'mitra') {
                Auth::logout();
                return back()->withErrors(['email'=>'User bukan mitra']);
            }
            $request->session()->regenerate();
            // Log system login
            app(\App\Services\SystemLogService::class)->log($user->id, 'login', 'Mitra logged in from web', $request->ip());
            return redirect()->intended(route('mitra.dashboard'));
        }

        return back()->withErrors(['email'=>'Login gagal']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('mitra.login');
    }
}
