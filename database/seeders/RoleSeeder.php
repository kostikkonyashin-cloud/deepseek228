<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private array $roles = [
        ['name' => 'Клиент', 'slug' => 'client'],
        ['name' => 'Менеджер по продажам', 'slug' => 'sales_manager'],
        ['name' => 'Администратор', 'slug' => 'admin'],
    ];
    public function run(): void
    {
        foreach ($this->roles as $role) {
            Role::create($role);
        }
    }
}
