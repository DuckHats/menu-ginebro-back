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

        User::create([
            'name' => config('welcome.kitchenUser.name'),
            'last_name' => config('welcome.kitchenUser.last_name'),
            'email' => config('welcome.kitchenUser.email'),
            'password' => bcrypt(config('welcome.kitchenUser.password')),
            'user_type_id' => config('welcome.kitchenUser.user_type_id'),
        ]);

        User::create([
            'name' => config('welcome.basicUser.name'),
            'last_name' => config('welcome.basicUser.last_name'),
            'email' => config('welcome.basicUser.email'),
            'password' => bcrypt(config('welcome.basicUser.password')),
            'user_type_id' => config('welcome.basicUser.user_type_id'),
        ]);

        $this->command->info('Admin and Kitchen users created successfully.');
    }
}
