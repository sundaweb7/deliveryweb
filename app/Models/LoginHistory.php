<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $fillable = ['user_id','role','ip','user_agent'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}