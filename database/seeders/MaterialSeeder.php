<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    private array $materials = [
        ['name' => 'Алюминий', 'slug' => 'aluminum'],
        ['name' => 'Сталь', 'slug' => 'steel'],
        ['name' => 'Карбон', 'slug' => 'carbon'],
        ['name' => 'Пластик', 'slug' => 'plastic'],
        ['name' => 'Хлопок', 'slug' => 'cotton'],
        ['name' => 'Полиэстер', 'slug' => 'polyester'],
        ['name' => 'Кожа', 'slug' => 'leather'],
        ['name' => 'Текстиль', 'slug' => 'textile'],
        ['name' => 'Поролон', 'slug' => 'foam'],
        ['name' => 'Резина', 'slug' => 'rubber'],
    ];
    public function run(): void
    {
        foreach ($this->materials as $material) {
            Material::create($material);
        }
    }
}
