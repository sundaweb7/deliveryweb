<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Wallet extends Model
{
    protected $fillable = ['owner_id','owner_type','balance'];

    protected $casts = ['balance'=>'integer'];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function histories()
    {
        return $this->hasMany(WalletHistory::class);
    }
}
