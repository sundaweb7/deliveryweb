<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return view('customer.orders.index');
    }

    public function data(Request $request)
    {
        $user = $request->user();
        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $query = Order::where('customer_id', $user->id)->orderBy('created_at','desc');
        $total = $query->count();
        $rows = $query->offset($start)->limit($length)->get();
        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->order_no,
                $r->mitra ? $r->mitra->name : '-',
                $r->status,
                $r->delivery_fee + $r->subtotal,
                '<button class="btn btn-sm btn-view" data-id="'.$r->id.'">View</button>'
            ];
        });
        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]);
    }

    public function show($id)
    {
        return Order::with(['items.product','mitra','driver'])->findOrFail($id);
    }
}
