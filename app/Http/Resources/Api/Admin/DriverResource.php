<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'address'=>$this->address,
            'wa_contact'=>$this->wa_contact,
            'vehicle_type'=>$this->vehicle_type,
            'vehicle_number'=>$this->vehicle_number,
            'bank_account_number'=>$this->bank_account_number,
            'bank_name'=>$this->bank_name,
            'bank_account_name'=>$this->bank_account_name,
            'is_active'=>$this->is_active,
            'user'=> new UserResource($this->whenLoaded('user')),
        ];
    }
}
