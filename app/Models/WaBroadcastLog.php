<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaBroadcastLog extends Model
{
    protected $fillable = ['wa_broadcast_id','user_id','phone','status','response'];

    public function broadcast()
    {
        return $this->belongsTo(WaBroadcast::class, 'wa_broadcast_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}