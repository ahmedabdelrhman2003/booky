<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    protected $guarded = [];

    public $casts = [
        'expired_at'    => 'datetime'
    ];

    public function isExpired()
    {
        return now()->greaterThan($this->expired_at);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
