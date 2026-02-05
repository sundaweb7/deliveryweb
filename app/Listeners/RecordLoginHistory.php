<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\LoginHistory;

class RecordLoginHistory
{
    public function handle(Login $event)
    {
        $user = $event->user;
        $req = request();

        LoginHistory::create([
            'user_id' => $user->id,
            'role' => $user->role,
            'ip' => $req->ip(),
            'user_agent' => $req->userAgent() ?? null,
        ]);

        // store login time in session so middleware can check
        if ($req->hasSession()) {
            $req->session()->put('login_at', now()->timestamp);
        }
    }
}