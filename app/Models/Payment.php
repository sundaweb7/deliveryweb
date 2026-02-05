<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['payment_id','order_id','provider','status','meta','reference','method','amount','raw_callback'];

    protected $casts = ['meta' => 'array', 'raw_callback' => 'array'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
