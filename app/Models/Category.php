<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['image_path', 'is_active', 'icon_path', 'slug', 'name'])]
#[Hidden(['created_at', 'updated_at'])]
class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon_path',
        'is_active',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categoryTypes()
    {
        return $this->hasMany(CategoryType::class);
    }

    public function sizeTypes()
    {
        return $this->hasMany(SizeType::class);
    }
}
