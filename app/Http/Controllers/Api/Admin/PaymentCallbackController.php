<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Duitku will send payment notifications here. Validate signature (simple HMAC demo)
        $payload = $request->all();
        $signatureHeader = $request->header('X-Duitku-Signature') ?? null;

        // Verify signature using merchant key from settings (admin editable)
        $merchantKey = \App\Models\Setting::getValue('duitku_merchant_key', env('DUITKU_MERCHANT_KEY'));
        if ($signatureHeader && $merchantKey) {
            $computed = hash_hmac('sha256', json_encode($payload), $merchantKey);
            if (! hash_equals($computed, $signatureHeader)) {
                return response()->json(['message'=>'Invalid signature'], 400);
            }
        }

        $orderNo = $payload['order_no'] ?? null;
        $status = strtolower($payload['status'] ?? ($payload['payment_status'] ?? 'pending'));

        $order = Order::where('order_no', $orderNo)->first();
        if (! $order) {
            return response()->json(['message'=>'order not found'], 404);
        }

        $payment = Payment::firstOrCreate(['order_id'=>$order->id], ['provider'=>'duitku','payment_id'=>$payload['payment_id'] ?? null]);
        $payment->update(['status'=>$status,'meta'=>$payload,'payment_id'=>$payload['payment_id'] ?? $payment->payment_id]);

        if ($status === 'paid' || $status === 'paid_online' || $status === 'paid_success') {
            // mark order as paid and attempt driver assignment automatically
            \DB::transaction(function() use ($order, $payload) {
                $order->status = 'paid';
                $order->save();

                // log system
                app(\App\Services\SystemLogService::class)->log(null, 'payment_callback', "Payment callback for order {$order->order_no}");

                // Prepare notification services
                $fcm = app(\App\Services\FcmService::class);
                $wa = app(\App\Services\WaService::class);

                // Try to find nearest online driver (best-effort; don't fail callback on DB/SQLite trig functions absence)
                try {
                    $distanceService = app(\App\Services\DistanceService::class);
                    $radius = (float)\App\Models\Setting::getValue('max_radius_km', env('MAX_RADIUS_KM',20));
                    $drivers = $distanceService->driversWithinRadius($order->pickup_lat, $order->pickup_lng, $radius, 5);
                    if ($drivers->count() > 0) {
                        $candidate = $drivers->first();
                        $order->driver_id = $candidate->id;
                        $order->status = 'accepted'; // driver assigned
                        $order->save();

                        // Notify driver
                        $fcm->sendToUser($candidate->user_id, 'Order Assigned', "Order {$order->order_no} telah ditugaskan kepada Anda", ['order_id'=>$order->id]);
                    }
                } catch (\Throwable $e) {
                    // Ignore driver-finding errors (e.g., SQLITE missing trig functions during tests)
                }

                // Notify mitra
                $wa->send($order->mitra->phone ?? null, "Order {$order->order_no} telah LUNAS, mohon konfirmasi.");

                // Notify customer
                $fcm->sendToUser($order->customer_id, 'Order Paid', "Order {$order->order_no} status: paid", ['order_id'=>$order->id]);
            });
        }

        return response()->json(['message'=>'ok']);
    }
}
