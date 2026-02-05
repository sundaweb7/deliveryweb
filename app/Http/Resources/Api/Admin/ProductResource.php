<?php

namespace App\Http\Resources\Api\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'price'=>$this->price,
            'stock'=>$this->stock,
            'image_url' => $this->image ? asset('storage/products/'.$this->image) : null,
            'mitra'=> new MitraResource($this->whenLoaded('mitra')),
        ];
    }
}
