<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class ProfitService
{
    public function getAdminProfit(string $range = 'all')
    {
        $query = Order::query()->where('status','delivered');

        if ($range === 'daily') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($range === 'monthly') {
            $query->whereYear('created_at', Carbon::now()->year)->whereMonth('created_at', Carbon::now()->month);
        }

        return (float) $query->sum('admin_fee');
    }
}