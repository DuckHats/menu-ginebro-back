<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $platos_primero = ['Ensalada', 'Sopa', 'Macarrones'];
        $platos_segundo = ['Pollo', 'Pescado', 'Ternera'];
        $platos_postre = ['Fruta', 'Yogur', 'Helado'];

        foreach ($users as $user) {
            for ($i = -7; $i <= 7; $i++) {
                $order_type_id = rand(1, 3);
                $order_date = Carbon::today()->copy()->addDays($i)->format('Y-m-d');

                $order = Order::create([
                    'user_id' => $user->id,
                    'order_date' => $order_date,
                    'allergies' => fake()->word,
                    'has_tupper' => fake()->boolean,
                    'order_type_id' => $order_type_id,
                    'order_status_id' => rand(1, 3),
                ]);

                $detailData = [
                    'order_id' => $order->id,
                    'option1' => null,
                    'option2' => null,
                    'option3' => null,
                ];

                if ($order_type_id == 1) {
                    $detailData['option1'] = fake()->randomElement($platos_primero);
                    $detailData['option2'] = fake()->randomElement($platos_segundo);
                    $detailData['option3'] = fake()->randomElement($platos_postre);
                } elseif ($order_type_id == 2) {
                    $detailData['option1'] = fake()->randomElement($platos_primero);
                    $detailData['option3'] = fake()->randomElement($platos_postre);
                } elseif ($order_type_id == 3) {
                    $detailData['option2'] = fake()->randomElement($platos_segundo);
                    $detailData['option3'] = fake()->randomElement($platos_postre);
                }

                OrderDetail::create($detailData);
            }
        }
    }
}