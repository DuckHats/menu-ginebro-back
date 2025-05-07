<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Dish;

class OrderDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $dishes = Dish::all();

        foreach ($orders as $order) {
            OrderDetail::factory(rand(1, 3))->create([
                'order_id' => $order->id,
                'dish_id' => $dishes->random()->id
            ]);
        }
    }
}
