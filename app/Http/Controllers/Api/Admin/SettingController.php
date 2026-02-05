<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(Setting::all()->pluck('value','key'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'admin_fee_percent'=>'nullable|numeric',
            'price_per_km'=>'nullable|numeric',
            'max_radius_km'=>'nullable|numeric'
        ]);
        foreach ($data as $key => $val) {
            Setting::updateOrCreate(['key'=>$key], ['value'=>$val]);
        }
        return response()->json(['message'=>'updated']);
    }
}
