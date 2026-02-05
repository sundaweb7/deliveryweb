<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id','name','address','wa_contact','vehicle_type','vehicle_number','is_active','lat','lng','bank_account_number','bank_name','bank_account_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function locations()
    {
        return $this->hasMany(DriverLocation::class);
    }
}
