<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promo;

class PromoController extends Controller
{
    public function index()
    {
        return view('admin.promos.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $q = Promo::orderBy('created_at','desc');
        $total = $q->count();
        $rows = $q->offset($start)->limit($length)->get();
        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->code,
                $r->title,
                $r->type,
                number_format($r->value,2),
                $r->usage_limit,
                $r->used_count,
                ucfirst($r->status),
                '<button class="btn btn-sm btn-edit" data-id="'.$r->id.'">Edit</button> <button class="btn btn-sm btn-delete" data-id="'.$r->id.'">Delete</button>'
            ];
        });
        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]);
    }

    public function show($id)
    {
        return Promo::findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'=>'required|unique:promos,code',
            'title'=>'required',
            'type'=>'required|in:percent,fixed',
            'value'=>'required|numeric|min:0',
            'min_order'=>'nullable|numeric|min:0',
            'max_discount'=>'nullable|numeric|min:0',
            'start_date'=>'nullable|date',
            'end_date'=>'nullable|date',
            'usage_limit'=>'nullable|integer|min:0',
            'status'=>'in:active,inactive'
        ]);
        $promo = Promo::create($data);
        return response()->json(['message'=>'Promo created','promo'=>$promo]);
    }

    public function update(Request $request, $id)
    {
        $promo = Promo::findOrFail($id);
        $data = $request->validate([
            'title'=>'required',
            'type'=>'required|in:percent,fixed',
            'value'=>'required|numeric|min:0',
            'min_order'=>'nullable|numeric|min:0',
            'max_discount'=>'nullable|numeric|min:0',
            'start_date'=>'nullable|date',
            'end_date'=>'nullable|date',
            'usage_limit'=>'nullable|integer|min:0',
            'status'=>'in:active,inactive'
        ]);
        $promo->update($data);
        return response()->json(['message'=>'Promo updated','promo'=>$promo]);
    }

    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return response()->json(['message'=>'Promo deleted']);
    }
}