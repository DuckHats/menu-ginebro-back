<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $orders = Order::all();

        foreach ($orders as $order) {
            OrderDetail::factory(rand(1, 3))->create([
                'order_id' => $order->id,
                'option1' => $faker->randomElement(['option1a', 'option1b', 'option1c']),
                'option2' => $faker->randomElement(['option2a', 'option2b', 'option2c']),
                'option3' => $faker->randomElement(['option3a', 'option3b', 'option3c']),
            ]);
        }
    }
}
