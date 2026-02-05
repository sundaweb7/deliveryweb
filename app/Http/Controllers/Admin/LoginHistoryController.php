<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginHistory;

class LoginHistoryController extends Controller
{
    public function index()
    {
        return view('admin.login_histories.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $q = LoginHistory::with('user')->orderBy('created_at','desc');
        $total = $q->count();
        $rows = $q->offset($start)->limit($length)->get();
        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->user ? $r->user->email : '-',
                $r->role,
                $r->ip,
                $r->user_agent,
                $r->created_at->format('Y-m-d H:i:s')
            ];
        });
        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]);
    }
}
