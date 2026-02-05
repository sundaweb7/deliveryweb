<?php

namespace App\Services;

use App\Models\FcmToken;
use Illuminate\Support\Facades\Http;

class FcmService
{
    public function sendToUser($userId, $title, $body, $data = [])
    {
        $tokens = FcmToken::where('user_id', $userId)->pluck('token')->toArray();
        foreach ($tokens as $token) {
            $this->send($token, $title, $body, $data);
        }
    }

    public function send($token, $title, $body, $data = [])
    {
        $payload = [
            'to'=>$token,
            'notification'=>['title'=>$title,'body'=>$body],
            'data'=>$data
        ];

        Http::withHeaders(['Authorization'=>'key='.env('FCM_SERVER_KEY'),'Content-Type'=>'application/json'])
            ->post('https://fcm.googleapis.com/fcm/send', $payload);

        return true;
    }
}
