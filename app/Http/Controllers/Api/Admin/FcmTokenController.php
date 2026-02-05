<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FcmToken;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'token'=>'required|string'
        ]);
        $user = $request->user();
        FcmToken::updateOrCreate(['user_id'=>$user->id,'token'=>$data['token']],[ ]);
        return response()->json(['message'=>'token saved']);
    }
}
