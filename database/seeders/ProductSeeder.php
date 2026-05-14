<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\CategoryType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private array $productNames = [
        'samokaty' => [
            'Электрический самокат Premium',
            'Складной самокат',
            'Трюковой самокат Pro',
            'Кикскутер детский',
            'Самокат для детей',
            'Гироскутер',
            'Самокат-трансформер',
            'Электросамокат Max'
        ],
        'bmx' => [
            'BMX Stunt',
            'BMX Race',
            'BMX Freestyle',
            'BMX Dirt',
            'BMX Street',
            'BMX Park Pro',
            'BMX Flatland',
            'BMX Expert'
        ],
        'skate' => [
            'Классический скейтборд',
            'Лонгборд',
            'Круизер',
            'Пенниборд',
            'Электрический скейт',
            'Скейт для трюков',
            'Скейт для начинающих',
            'Электроскейтборд'
        ],
        'zapchasti' => [
            'Колёса для самоката',
            'Рулевая колонка',
            'Тормозная система',
            'Подшипники',
            'Деки для скейта',
            'Гриплейта',
            'Болты и крепежи',
            'Амортизаторы'
        ],
        'zashchita' => [
            'Шлем защитный',
            'Наколенники',
            'Налокотники',
            'Защита запястий',
            'Защита спины',
            'Защита шеи',
            'Защитный костюм',
            'Защита груди'
        ],
        'odezhda' => [
            'Бомбер утепленный',
            'Худи',
            'Кофта',
            'Футболка',
            'Штаны',
            'Толстовка',
            'Куртка ветрозащитная',
            'Дождевик'
        ],
        'obuv' => [
            'Кроссовки для катания',
            'Кеды',
            'Слипоны',
            'Вибрам пятипальцы',
            'Спортивная обувь',
            'Треккинговые ботинки',
            'Лёгкие кроссовки',
            'Баскетбольные кроссовки'
        ],
        'aksessuary' => [
            'Сумка для шлема',
            'Рюкзак',
            'Бутылка для воды',
            'Светоотражатели',
            'Перчатки',
            'Накладки на ручки',
            'Чехол для самоката',
            'Ключи для колёс'
        ],
    ];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();

        $categories = Category::all()->keyBy('slug');
        $brands = Brand::all();

        $categoryTypes = CategoryType::all()->groupBy('category_id');

        foreach ($categories as $slug => $category) {
            $categoryBrands = $brands->random(4);
            $descriptions = $this->getDescriptions();

            $typesForCategory = $categoryTypes[$category->id] ?? collect();
            $typesCount = $typesForCategory->count();

            for ($i = 0; $i < 8; $i++) {
                $brand = $categoryBrands[$i % 4];
                $price = rand(1000, 50000);
                $discount = rand(0, 30) <= 20 ? rand(5, 30) : null;

                Product::create([
                    'name' => $this->productNames[$slug][$i],
                    'slug' => Str::slug($this->productNames[$slug][$i]) . '-' . uniqid(),
                    'description' => $descriptions[array_rand($descriptions)],
                    'price' => $price,
                    'discount' => $discount,
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'category_type_id' => 2,
                ]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function getDescriptions(): array
    {
        return [
            'Отличное качество по доступной цене. Идеально подходит для ежедневного использования.',
            'Профессиональное снаряжение для настоящих спортсменов. Максимальная надежность.',
            'Идеально подходит для начинающих и профессионалов. Легкий и удобный.',
            'Надежность и стиль в каждой детали. Современный дизайн.',
            'Современные технологии и инновационные материалы. Высокая прочность.',
            'Проверено временем и тысячами пользователей. Отличный выбор.',
            'Максимальная защита и комфорт. Безопасность превыше всего.',
            'Создано для тех, кто выбирает лучшее. Гарантия качества.',
            'Высокое качество сборки. Долговечность и надежность.',
            'Инновационный дизайн. Отличные технические характеристики.',
        ];
    }
}
