<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = ['order_no','customer_id','mitra_id','driver_id','pickup_lat','pickup_lng','drop_lat','drop_lng','distance_km','delivery_fee','admin_fee','subtotal','status'];

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function timelines()
    {
        return $this->hasMany(OrderTimeline::class);
    }
}
