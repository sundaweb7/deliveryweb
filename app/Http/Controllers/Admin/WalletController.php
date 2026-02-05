<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        return view('admin.wallets.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $q = Wallet::with('owner')->orderBy('id','desc');
        $total = $q->count();

        $rows = $q->offset($start)->limit($length)->get();
        $data = $rows->map(function($r){
            return [
                'id'=>$r->id,
                'owner'=>$r->owner,
                'balance'=>$r->balance
            ];
        });
        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]);
    }

    public function show($id)
    {
        $wallet = Wallet::with('owner')->findOrFail($id);
        return response()->json($wallet);
    }

    public function histories($id)
    {
        $wallet = Wallet::findOrFail($id);
        $hist = $wallet->histories()->orderBy('created_at','desc')->get();
        return response()->json($hist);
    }
}