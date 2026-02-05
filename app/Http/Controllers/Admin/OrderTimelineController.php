<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTimelineController extends Controller
{
    public function show($orderId)
    {
        $order = Order::with('timelines')->findOrFail($orderId);
        return response()->json($order->timelines()->orderBy('created_at','desc')->get());
    }

    public function indexOrderTimelineBlade($orderId)
    {
        $order = Order::with('timelines')->findOrFail($orderId);
        return view('admin.order_timelines.index', compact('order'));
    }
}