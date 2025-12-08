<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => config('welcome.AdminUser.name'),
            'last_name' => config('welcome.AdminUser.last_name'),
            'email' => config('welcome.AdminUser.email'),
            'password' => bcrypt(config('welcome.AdminUser.password')),
            'user_type_id' => config('welcome.AdminUser.user_type_id'),
        ]);
    }
}
