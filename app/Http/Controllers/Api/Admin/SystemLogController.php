<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;

class SystemLogController extends Controller
{
    public function index()
    {
        return response()->json(SystemLog::with('user')->orderBy('created_at','desc')->paginate(20));
    }
}