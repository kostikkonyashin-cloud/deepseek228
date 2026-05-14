<?php

namespace Database\Seeders;

use App\Models\CategoryType;
use Illuminate\Database\Seeder;
use Str;

class CategoryTypeSeeder extends Seeder
{
    private array $types = [
        // Самокаты
        [
            'name' => 'Стрит самокаты',
            'category_id' => 1,
        ],
        [
            'name' => 'Парковые самокаты',
            'category_id' => 1,
        ],
        [
            'name' => 'Батутные самокаты',
            'category_id' => 1,
        ],
        [
            'name' => 'Детские самокаты',
            'category_id' => 1,
        ],
        // Защита
        [
            'name' => 'Шлемы',
            'category_id' => 5,
        ],
        [
            'name' => 'Защита ног',
            'category_id' => 5,
        ],
        // Запчасти
        [
            'name' => 'Рули',
            'category_id' => 4,
        ],
        [
            'name' => 'Деки',
            'category_id' => 4,
        ],
        [
            'name' => 'Вилки',
            'category_id' => 4,
        ],
        [
            'name' => 'Колеса',
            'category_id' => 4,
        ],
        [
            'name' => 'Тормоза',
            'category_id' => 4,
        ],
        // Одежда
        [
            'name' => 'Футболки и лонгсливы',
            'category_id' => 6,
        ],
        [
            'name' => 'Худи и свитшоты',
            'category_id' => 6,
        ],
        [
            'name' => 'Джинсы',
            'category_id' => 6,
        ],
        [
            'name' => 'Брюки',
            'category_id' => 6,
        ],
        [
            'name' => 'Куртки',
            'category_id' => 6,
        ],
        // Аксессуары
        [
            'name' => 'Сумки',
            'category_id' => 8,
        ],
        [
            'name' => 'Рюкзаки',
            'category_id' => 8,
        ],
        [
            'name' => 'Носки',
            'category_id' => 8,
        ],
        [
            'name' => 'Шапки',
            'category_id' => 8,
        ],
        [
            'name' => 'Кепки и панамы',
            'category_id' => 8,
        ],
        [
            'name' => 'Чехлы',
            'category_id' => 8,
        ],
    ];
    public function run(): void
    {
        foreach ($this->types as $type) {
            CategoryType::create([
                'name' => $type['name'],
                'slug' => Str::slug($type['name']),
                'category_id' => $type['category_id']
            ]);
        }
    }
}
