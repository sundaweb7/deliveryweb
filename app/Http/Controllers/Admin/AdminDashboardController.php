<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Mitra;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('created_at', now()->format('Y-m-d'))->count();
        $totalIncome = Order::whereIn('status',['paid','on_delivery','delivered'])->sum('subtotal');
        // Admin profit defined as sum of admin_fee where order is delivered
        $adminProfit = Order::where('status','delivered')->sum('admin_fee');
        $driversActive = Driver::where('is_active', true)->count();
        $mitraActive = Mitra::where('is_active', true)->count();

        // Totals
        $totalMitra = Mitra::count();
        $totalUsers = \App\Models\User::count();
        $totalProducts = \App\Models\Product::count();

        return view('admin.dashboard', compact('todayOrders','totalIncome','adminProfit','driversActive','mitraActive','totalMitra','totalUsers','totalProducts'));
    }
}
