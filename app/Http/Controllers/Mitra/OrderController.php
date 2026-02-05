<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return view('mitra.orders.index');
    }

    public function data(Request $request)
    {
        $mitra = $request->user()->mitra;
        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $search = $request->get('search',[])['value'] ?? null;

        $query = Order::where('mitra_id',$mitra->id)->with('user');
        $total = $query->count();
        if ($search) {
            $query->where('id','like','%'.$search.'%');
        }
        $filtered = $query->count();
        $rows = $query->offset($start)->limit($length)->get();

        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->user->name ?? '-',
                $r->status,
                $r->total_price,
                $r->created_at->toDateTimeString(),
                '<a class="btn btn-sm" href="'.url('mitra/orders/'.$r->id).'">View</a>'
            ];
        });

        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$data]);
    }

    public function show($id)
    {
        $mitra = auth()->user()->mitra;
        $order = Order::where('mitra_id',$mitra->id)->findOrFail($id);
        return view('mitra.orders.show', compact('order'));
    }
}
