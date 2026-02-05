<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet;
        $histories = $wallet ? $wallet->histories()->orderByDesc('created_at')->limit(50)->get() : collect();
        return view('mitra.wallet.index', compact('wallet','histories'));
    }
}
