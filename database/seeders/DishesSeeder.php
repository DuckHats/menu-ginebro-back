<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dish;
use App\Models\Menu;

class DishesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = Menu::all();

        foreach ($menus as $menu) {
            Dish::factory(5)->create(['menu_id' => $menu->id]);
        }
    }
}
