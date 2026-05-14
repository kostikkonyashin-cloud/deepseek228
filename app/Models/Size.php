<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'size_type_id',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_size', 'size_id', 'product_id');
    }

    public function sizeType()
    {
        return $this->belongsTo(SizeType::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}
