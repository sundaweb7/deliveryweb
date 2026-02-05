<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaBroadcast extends Model
{
    protected $fillable = ['title','message','target','status'];

    public function logs()
    {
        return $this->hasMany(WaBroadcastLog::class);
    }
}