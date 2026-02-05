<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        return response()->json(Wallet::with('owner')->paginate(20));
    }

    public function show($id)
    {
        return response()->json(Wallet::with('owner')->findOrFail($id));
    }

    public function histories($id)
    {
        $w = Wallet::findOrFail($id);
        return response()->json($w->histories()->orderBy('created_at','desc')->get());
    }
}