<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Promo;

class PromoController extends Controller
{
    public function index()
    {
        return response()->json(Promo::orderBy('created_at','desc')->paginate(20));
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
        return response()->json($promo,201);
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
        return response()->json($promo);
    }

    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return response()->json(['message'=>'Promo deleted']);
    }
}