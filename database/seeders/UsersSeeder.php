<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'user_type_id' => 1,
        ]);

        User::factory(10)->create();
    }
}
