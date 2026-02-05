<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderTimeline;
use App\Services\WalletService;

class OrderObserver
{
    public function updated(Order $order)
    {
        if ($order->isDirty('status')) {
            $new = $order->status;
            OrderTimeline::create(['order_id'=>$order->id,'status'=>$new,'note'=>'Status changed to '.$new]);

            if ($new === 'delivered') {
                // distribute earnings
                $walletService = new WalletService();
                try {
                    $walletService->distributeEarnings($order);
                } catch (\Exception $e) {
                    // log and continue
                    logger()->error('Distribute earnings failed for order '.$order->id.' : '.$e->getMessage());
                }
            }
        }
    }
}