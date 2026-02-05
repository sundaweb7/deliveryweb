<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProfitService;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    public function index()
    {
        return view('admin.profit.index');
    }

    public function apiGetProfit(Request $request)
    {
        $range = $request->get('range','all');
        $service = new ProfitService();
        $value = $service->getAdminProfit($range);
        return response()->json(['range'=>$range,'profit'=>$value]);
    }
}