<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet;
        if (! $wallet) {
            return response()->json(['balance'=>0,'histories'=>[]]);
        }
        return response()->json(['balance'=>$wallet->balance,'histories'=>$wallet->histories()->latest()->limit(50)->get()]);
    }
}
