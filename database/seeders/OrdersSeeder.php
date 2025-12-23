<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $basicUserEmail = config('welcome.basicUser.email');
        $users = User::where('user_type_id', User::ROLE_USER)->where('email', '!=', $basicUserEmail)->get();
        $platos_primero = [
            'Amanida verda',
            'Sopa de fideus',
            'Macarrons a la bolonyesa',
            'Crema de verdures',
            'Arròs a la cubana'
        ];

        $platos_segundo = [
            'Pollastre a la planxa',
            'Lluç al forn',
            'Mandonguilles amb tomàquet',
            'Lloms de porc amb patates',
            'Truita de patates'
        ];

        $platos_postre = [
            'Fruita del temps',
            'Iogurt natural',
            'Flam d’ou',
            'Gelat de vainilla',
            'Coca casolana'
        ];


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
