<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    protected $fillable = ['wallet_id','type','amount','reference','meta'];

    protected $casts = ['meta'=>'array','amount'=>'integer'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
