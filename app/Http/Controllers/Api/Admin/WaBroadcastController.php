<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaBroadcast;
use App\Models\WaBroadcastLog;
use App\Models\User;
use App\Services\MpediaWaService;

class WaBroadcastController extends Controller
{
    public function index()
    {
        return response()->json(WaBroadcast::orderBy('created_at','desc')->paginate(20));
    }

    public function store(Request $request, MpediaWaService $mpedia)
    {
        $data = $request->validate(['title'=>'required','message'=>'required','target'=>'in:all,customer,mitra,driver']);
        $broadcast = WaBroadcast::create($data + ['status'=>'pending']);

        $query = User::query();
        if ($data['target'] === 'customer') $query->where('role','user');
        if ($data['target'] === 'mitra') $query->where('role','mitra');
        if ($data['target'] === 'driver') $query->where('role','driver');
        $users = $query->get();

        $allOk = true;
        foreach($users as $u) {
            $phone = $u->phone ?? null;
            if (! $phone) continue;
            $res = $mpedia->send($phone, $data['message']);
            $status = $res['status'] === 'sent' ? 'sent' : 'failed';
            WaBroadcastLog::create(['wa_broadcast_id'=>$broadcast->id,'user_id'=>$u->id,'phone'=>$phone,'status'=>$status,'response'=>json_encode($res['response'])]);
            if ($status !== 'sent') $allOk = false;
        }

        $broadcast->status = $allOk ? 'sent' : 'failed';
        $broadcast->save();

        app(\App\Services\SystemLogService::class)->log(auth()->id(), 'wa_broadcast', "Broadcast {$broadcast->id} created and processed");

        return response()->json(['message'=>'Broadcast processed','broadcast'=>$broadcast]);
    }
}