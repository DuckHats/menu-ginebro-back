<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Executar seeders en ordre
        $this->call([
            UserTypeSeeder::class,
            UsersSeeder::class,
            DaysSeeder::class,
            ImagesSeeder::class,
            MenusSeeder::class,
            MenuDaysSeeder::class,
            OrderTypeSeeder::class,
            OrderStatusSeeder::class,
            OrdersSeeder::class,
            DishTypeSeeder::class,
            DishesSeeder::class,
            OrderDetailsSeeder::class,
            
            
        ]);
    }
}
