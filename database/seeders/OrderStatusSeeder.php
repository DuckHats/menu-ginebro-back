<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderStatus::create(['name' => 'Pendent']);
        OrderStatus::create(['name' => 'En preparació']);
        OrderStatus::create(['name' => 'Entregat']);
    }
}
