<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialiteAccount extends Model
{
    protected $fillable = [
        'user_id',
        'socialite_provider_id',
        'socialite_account_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function socialiteProvider() {
        return $this->belongsTo(SocialiteProvider::class);
    }
}
