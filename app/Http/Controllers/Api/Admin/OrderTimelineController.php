<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderTimelineController extends Controller
{
    public function index($orderId)
    {
        $order = Order::findOrFail($orderId);
        return response()->json($order->timelines()->orderBy('created_at','desc')->get());
    }
}