<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value','key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'price_per_km' => 'nullable|integer|min:0',
            'admin_fee_percent' => 'nullable|integer|min:0|max:100',
            'max_radius_km' => 'nullable|numeric|min:0',
            'mpedia_api_key' => 'nullable|string',
            'mpedia_sender' => 'nullable|string',

            // Duitku payment gateway
            'duitku_merchant_code' => 'nullable|string',
            'duitku_merchant_key' => 'nullable|string',
            'duitku_api_url' => 'nullable|url',
            'duitku_is_test_mode' => 'nullable',
        ]);

        foreach (['price_per_km','admin_fee_percent','max_radius_km','mpedia_api_key','mpedia_sender','duitku_merchant_code','duitku_merchant_key','duitku_api_url','duitku_is_test_mode'] as $k) {
            if (array_key_exists($k, $data)) {
                Setting::setValue($k, (string)$data[$k]);
            }
        }

        return redirect()->back()->with('success','Settings saved');
    }
}