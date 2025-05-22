<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->imageUrl(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'month' => $this->faker->monthName(),
            'year' => $this->faker->year(),
        ];
    }
}
