<?php

namespace App\Http\Resources\Api\Mobile;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'order_no'=>$this->order_no,
            'status'=>$this->status,
            'subtotal'=>$this->subtotal,
            'delivery_fee'=>$this->delivery_fee,
            'admin_fee'=>$this->admin_fee,
            'distance_km'=>$this->distance_km,
            'items'=> $this->whenLoaded('items'),
            'mitra'=> $this->whenLoaded('mitra'),
            'driver'=> $this->whenLoaded('driver')
        ];
    }
}
