<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function index()
    {
        return view('admin.mitras.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start', 0);
        $length = (int)$request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? null;

        $query = Mitra::query();
        $total = $query->count();

        if ($search) {
            $query->where('name','like','%'.$search.'%')->orWhere('address','like','%'.$search.'%');
        }

        $filtered = $query->count();
        $rows = $query->offset($start)->limit($length)->get();

        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->name,
                $r->address,
                $r->lat,
                $r->lng,
                $r->is_active ? 'Yes' : 'No',
                '<button class="btn btn-sm btn-edit" data-id="'.$r->id.'">Edit</button> <button class="btn btn-sm btn-delete" data-id="'.$r->id.'">Delete</button>'
            ];
        });

        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$data]);
    }

    public function store(\App\Http\Requests\Admin\MitraRequest $request)
    {
        $data = $request->validated();
        $mitra = Mitra::create($data);
        return response()->json(['message'=>'Mitra created','mitra'=>$mitra]);
    }

    public function update(\App\Http\Requests\Admin\MitraRequest $request, $id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->update($request->validated());
        return response()->json(['message'=>'Mitra updated','mitra'=>$mitra]);
    }

    public function destroy($id)
    {
        $mitra = Mitra::findOrFail($id);
        $mitra->delete();
        return response()->json(['message'=>'Mitra deleted']);
    }

    public function show($id)
    {
        return Mitra::findOrFail($id);
    }
}