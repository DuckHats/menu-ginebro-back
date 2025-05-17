<?php

namespace Database\Seeders;

use App\Models\DishType;
use Illuminate\Database\Seeder;

class DishTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DishType::create(['name' => 'Primer']);
        DishType::create(['name' => 'Segon']);
        DishType::create(['name' => 'Postre']);
    }
}
