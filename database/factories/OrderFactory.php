<?php

namespace Database\Factories;

use App\Models\User;
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
            'user_id' => $this->faker->numberBetween(1, 10),
            'order_date' => $this->faker->date,
            'allergies' => $this->faker->word,
            'order_type_id' => $this->faker->numberBetween(1, 3),
            'order_status_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}
