<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SizeType extends Model
{
    protected $fillable = [
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }
}
