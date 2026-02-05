<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return view('mitra.settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name'=>'required',
            'phone'=>'nullable',
            'password'=>'nullable|min:6'
        ]);
        $user->name = $data['name'];
        $user->phone = $data['phone'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return back()->with('success','Profile updated');
    }
}
