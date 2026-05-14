<?php

namespace Database\Seeders;

use App\Models\SizeType;
use Illuminate\Database\Seeder;

class SizeTypeSeeder extends Seeder
{
    private array $types = [
        1,
        6,
        7,
        4,
    ];
    public function run(): void
    {
        foreach ($this->types as $type) {
            SizeType::create([
                'category_id' => $type
            ]);
        }
    }
}
