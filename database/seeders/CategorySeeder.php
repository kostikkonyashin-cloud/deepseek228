<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    private array $categories = [
        ['name' => 'Самокаты', 'slug' => 'samokaty'], // 1
        ['name' => 'BMX', 'slug' => 'bmx'], // 2
        ['name' => 'Скейты', 'slug' => 'skate'], // 3
        ['name' => 'Запчасти', 'slug' => 'zapchasti'], // 4
        ['name' => 'Защита', 'slug' => 'zashchita'], // 5
        ['name' => 'Одежда', 'slug' => 'odezhda'], // 6
        ['name' => 'Обувь', 'slug' => 'obuv'], // 7
        ['name' => 'Аксессуары', 'slug' => 'aksessuary'], // 8
    ];

    public function run(): void
    {
        foreach ($this->categories as $category) {
            $newCategory = Category::create($category);
            $newCategory->update([
                'icon_path' => "/storage/images/miniicon{$newCategory->id}.png"
            ]);
        }
    }
}
