<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverLocation;
use Illuminate\Http\Request;

class DriverTrackingController extends Controller
{
    public function index()
    {
        return view('admin.driver_locations.index');
    }

    public function data(Request $request)
    {
        $rows = DriverLocation::with('driver')->orderBy('updated_at','desc')->limit(100)->get();
        return response()->json($rows);
    }

    // API endpoint for drivers to POST their location
    public function storeLocation(Request $request)
    {
        $request->validate(['driver_id'=>'required|exists:drivers,id','lat'=>'required','lng'=>'required']);
        $loc = DriverLocation::create(['driver_id'=>$request->driver_id,'lat'=>$request->lat,'lng'=>$request->lng]);
        return response()->json(['message'=>'Location stored','location'=>$loc]);
    }
}