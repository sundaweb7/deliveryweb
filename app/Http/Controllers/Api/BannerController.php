<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index()
    {
        $b = Banner::where('status','active')->orderBy('id','desc')->get();
        return response()->json($b);
    }
}