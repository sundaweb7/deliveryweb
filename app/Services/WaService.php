<?php

namespace App\Services;

class WaService
{
    public function send($phone, $message)
    {
        // Abstracted: implement provider (e.g., WhatsApp Business API, Twilio) later.
        // For now, log or queue message.
        \Log::info('WA send to '.$phone, ['message'=>$message]);
        return true;
    }
}
