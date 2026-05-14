<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    private array $sizes = [
        ['name' => 'S (до 120 см)', 'slug' => 's', 'size_type_id' => 1],
        ['name' => 'M (120-150 см)', 'slug' => 'm', 'size_type_id' => 1],
        ['name' => 'L (150-170 см)', 'slug' => 'l', 'size_type_id' => 1],
        ['name' => 'XL (от 170 см)', 'slug' => 'xl', 'size_type_id' => 1],

        ['name' => 'XS', 'slug' => 'xs', 'size_type_id' => 2],
        ['name' => 'S', 'slug' => 's_clothes', 'size_type_id' => 2],
        ['name' => 'M', 'slug' => 'm_clothes', 'size_type_id' => 2],
        ['name' => 'L', 'slug' => 'l_clothes', 'size_type_id' => 2],
        ['name' => 'XL', 'slug' => 'xl_clothes', 'size_type_id' => 2],
        ['name' => 'XXL', 'slug' => 'xxl', 'size_type_id' => 2],

        ['name' => '35', 'slug' => '35', 'size_type_id' => 3],
        ['name' => '36', 'slug' => '36', 'size_type_id' => 3],
        ['name' => '37', 'slug' => '37', 'size_type_id' => 3],
        ['name' => '38', 'slug' => '38', 'size_type_id' => 3],
        ['name' => '39', 'slug' => '39', 'size_type_id' => 3],
        ['name' => '40', 'slug' => '40', 'size_type_id' => 3],
        ['name' => '41', 'slug' => '41', 'size_type_id' => 3],
        ['name' => '42', 'slug' => '42', 'size_type_id' => 3],
        ['name' => '43', 'slug' => '43', 'size_type_id' => 3],
        ['name' => '44', 'slug' => '44', 'size_type_id' => 3],
        ['name' => '45', 'slug' => '45', 'size_type_id' => 3],

        ['name' => 'Универсальный', 'slug' => 'universal', 'size_type_id' => 4],
        ['name' => 'Стандарт', 'slug' => 'standard', 'size_type_id' => 4],
        ['name' => 'Профессиональный', 'slug' => 'pro', 'size_type_id' => 4],
    ];
    public function run(): void
    {
        foreach ($this->sizes as $size) {
            Size::create($size);
        }
    }
}
