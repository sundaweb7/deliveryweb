<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = ['name','email','phone','password','role','meta'];

    protected $hidden = ['password','remember_token'];

    protected $casts = ['meta' => 'array'];

    public function mitra()
    {
        return $this->hasOne(Mitra::class);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }

    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'owner');
    }
}
