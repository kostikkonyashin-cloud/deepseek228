<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    private array $brands = [
        ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
        ['name' => 'Ninebot', 'slug' => 'ninebot'],
        ['name' => 'Kugo', 'slug' => 'kugo'],
        ['name' => 'Hoverboard', 'slug' => 'hoverboard'],
        ['name' => 'Segway', 'slug' => 'segway'],
        ['name' => 'Razor', 'slug' => 'razor'],
        ['name' => 'Micro', 'slug' => 'micro'],
        ['name' => 'Oxelo', 'slug' => 'oxelo'],
    ];

    public function run(): void
    {
        foreach ($this->brands as $brand) {
            Brand::create($brand);
        }
    }
}
