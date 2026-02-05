<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $wallet = Wallet::where('owner_id', $user->id)->where('owner_type','App\\Models\\User')->first();
        return view('customer.wallet.index', compact('wallet'));
    }
}
