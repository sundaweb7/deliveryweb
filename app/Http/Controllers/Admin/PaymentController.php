<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('admin.payments.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start', 0);
        $length = (int)$request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? null;

        $query = Payment::with('order')->orderBy('created_at','desc');
        $total = $query->count();

        if ($search) {
            $query->where('reference','like','%'.$search.'%')->orWhere('method','like','%'.$search.'%');
        }

        $filtered = $query->count();
        $rows = $query->offset($start)->limit($length)->get();

        $data = $rows->map(function($r){
            return [
                $r->order->order_no ?? '-',
                $r->reference,
                $r->method,
                number_format($r->amount,2),
                ucfirst($r->status),
                $r->created_at->format('Y-m-d H:i:s'),
                '<button class="btn btn-sm btn-show" data-id="'.$r->id.'">Detail</button>'
            ];
        });

        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$data]);
    }

    public function show($id)
    {
        $payment = Payment::with('order')->findOrFail($id);
        return response()->json($payment);
    }
}