<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return view('driver.orders.index');
    }

    public function data(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver;
        if (! $driver) return response()->json(['data'=>[]]);

        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $query = Order::where('driver_id', $driver->id)->orderBy('created_at','desc');
        $total = $query->count();
        $rows = $query->offset($start)->limit($length)->get();

        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->order_no,
                $r->customer ? $r->customer->name : '-',
                $r->status,
                $r->delivery_fee,
                '<button class="btn btn-sm btn-view" data-id="'.$r->id.'">View</button> <button class="btn btn-sm btn-action" data-id="'.$r->id.'" data-action="delivered">Mark Delivered</button>'
            ];
        });

        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]);
    }

    public function show($id)
    {
        return Order::with(['items.product','mitra','customer'])->findOrFail($id);
    }

    public function markDelivered(Request $request, $id)
    {
        $user = $request->user();
        $driver = $user->driver;
        $order = Order::findOrFail($id);
        if ($order->driver_id != $driver->id) return response()->json(['message'=>'Not authorized'],403);
        $order->status = 'delivered';
        $order->save();

        // distribute earnings
        $ws = app(\App\Services\WalletService::class);
        $ws->distributeEarnings($order);

        return response()->json(['message'=>'Order marked delivered','order'=>$order]);
    }
}
