<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug', 'is_active', 'category_id'])]
class CategoryType extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
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
