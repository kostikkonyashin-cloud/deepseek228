<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_material', 'material_id', 'product_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
