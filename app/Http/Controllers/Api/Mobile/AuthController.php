<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'=>'required',
            'email'=>'nullable|email|unique:users',
            'phone'=>'required|unique:users',
            'password'=>'required|min:6'
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'customer';
        $user = User::create($data);

        $token = $user->createToken('mobile_token')->plainTextToken;

        return response()->json(['token'=>$token,'user'=>$user]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'phone'=>'nullable',
            'email'=>'nullable|email',
            'password'=>'required'
        ]);

        $query = User::query();
        if (! empty($data['email'])) $query->where('email', $data['email']);
        if (! empty($data['phone'])) $query->where('phone', $data['phone']);

        $user = $query->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message'=>'Invalid credentials'], 401);
        }

        $token = $user->createToken('mobile_token')->plainTextToken;
        // system log
        app(\App\Services\SystemLogService::class)->log($user->id, 'login', 'Mobile login', request()->ip());
        return response()->json(['token'=>$token,'user'=>$user]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out']);
    }
}
