<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        Log::debug('AdminAuthController@login start', ['ip'=>$request->ip()]);

        $data = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        Log::debug('AdminAuthController@login after validation', ['email'=>$data['email']]);

        if (Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])) {
            Log::debug('AdminAuthController@login Auth::attempt success', ['email'=>$data['email']]);
            $user = $request->user();
            if ($user->role !== 'admin') {
                Auth::logout();
                Log::debug('AdminAuthController@login user not admin, logging out', ['user_id'=>$user->id,'role'=>$user->role]);
                return back()->withErrors(['email'=>'User bukan admin']);
            }
            $request->session()->regenerate();
            Log::debug('AdminAuthController@login session regenerated', ['user_id'=>$user->id]);

            // Log system login
            app(\App\Services\SystemLogService::class)->log($user->id, 'login', 'Admin logged in from web', $request->ip());
            Log::debug('AdminAuthController@login system log created', ['user_id'=>$user->id]);

            return redirect()->intended(route('admin.dashboard'));
        }

        Log::debug('AdminAuthController@login Auth::attempt failed', ['email'=>$data['email']]);
        return back()->withErrors(['email'=>'Login gagal']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
