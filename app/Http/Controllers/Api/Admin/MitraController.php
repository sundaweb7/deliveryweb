<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use App\Http\Resources\Api\Admin\MitraResource;

class MitraController extends Controller
{
    public function index()
    {
        return MitraResource::collection(Mitra::paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'=>'required|exists:users,id',
            'name'=>'required',
            'address'=>'nullable',
            'lat'=>'nullable|numeric',
            'lng'=>'nullable|numeric',
        ]);
        $mitra = Mitra::create($data);
        return new MitraResource($mitra);
    }

    public function show($id)
    {
        return new MitraResource(Mitra::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $mitra = Mitra::findOrFail($id);
        $data = $request->validate([
            'name'=>'sometimes|required',
            'address'=>'sometimes',
            'lat'=>'nullable|numeric',
            'lng'=>'nullable|numeric',
            'is_active'=>'boolean'
        ]);
        $mitra->update($data);
        return new MitraResource($mitra);
    }

    public function destroy($id)
    {
        Mitra::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
