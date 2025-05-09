<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'order_date' => $this->faker->date,
            'allergies' => $this->faker->word,
            'type' => $this->faker->randomElement(['Primer plat + Segon plat + Postres', 'Primer plat + Postres', 'Segon plat + Postres']),
            'status' => $this->faker->randomElement(['pendent', 'preparat', 'entregat']),
        ];
    }
}
