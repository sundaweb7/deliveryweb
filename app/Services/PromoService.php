<?php

namespace App\Services;

use App\Models\Promo;
use App\Models\PromoUsage;
use App\Models\Order;
use App\Models\User;
use App\Models\SystemLog;
use Illuminate\Support\Facades\DB;

class PromoService
{
    public function validatePromoForUser(string $code, User $user, float $orderAmount)
    {
        $promo = Promo::where('code', $code)->first();
        if (! $promo) return ['ok'=>false,'message'=>'Promo tidak ditemukan'];
        if (! $promo->isActive()) return ['ok'=>false,'message'=>'Promo tidak aktif atau sudah kadaluarsa'];
        if ($orderAmount < (float)$promo->min_order) return ['ok'=>false,'message'=>"Minimal order untuk promo ini adalah " . number_format($promo->min_order,2)];
        if ($promo->usage_limit && $promo->used_count >= $promo->usage_limit) return ['ok'=>false,'message'=>'Promo sudah mencapai batas penggunaan'];
        // check per-user usage
        $usedByUser = PromoUsage::where('promo_id', $promo->id)->where('user_id', $user->id)->count();
        if ($usedByUser > 0) return ['ok'=>false,'message'=>'Anda sudah menggunakan promo ini'];

        // calculate discount
        if ($promo->type === 'percent') {
            $discount = ($orderAmount * (float)$promo->value) / 100;
            if ($promo->max_discount) $discount = min($discount, (float)$promo->max_discount);
        } else {
            $discount = (float)$promo->value;
        }

        return ['ok'=>true,'promo'=>$promo,'discount'=>round($discount,2)];
    }

    public function applyPromoToOrder(Promo $promo, Order $order, ?User $user = null)
    {
        return DB::transaction(function() use ($promo,$order,$user) {
            // create usage
            $usage = PromoUsage::create(['promo_id'=>$promo->id,'user_id'=>$user? $user->id : null,'order_id'=>$order->id]);
            // increment used_count
            $promo->used_count = $promo->used_count + 1;
            $promo->save();

            // log system
            app(SystemLogService::class)->log($user? $user->id : null, 'promo_used', "Promo {$promo->code} digunakan untuk order {$order->order_no}");

            return $usage;
        });
    }
}