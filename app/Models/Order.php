<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'status',
        'total_amount',
        'user_id',
        'address_id',
        'payment_id',
        'ordered_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(User::class);
    }

    public function color()
    {
        return $this->belongsTo(User::class);
    }

    public function size()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function orderedAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value)
                    return null;
                return date('d.m.Y в H:i', strtotime($value));
            }
        );
    }
}
