<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            SocialiteProviderSeeder::class,
            UserSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            CategoryTypeSeeder::class,
            SizeTypeSeeder::class,
            SizeSeeder::class,
            MaterialSeeder::class,
            ColorSeeder::class,
            ProductSeeder::class
        ]);
    }
}
