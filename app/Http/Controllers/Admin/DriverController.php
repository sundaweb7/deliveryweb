<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        return view('admin.drivers.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start', 0);
        $length = (int)$request->get('length', 10);
        $search = $request->get('search', [])['value'] ?? null;

        $query = Driver::with('user');
        $total = $query->count();

        if ($search) {
            $query->whereHas('user', function($q) use ($search) { $q->where('name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%'); });
        }

        $filtered = $query->count();
        $rows = $query->offset($start)->limit($length)->get();

        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->name ?? ($r->user->name ?? '—'),
                $r->vehicle_type,
                $r->vehicle_number,
                $r->wa_contact ?? '-',
                $r->is_active ? 'Yes' : 'No',
                '<button class="btn btn-sm btn-edit" data-id="'.$r->id.'">Edit</button> <button class="btn btn-sm btn-delete" data-id="'.$r->id.'">Delete</button>'
            ];
        });

        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$filtered,'data'=>$data]);
    }

    public function store(\App\Http\Requests\Admin\DriverRequest $request)
    {
        $driver = Driver::create($request->validated());
        return response()->json(['message'=>'Driver created','driver'=>$driver]);
    }

    public function update(\App\Http\Requests\Admin\DriverRequest $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->update($request->validated());
        return response()->json(['message'=>'Driver updated','driver'=>$driver]);
    }

    public function destroy($id)
    {
        Driver::findOrFail($id)->delete();
        return response()->json(['message'=>'Driver deleted']);
    }

    public function show($id)
    {
        return Driver::with('user')->findOrFail($id);
    }
}