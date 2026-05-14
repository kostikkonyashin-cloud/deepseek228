<?php

namespace Database\Seeders;

use App\Models\SocialiteProvider;
use Illuminate\Database\Seeder;

class SocialiteProviderSeeder extends Seeder
{
    private array $providers = ['yandex'];

    public function run(): void
    {
        foreach($this->providers as $provider) {
            SocialiteProvider::create([
                'name' => $provider
            ]);
        }
    }
}
