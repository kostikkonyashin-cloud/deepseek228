<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    private array $colors = [
        ['name' => 'Черный', 'slug' => 'black', 'code' => '#000000'],
        ['name' => 'Белый', 'slug' => 'white', 'code' => '#FFFFFF'],
        ['name' => 'Красный', 'slug' => 'red', 'code' => '#FF0000'],
        ['name' => 'Синий', 'slug' => 'blue', 'code' => '#0000FF'],
        ['name' => 'Зеленый', 'slug' => 'green', 'code' => '#00FF00'],
        ['name' => 'Желтый', 'slug' => 'yellow', 'code' => '#FFFF00'],
        ['name' => 'Розовый', 'slug' => 'pink', 'code' => '#FFC0CB'],
        ['name' => 'Фиолетовый', 'slug' => 'purple', 'code' => '#800080'],
        ['name' => 'Оранжевый', 'slug' => 'orange', 'code' => '#FFA500'],
        ['name' => 'Серый', 'slug' => 'gray', 'code' => '#808080'],
    ];

    public function run(): void
    {
        foreach ($this->colors as $color) {
            Color::create($color);
        }
    }
}
