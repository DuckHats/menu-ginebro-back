<?php

namespace Database\Seeders;

use App\Models\OrderType;
use Illuminate\Database\Seeder;

class OrderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderType::create(['name' => 'Primer plat + Segon plat + Postre']);
        OrderType::create(['name' => 'Primer plat + Postre']);
        OrderType::create(['name' => 'Segon plat + Postre']);
    }
}
