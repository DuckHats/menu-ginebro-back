<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\Menu;
use App\Models\MenuDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = Menu::all();
        $days = Day::all();

        foreach ($menus as $menu) {
            foreach ($days as $day) {
                MenuDay::create([
                    'menu_id' => $menu->id,
                    'day_id' => $day->id,
                    'specific_date' => now()->addDays(rand(1, 30))
                ]);
            }
        }
    }
}
