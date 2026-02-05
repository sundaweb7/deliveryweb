<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    public function createQrisPayment($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Read Duitku settings (admin-editable) with env fallback
        $merchantCode = \App\Models\Setting::getValue('duitku_merchant_code', env('DUITKU_MERCHANT_CODE'));
        $merchantKey = \App\Models\Setting::getValue('duitku_merchant_key', env('DUITKU_MERCHANT_KEY'));
        $apiUrl = \App\Models\Setting::getValue('duitku_api_url', env('DUITKU_API_URL', 'https://api-sandbox.duitku.com'));
        $isTest = (bool) \App\Models\Setting::getValue('duitku_is_test_mode', env('DUITKU_IS_TEST_MODE', true));

        $amount = $order->subtotal + $order->delivery_fee;
        $orderNo = $order->order_no;

        $payload = [
            'merchantCode' => $merchantCode,
            'amount' => $amount,
            'orderNo' => $orderNo,
            'callbackUrl' => \App\Models\Setting::getValue('duitku_callback_url', env('DUITKU_CALLBACK_URL')),
            'productDetails' => 'Order '.$orderNo
        ];

        // Compute signature - HMAC SHA256 over JSON payload using merchant key
        $body = json_encode($payload, JSON_UNESCAPED_SLASHES);
        $signature = hash_hmac('sha256', $body, $merchantKey);

        // Perform HTTP request to Duitku (sandbox or prod depending on settings)
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Duitku-Signature' => $signature,
                'X-Duitku-Merchant' => $merchantCode,
            ])->post(rtrim($apiUrl, '/').'/qris', $payload);
        } catch (\Throwable $e) {
            // create payment record with failed meta
            $payment = Payment::create(['order_id'=>$order->id,'provider'=>'duitku','status'=>'failed','meta'=>['error'=>$e->getMessage()]]);
            return ['ok'=>false,'message'=>'failed_request','error'=>$e->getMessage()];
        }

        if (! $response->successful()) {
            $payment = Payment::create(['order_id'=>$order->id,'provider'=>'duitku','status'=>'failed','meta'=>$response->json()]);
            return ['ok'=>false,'message'=>'api_error','response'=>$response->json()];
        }

        $res = $response->json();

        // Expecting Duitku returns pay_url or qr_url; fallback to returned token
        $payUrl = $res['pay_url'] ?? ($res['qr_url'] ?? ($res['data']['pay_url'] ?? null));
        $reference = $res['reference'] ?? ($res['orderNo'] ?? $orderNo);

        $payment = Payment::create([
            'order_id'=>$order->id,
            'provider'=>'duitku',
            'status'=>'pending',
            'meta'=>$res,
            'reference'=>$reference,
            'method'=>'qris'
        ]);

        return ['ok'=>true,'payment_id'=>$payment->id,'pay_url'=>$payUrl,'meta'=>$res];
    }
}
