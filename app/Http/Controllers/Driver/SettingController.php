<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return view('driver.settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:32',
            'meta' => 'nullable'
        ]);

        $user = $request->user();
        $user->update($data);
        return response()->json(['message'=>'Profile updated','user'=>$user]);
    }
}
