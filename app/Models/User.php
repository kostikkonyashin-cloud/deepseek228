<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['full_name', 'login', 'email', 'password', 'is_blocked', 'role_id'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function socialiteAccounts()
    {
        return $this->hasMany(SocialiteAccount::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites')->withTimestamps();
    }

    public function favoriteProducts()
    {
        return $this->hasMany(Favorite::class);
    }

    public function isClient()
    {
        return $this->role->name === 'Клиент';
    }

    public function isManager()
    {
        return $this->role->name === 'Менеджер по продажам';
    }

    public function isAdmin()
    {
        return $this->role->name === 'Администратор';
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return $value ? date('d.m.Y', strtotime($value)) : null;
            }
        );
    }
}
