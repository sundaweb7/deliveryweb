<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Wallet;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver;
        $assigned = 0; $onDelivery = 0; $delivered = 0; $wallet = null;
        if ($driver) {
            $assigned = Order::where('driver_id', $driver->id)->count();
            $onDelivery = Order::where('driver_id', $driver->id)->where('status','on_delivery')->count();
            $delivered = Order::where('driver_id', $driver->id)->where('status','delivered')->count();
            $wallet = Wallet::where('owner_id', $driver->id)->where('owner_type', 'App\\Models\\Driver')->first();
        }
        return view('driver.dashboard', compact('assigned','onDelivery','delivered','wallet'));
    }
}
