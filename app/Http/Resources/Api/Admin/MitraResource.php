<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class MitraResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'business_name'=>$this->business_name,
            'address'=>$this->address,
            'lat'=>$this->lat,
            'lng'=>$this->lng,
            'bank_account_number'=>$this->bank_account_number,
            'bank_name'=>$this->bank_name,
            'bank_account_name'=>$this->bank_account_name,
            'is_active'=>$this->is_active,
            'user'=> new UserResource($this->whenLoaded('user')),
        ];
    }
}
