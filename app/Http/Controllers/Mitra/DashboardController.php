<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $mitra = $user->mitra;

        $productCount = $mitra ? $mitra->products()->count() : 0;
        $orderCount = $mitra ? $mitra->orders()->count() : 0;
        $wallet = $user->wallet ? $user->wallet->balance : 0;

        return view('mitra.dashboard', compact('productCount','orderCount','wallet'));
    }
}
