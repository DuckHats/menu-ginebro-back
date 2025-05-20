<?php

namespace Database\Factories;

use Carbon\Carbon;
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
            'order_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'allergies' => $this->faker->word,
            'has_tupper' => $this->faker->boolean,
            'order_type_id' => $this->faker->numberBetween(1, 3),
            'order_status_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}
