<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Wallet;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $ordersCount = Order::where('customer_id', $user->id)->count();
        $pending = Order::where('customer_id', $user->id)->whereIn('status',['pending','paid'])->count();
        $delivered = Order::where('customer_id', $user->id)->where('status','delivered')->count();
        $wallet = Wallet::where('owner_id', $user->id)->where('owner_type','App\\Models\\User')->first();
        return view('customer.dashboard', compact('ordersCount','pending','delivered','wallet'));
    }
}
