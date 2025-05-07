<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => \App\Models\Menu::factory(),
            'dish_date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['Primer', 'Segon', 'Segon plat + Postres']),
            'options' => json_encode([$this->faker->word(), $this->faker->word()]),
        ];
    }
}
