<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('customer','mitra','driver')->orderBy('created_at','desc')->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start', 0);
        $length = (int)$request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? null;

        $query = Order::with('customer','mitra','driver');
        $total = $query->count();

        if ($search) {
            $query->where('order_no','like','%'.$search.'%')->orWhereHas('customer', function($q) use ($search){ $q->where('name','like','%'.$search.'%'); });
        }

        $filtered = $query->count();
        $rows = $query->offset($start)->limit($length)->get();

        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->order_no,
                $r->customer->name ?? '—',
                $r->mitra->name ?? '—',
                $r->driver->user->name ?? '—',
                $r->status,
                '<a class="btn btn-sm" href="/admin/orders/'+$r->id+'">View</a>'
            ];
        });

        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$data]);
    }
}