<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\DishType;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MenusAndDishesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DishType::create(['name' => 'Primer']);
        DishType::create(['name' => 'Segon']);
        DishType::create(['name' => 'Postre']);

        $primerId = DishType::where('name', 'Primer')->first()->id;
        $segonId = DishType::where('name', 'Segon')->first()->id;
        $postreId = DishType::where('name', 'Postre')->first()->id;

        $platos_primero = ['Ensalada', 'Sopa', 'Macarrones', 'Gazpacho', 'Arroz'];
        $platos_segundo = ['Pollo', 'Pescado', 'Ternera', 'Cerdo', 'Tofu'];
        $platos_postre = ['Fruta', 'Yogur', 'Helado', 'Flan', 'Natillas'];

        for ($i = -15; $i <= 15; $i++) {
            $day = Carbon::today()->copy()->addDays($i)->format('Y-m-d');
            $menu = Menu::create(['day' => $day]);

            Dish::create([
                'menu_id' => $menu->id,
                'dish_type_id' => $primerId,
                'options' => json_encode([fake()->randomElement($platos_primero), fake()->randomElement($platos_primero)]),
            ]);
            Dish::create([
                'menu_id' => $menu->id,
                'dish_type_id' => $segonId,
                'options' => json_encode([fake()->randomElement($platos_segundo), fake()->randomElement($platos_segundo)]),
            ]);
            Dish::create([
                'menu_id' => $menu->id,
                'dish_type_id' => $postreId,
                'options' => json_encode([fake()->randomElement($platos_postre), fake()->randomElement($platos_postre)]),
            ]);
        }
    }
}
