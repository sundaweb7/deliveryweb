<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $driver = $user->driver;
        $wallet = null;
        if ($driver) {
            $wallet = Wallet::where('owner_id', $driver->id)->where('owner_type','App\\Models\\Driver')->first();
        }
        return view('driver.wallet.index', compact('wallet'));
    }
}
