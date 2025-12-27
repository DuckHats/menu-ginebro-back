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
        $seeders = [
            UserTypeSeeder::class,
            AdminUserSeeder::class,
            AllergySeeder::class,
            OrderTypeSeeder::class,
            OrderStatusSeeder::class,
            ConfigurationSeeder::class,
        ];

        if (app()->environment('local')) {
            $seeders = array_merge($seeders, [
                UsersSeeder::class,
                ImagesSeeder::class,
                OrdersSeeder::class,
                MenusAndDishesSeeder::class,
                TransactionsSeeder::class,
            ]);
        }

        $this->call($seeders);
    }
}
