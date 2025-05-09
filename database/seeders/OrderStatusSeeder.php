<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderStatus::create(['name' => 'pendent']);
        OrderStatus::create(['name' => 'en preparació']);
        OrderStatus::create(['name' => 'entregat']);
    }
}
