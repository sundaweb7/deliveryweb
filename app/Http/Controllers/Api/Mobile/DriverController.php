<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Order;

class DriverController extends Controller
{
    public function setOnline(\App\Http\Requests\Api\Mobile\DriverOnlineRequest $request)
    {
        $driver = $request->user()->driver;
        if (! $driver) return response()->json(['message'=>'Driver profile not found'], 404);
        $data = $request->validated();
        $driver->is_online = (bool)$data['is_online'];
        if (isset($data['lat'])) $driver->lat = $data['lat'];
        if (isset($data['lng'])) $driver->lng = $data['lng'];
        $driver->last_active_at = now();
        $driver->save();
        return response()->json(['message'=>'status updated','driver'=>$driver]);
    }

    public function acceptOrder(Request $request, $orderId)
    {
        $driver = $request->user()->driver;
        if (! $driver) return response()->json(['message'=>'Driver profile not found'], 404);
        $order = Order::findOrFail($orderId);
        if ($order->driver_id && $order->driver_id != $driver->id) {
            return response()->json(['message'=>'Order already taken'], 400);
        }
        if ($order->status !== 'paid' && $order->status !== 'accepted') {
            return response()->json(['message'=>'Order not available for accept'], 400);
        }
        $order->driver_id = $driver->id;
        $order->status = 'on_delivery';
        $order->save();

        // notify customer
        $fcm = app(\App\Services\FcmService::class);
        $fcm->sendToUser($order->customer_id, 'Driver On The Way', "Driver {$driver->user->name} sedang menuju pickup", ['order_id'=>$order->id]);

        return response()->json(['message'=>'Order accepted','order'=>$order]);
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $driver = $request->user()->driver;
        $order = Order::findOrFail($orderId);
        if ($order->driver_id != $driver->id) return response()->json(['message'=>'Not authorized'], 403);
        $data = $request->validate(['status'=>'required|in:on_delivery,delivered']);
        $order->status = $data['status'];
        $order->save();

        if ($data['status'] === 'delivered') {
            // distribute earnings
            $ws = app(\App\Services\WalletService::class);
            $ws->distributeEarnings($order);

            $fcm = app(\App\Services\FcmService::class);
            $fcm->sendToUser($order->customer_id, 'Order Delivered', "Order {$order->order_no} telah dikirim");
        }

        return response()->json(['order'=>$order]);
    }
}
