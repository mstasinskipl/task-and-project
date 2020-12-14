<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Zdzichu Admin',
            'email' => 'test@example.org',
            'password' => bcrypt('test123'),
            'type' => User::ADMIN_TYPE
        ]);
        User::create([
                'name' => 'Bogdan Zwykły ',
                'email' => 'test1@example.org',
                'password' => bcrypt('test123'),
                'type' => User::USER_TYPE
            ]);
    }
}
