<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private array $users = [
        [
            'full_name' => 'Иванов Иван Иванович',
            'login' => 'ivanovcool',
            'email' => 'client1@example.com',
            'password' => 'password123',
            'role_id' => 1,
        ],
        [
            'full_name' => 'Петров Петр Петрович',
            'login' => 'petrovcool',
            'email' => 'client2@example.com',
            'password' => 'password123',
            'role_id' => 1,
        ],
        [
            'full_name' => 'Сидоров Сидр Сидорович',
            'login' => 'cidorcool',
            'email' => 'client3@example.com',
            'password' => 'password123',
            'role_id' => 1,
        ],
        [
            'full_name' => 'Дмитриев Дмитрий Дмитриевич',
            'login' => 'dmitriycool',
            'email' => 'client4@example.com',
            'password' => 'password123',
            'role_id' => 1,
        ],
        [
            'full_name' => 'Александров Александр Александрович',
            'login' => 'alexandrcool',
            'email' => 'client5@example.com',
            'password' => 'password123',
            'role_id' => 1,
        ],
        [
            'full_name' => 'Менеджеровна Анна Менеджеровична',
            'login' => 'annacool',
            'email' => 'manager1@example.com',
            'password' => 'password123',
            'role_id' => 2,
        ],
        [
            'full_name' => 'Менеджеровна Ольга Менеджеровична',
            'login' => 'olgacool',
            'email' => 'manager2@example.com',
            'password' => 'password123',
            'role_id' => 2,
        ],
        [
            'full_name' => 'Админов Админ Админович',
            'login' => 'admincool',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role_id' => 3,
        ],
    ];

    public function run(): void
    {
        foreach ($this->users as $user) {
            User::create($user);
        }
    }
}
