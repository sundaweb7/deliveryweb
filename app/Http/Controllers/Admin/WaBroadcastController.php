<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WaBroadcast;
use App\Models\User;
use App\Models\WaBroadcastLog;
use App\Services\MpediaWaService;

class WaBroadcastController extends Controller
{
    public function index()
    {
        return view('admin.wa_broadcasts.index');
    }

    public function data(Request $request)
    {
        $start = (int)$request->get('start',0);
        $length = (int)$request->get('length',10);
        $q = WaBroadcast::orderBy('created_at','desc');
        $total = $q->count();
        $rows = $q->offset($start)->limit($length)->get();
        $data = $rows->map(function($r){
            return [
                $r->id,
                $r->title,
                $r->target,
                ucfirst($r->status),
                $r->created_at->format('Y-m-d H:i:s'),
                '<button class="btn btn-sm btn-send" data-id="'.$r->id.'">Send</button>'
            ];
        });
        return response()->json(['draw'=>$request->get('draw',1),'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>$data]);
    }

    public function store(Request $request, MpediaWaService $mpedia)
    {
        $data = $request->validate(['title'=>'required','message'=>'required','target'=>'in:all,customer,mitra,driver']);
        $broadcast = WaBroadcast::create($data + ['status'=>'pending']);
        // send immediately in a simple loop (sync) - in production do queue jobs
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

        // log system
        app(\App\Services\SystemLogService::class)->log(auth()->id(), 'wa_broadcast', "Broadcast {$broadcast->id} created and processed");

        return response()->json(['message'=>'Broadcast processed','broadcast'=>$broadcast]);
    }
}