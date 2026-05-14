<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_available',
        'price',
        'discount',
        'product_count',
        'category_id',
        'category_type_id',
        'brand_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categoryType()
    {
        return $this->belongsTo(CategoryType::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'product_material', 'product_id', 'material_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color', 'product_id', 'color_id');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size', 'product_id', 'size_id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeWhereCategory(Builder $query, string $category): Builder
    {
        $query->whereHas('category', function ($q) use ($category) {
            $q->where('slug', $category);
        });

        return $query;
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q
                    ->where('slug', $filters['category']);
            });
        }

        if (!empty($filters['categories'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q
                    ->whereIn('slug', $filters['categories']);
            });
        }

        if (!empty($filters['category_type'])) {
            $query->whereHas('categoryType', function ($q) use ($filters) {
                $q->where('slug', $filters['category_type']);
            });
        }

        if (!empty($filters['brand'])) {
            $query->whereHas('brand', function ($q) use ($filters) {
                $q->where('slug', $filters['brand']);
            });
        }

        if (!empty($filters['colors']) && is_array($filters['colors'])) {
            $query->whereHas('colors', function ($q) use ($filters) {
                $q->whereIn('slug', $filters['colors']);
            });
        }

        if (!empty($filters['materials']) && is_array($filters['materials'])) {
            $query->whereHas('materials', function ($q) use ($filters) {
                $q->whereIn('slug', $filters['materials']);
            });
        }

        if (!empty($filters['sizes']) && is_array($filters['sizes'])) {
            $query->whereHas('sizes', function ($q) use ($filters) {
                $q->whereIn('slug', $filters['sizes']);
            });
        }

        if (!empty($filters['price_min'])) {
            $query->where('price', '>=', (float) $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('price', '<=', (float) $filters['price_max']);
        }

        if (isset($filters['is_available'])) {
            $query->where('is_available', (bool) $filters['is_available']);
        }

        if (!empty($filters['discounted'])) {
            $query->whereNotNull('discount')->where('discount', '>', 0);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['title'])) {
            $query->where('name', 'like', '%' . $filters['title'] . '%');
        }

        return $query;
    }
}
