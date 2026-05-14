<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;

#[Hidden(['created_at', 'updated_at'])]
class SocialiteProvider extends Model
{
    protected $fillable = [
        'name'
    ];

    public function socialiteAccounts()
    {
        return $this->hasMany(SocialiteAccount::class);
    }
}
