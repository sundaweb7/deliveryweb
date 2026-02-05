<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProfitService;
use Illuminate\Http\Request;

class ProfitController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range','all');
        $service = new ProfitService();
        return response()->json(['range'=>$range,'profit'=>$service->getAdminProfit($range)]);
    }
}