<?php

namespace App\Services;

use GuzzleHttp\Client;

class MpediaWaService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri'=>'https://api.mpedia.example/']); // placeholder base
    }

    public function send(string $phone, string $message): array
    {
        // Simple implementation: POST to MPEDIA endpoint with API key
        try {
            $apiKey = \App\Models\Setting::getValue('mpedia_api_key', env('MPEDIA_API_KEY'));
            $sender = \App\Models\Setting::getValue('mpedia_sender', env('MPEDIA_SENDER'));

            $resp = $this->client->post('send', [
                'json' => [
                    'api_key' => $apiKey,
                    'sender' => $sender,
                    'to' => $phone,
                    'message' => $message
                ],
                'timeout' => 10
            ]);

            $body = json_decode((string)$resp->getBody(), true);
            return ['status'=>'sent','response'=>$body];
        } catch (\Exception $e) {
            return ['status'=>'failed','response'=>['error'=>$e->getMessage()]];
        }
    }
}