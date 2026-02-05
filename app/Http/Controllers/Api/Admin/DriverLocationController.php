<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverLocation;
use Illuminate\Http\Request;

class DriverLocationController extends Controller
{
    public function index()
    {
        $rows = DriverLocation::with('driver')->orderBy('updated_at','desc')->limit(200)->get();
        return response()->json($rows);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['driver_id'=>'required|exists:drivers,id','lat'=>'required','lng'=>'required']);
        $loc = DriverLocation::create($data);
        return response()->json($loc);
    }
}