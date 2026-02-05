<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Driver;
use App\Models\Mitra;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $totalOrdersToday = Order::whereDate('created_at', $today)->count();
        $totalIncome = Order::whereIn('status', ['paid','on_delivery','completed'])->sum('subtotal');
        $adminProfit = Order::whereIn('status', ['paid','on_delivery','completed'])->sum('admin_fee');
        $driversActive = Driver::where('is_active', true)->count();
        $mitraActive = Mitra::where('is_active', true)->count();

        return response()->json([
            'total_orders_today'=>$totalOrdersToday,
            'total_income'=>$totalIncome,
            'admin_profit'=>$adminProfit,
            'drivers_active'=>$driversActive,
            'mitra_active'=>$mitraActive,
        ]);
    }
}
