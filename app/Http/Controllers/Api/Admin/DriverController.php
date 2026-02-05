<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Admin\DriverResource;

class DriverController extends Controller
{
    public function index()
    {
        return DriverResource::collection(Driver::paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'=>'required|exists:users,id',
            'vehicle_type'=>'nullable',
            'vehicle_number'=>'nullable',
        ]);
        $driver = Driver::create($data);
        return new DriverResource($driver);
    }

    public function show($id)
    {
        return new DriverResource(Driver::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $data = $request->validate([
            'vehicle_type'=>'nullable',
            'vehicle_number'=>'nullable',
            'is_active'=>'boolean'
        ]);
        $driver->update($data);
        return new DriverResource($driver);
    }

    public function destroy($id)
    {
        Driver::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
